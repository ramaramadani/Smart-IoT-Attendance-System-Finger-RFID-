<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    use HasFactory;

    protected $table = 'departemen';
    protected $primaryKey = 'id_departemen';
    
    const CREATED_AT = 'cread_at';
    const UPDATED_AT = null;

    protected $fillable = [
        'Nama_departemen',
        'Keterangan',
    ];

    public function employees()
    {
        return $this->hasMany(Employee::class, 'id_departemen', 'id_departemen');
    }
}
