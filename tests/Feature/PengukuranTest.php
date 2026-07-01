<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Unit;
use App\Models\Periode;
use App\Models\Standar;
use App\Models\Indikator;
use App\Models\Pengukuran;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class PengukuranTest extends TestCase
{
    use RefreshDatabase;

    protected $auditee;
    protected $pengukuran;

    protected function setUp(): void
    {
        parent::setUp();
        Role::create(['name' => 'Auditee']);
        
        $unit = Unit::create(['name' => 'Fakultas Teknik']);
        $this->auditee = User::factory()->create(['unit_id' => $unit->id]);
        $this->auditee->assignRole('Auditee');

        $periode = Periode::create(['name' => '2026/2027', 'year' => 2026, 'is_active' => true]);
        $standar = Standar::create(['periode_id' => $periode->id, 'code' => 'STD-1', 'name' => 'Pendidikan']);
        $indikator = Indikator::create(['standar_id' => $standar->id, 'description' => 'Target A', 'target' => '100']);
        
        $this->pengukuran = Pengukuran::create([
            'unit_id' => $this->auditee->unit_id,
            'indikator_id' => $indikator->id,
            'status' => 'draft'
        ]);
    }

    public function test_auditee_can_upload_valid_file()
    {
        Storage::fake('local');
        $this->actingAs($this->auditee);
        
        $file = UploadedFile::fake()->create('dokumen_bukti.pdf', 500, 'application/pdf');
        
        $response = $this->put("/pengukuran/{$this->pengukuran->id}", [
            'self_score' => 'Tercapai',
            'bukti_file' => $file
        ]);
        
        $response->assertSessionHasNoErrors();
        $this->assertDatabaseHas('pengukurans', [
            'id' => $this->pengukuran->id,
            'self_score' => 'Tercapai'
        ]);
    }

    public function test_auditee_cannot_upload_invalid_extension()
    {
        $this->actingAs($this->auditee);
        
        $file = UploadedFile::fake()->create('malware.exe', 100, 'application/x-msdownload');
        
        $response = $this->put("/pengukuran/{$this->pengukuran->id}", [
            'self_score' => '100',
            'bukti_file' => $file
        ]);
        
        $response->assertSessionHasErrors('bukti_file');
    }

    public function test_auditee_cannot_upload_file_over_10mb()
    {
        $this->actingAs($this->auditee);
        
        $file = UploadedFile::fake()->create('huge.pdf', 12000, 'application/pdf'); // 12 MB
        
        $response = $this->put("/pengukuran/{$this->pengukuran->id}", [
            'self_score' => '100',
            'bukti_file' => $file
        ]);
        
        $response->assertSessionHasErrors('bukti_file');
    }
}
