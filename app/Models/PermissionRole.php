<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PermissionRole extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $table = 'permission_roles';
    protected $primaryKey = 'id';
    function permissions()
    {
        return $this->belongsTo(Permission::class, 'permission_id', 'id');
    }
}
