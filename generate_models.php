<?php

$modelsDir = __DIR__ . '/app/Models';
if (!is_dir($modelsDir)) mkdir($modelsDir, 0755, true);

$traitContent = <<<EOT
<?php
namespace App\Traits;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

trait AutoLogActivity
{
    public static function bootAutoLogActivity()
    {
        static::created(function (\$model) {
            self::recordLog('Created', \$model);
        });
        
        static::updated(function (\$model) {
            self::recordLog('Updated', \$model);
        });
        
        static::deleted(function (\$model) {
            self::recordLog('Deleted', \$model);
        });
    }
    
    protected static function recordLog(\$action, \$model)
    {
        if (Auth::check()) {
            DB::table('log_activities')->insert([
                'user_id' => Auth::id(),
                'action' => \$action . ' ' . class_basename(\$model),
                'description' => \$action . ' record in ' . \$model->getTable() . ' with ID: ' . \$model->getKey(),
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent(),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
EOT;

if (!is_dir(__DIR__ . '/app/Traits')) mkdir(__DIR__ . '/app/Traits', 0755, true);
file_put_contents(__DIR__ . '/app/Traits/AutoLogActivity.php', $traitContent);

$models = [
    'Role' => [
        'base' => 'Spatie\Permission\Models\Role',
        'content' => <<<EOT
    use \App\Traits\AutoLogActivity;
EOT
    ],
    'Unit' => [
        'base' => 'Illuminate\Database\Eloquent\Model',
        'content' => <<<EOT
    use \App\Traits\AutoLogActivity;
    
    protected \$fillable = ['name', 'type'];
    
    public function users() { return \$this->hasMany(User::class); }
    public function pengukurans() { return \$this->hasMany(Pengukuran::class); }
    public function jadwalAudits() { return \$this->hasMany(JadwalAudit::class); }
EOT
    ],
    'Periode' => [
        'base' => 'Illuminate\Database\Eloquent\Model',
        'content' => <<<EOT
    use \App\Traits\AutoLogActivity;
    
    protected \$fillable = ['name', 'year', 'is_active'];
    protected \$casts = ['is_active' => 'boolean'];
    
    public function standars() { return \$this->hasMany(Standar::class); }
    public function jadwalAudits() { return \$this->hasMany(JadwalAudit::class); }
    
    public function scopeAktif(\$query) { return \$query->where('is_active', true); }
EOT
    ],
    'User' => [
        'base' => 'Illuminate\Foundation\Auth\User',
        'content' => <<<EOT
    use \Illuminate\Database\Eloquent\Factories\HasFactory;
    use \Illuminate\Notifications\Notifiable;
    use \Spatie\Permission\Traits\HasRoles;
    use \App\Traits\AutoLogActivity;
    
    protected \$fillable = ['unit_id', 'name', 'email', 'password'];
    protected \$hidden = ['password', 'remember_token'];
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function unit() { return \$this->belongsTo(Unit::class); }
    public function pengukurans() { return \$this->hasMany(Pengukuran::class); }
    public function jadwalAsAuditor() { return \$this->hasMany(JadwalAudit::class, 'auditor_id'); }
EOT
    ],
    'Standar' => [
        'base' => 'Illuminate\Database\Eloquent\Model',
        'content' => <<<EOT
    use \App\Traits\AutoLogActivity;
    
    protected \$fillable = ['periode_id', 'code', 'title', 'description', 'is_active'];
    protected \$casts = ['is_active' => 'boolean'];

    public function periode() { return \$this->belongsTo(Periode::class); }
    public function indikators() { return \$this->hasMany(Indikator::class); }

    public function scopeAktif(\$query) { return \$query->where('is_active', true); }
EOT
    ],
    'Indikator' => [
        'base' => 'Illuminate\Database\Eloquent\Model',
        'content' => <<<EOT
    use \App\Traits\AutoLogActivity;
    
    protected \$fillable = ['standar_id', 'type', 'description', 'target'];

    public function standar() { return \$this->belongsTo(Standar::class); }
    public function pengukurans() { return \$this->hasMany(Pengukuran::class); }
EOT
    ],
    'Pengukuran' => [
        'base' => 'Illuminate\Database\Eloquent\Model',
        'content' => <<<EOT
    use \App\Traits\AutoLogActivity;
    
    protected \$fillable = ['indikator_id', 'unit_id', 'user_id', 'self_score', 'status'];

    public function indikator() { return \$this->belongsTo(Indikator::class); }
    public function unit() { return \$this->belongsTo(Unit::class); }
    public function user() { return \$this->belongsTo(User::class); }
    public function auditAmis() { return \$this->hasMany(AuditAmi::class); }
    public function buktiFisiks() { return \$this->morphMany(FileUpload::class, 'uploadable'); }

    public function scopeSelesai(\$query) { return \$query->where('status', 'submitted'); }
EOT
    ],
    'JadwalAudit' => [
        'base' => 'Illuminate\Database\Eloquent\Model',
        'content' => <<<EOT
    use \App\Traits\AutoLogActivity;
    
    protected \$fillable = ['periode_id', 'unit_id', 'auditor_id', 'date_start', 'date_end', 'status'];
    protected function casts(): array
    {
        return [
            'date_start' => 'datetime',
            'date_end' => 'datetime'
        ];
    }

    public function periode() { return \$this->belongsTo(Periode::class); }
    public function unit() { return \$this->belongsTo(Unit::class); }
    public function auditor() { return \$this->belongsTo(User::class, 'auditor_id'); }
    public function auditAmis() { return \$this->hasMany(AuditAmi::class); }
EOT
    ],
    'AuditAmi' => [
        'base' => 'Illuminate\Database\Eloquent\Model',
        'content' => <<<EOT
    use \App\Traits\AutoLogActivity;
    
    protected \$fillable = ['jadwal_audit_id', 'pengukuran_id', 'auditor_score', 'finding_type', 'description'];

    public function jadwal() { return \$this->belongsTo(JadwalAudit::class, 'jadwal_audit_id'); }
    public function pengukuran() { return \$this->belongsTo(Pengukuran::class); }
    public function rtls() { return \$this->hasMany(RtmRtl::class); }
    public function komentars() { return \$this->hasMany(KomentarAmi::class); }
EOT
    ],
    'RtmRtl' => [
        'base' => 'Illuminate\Database\Eloquent\Model',
        'content' => <<<EOT
    use \App\Traits\AutoLogActivity;
    
    protected \$fillable = ['audit_ami_id', 'auditee_id', 'description', 'target_date', 'status', 'auditor_validation'];
    protected function casts(): array
    {
        return [
            'target_date' => 'datetime',
            'auditor_validation' => 'boolean'
        ];
    }

    public function auditAmi() { return \$this->belongsTo(AuditAmi::class); }
    public function auditee() { return \$this->belongsTo(User::class, 'auditee_id'); }
    public function lampirans() { return \$this->morphMany(FileUpload::class, 'uploadable'); }
EOT
    ],
    'Notifikasi' => [
        'base' => 'Illuminate\Database\Eloquent\Model',
        'content' => <<<EOT
    use \App\Traits\AutoLogActivity;
    
    protected \$fillable = ['user_id', 'title', 'message', 'is_read'];
    protected \$casts = ['is_read' => 'boolean'];

    public function user() { return \$this->belongsTo(User::class); }

    public function scopeUnread(\$query) { return \$query->where('is_read', false); }
EOT
    ],
    'LogActivity' => [
        'base' => 'Illuminate\Database\Eloquent\Model',
        'content' => <<<EOT
    // Skip AutoLogActivity here to prevent infinite loop!
    protected \$table = 'log_activities';
    
    protected \$fillable = ['user_id', 'action', 'description', 'ip_address', 'user_agent'];

    public function user() { return \$this->belongsTo(User::class); }
EOT
    ],
    'PasswordReset' => [
        'base' => 'Illuminate\Database\Eloquent\Model',
        'content' => <<<EOT
    use \App\Traits\AutoLogActivity;
    
    protected \$table = 'password_reset_tokens';
    protected \$primaryKey = 'email';
    public \$incrementing = false;
    protected \$keyType = 'string';
    public \$timestamps = false;
    
    protected \$fillable = ['email', 'token', 'created_at'];
    protected \$casts = ['created_at' => 'datetime'];
EOT
    ],
    'FileUpload' => [
        'base' => 'Illuminate\Database\Eloquent\Model',
        'content' => <<<EOT
    use \App\Traits\AutoLogActivity;
    
    protected \$fillable = ['uploadable_id', 'uploadable_type', 'file_name', 'file_path', 'file_size'];

    public function uploadable() { return \$this->morphTo(); }
EOT
    ],
    'KomentarAmi' => [
        'base' => 'Illuminate\Database\Eloquent\Model',
        'content' => <<<EOT
    use \App\Traits\AutoLogActivity;
    
    protected \$table = 'komentar_amis';
    protected \$fillable = ['audit_ami_id', 'user_id', 'comment'];

    public function auditAmi() { return \$this->belongsTo(AuditAmi::class); }
    public function user() { return \$this->belongsTo(User::class); }
EOT
    ]
];

$backslash = chr(92);
foreach(\$models as \$name => \$config) {
    \$base = \$config['base'];
    \$c = \$config['content'];
    
    \$content = "<?php\n\nnamespace App\Models;\n\nclass {\$name} extends {$backslash}{\$base}\n{\n{\$c}\n}\n";
    file_put_contents(\$modelsDir . '/' . \$name . '.php', \$content);
}

echo "15 Models successfully created!\n";
