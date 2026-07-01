<?php
$user = App\Models\User::firstOrCreate(
    ['email' => 'super@spmi.com'],
    [
        'name' => 'Super User',
        'password' => bcrypt('password'),
        'unit_id' => 1,
    ]
);
$user->syncRoles(['Admin', 'LPM', 'Auditee', 'Auditor', 'Pimpinan']);
echo "Super user created successfully.\n";
exit;
