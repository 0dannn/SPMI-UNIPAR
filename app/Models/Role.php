<?php

namespace App\Models;

use Spatie\Permission\Models\Role as SpatieRole;
use App\Traits\AutoLogActivity;

class Role extends SpatieRole
{
    use AutoLogActivity;
}
