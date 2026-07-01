<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Role::create(['name' => 'Admin']);
        Role::create(['name' => 'Auditee']);
        Role::create(['name' => 'Auditor']);
        Role::create(['name' => 'LPM']);
        Role::create(['name' => 'Pimpinan']);
    }

    public function test_login_with_wrong_password_fails()
    {
        $user = User::factory()->create(['password' => bcrypt('password123')]);
        
        $response = $this->post('/login', [
            'email' => $user->email,
            'password' => 'salah-password',
        ]);
        
        $this->assertGuest();
        $response->assertSessionHasErrors('email');
    }

    public function test_admin_redirected_to_admin_dashboard()
    {
        $admin = User::factory()->create();
        $admin->assignRole('Admin');

        $response = $this->post('/login', [
            'email' => $admin->email,
            'password' => 'password', // Default factory password
        ]);

        $response->assertRedirect('/dashboard/admin');
    }

    public function test_auditee_cannot_access_admin_routes()
    {
        $auditee = User::factory()->create();
        $auditee->assignRole('Auditee');
        
        $this->actingAs($auditee);
        
        $response = $this->get('/users'); // Rute Admin
        $response->assertStatus(403);
    }

    public function test_reset_password_sends_email()
    {
        $user = User::factory()->create();

        $response = $this->post('/forgot-password', [
            'email' => $user->email,
        ]);

        $response->assertSessionHasNoErrors();
        $response->assertSessionHas('status');
    }
}
