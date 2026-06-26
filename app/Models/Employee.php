<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    use HasFactory;

    protected $table = 'karyawan';
    protected $primaryKey = 'id_Karyawan';

    const CREATED_AT = 'cread_at';
    const UPDATED_AT = null;

    protected $fillable = [
        'Nip',
        'Nama',
        'Jenis_Kelamin',
        'Jabatan',
        'id_departemen',
        'id_fingerprint',
        'id_RFID',
        'Tanggal_bergabung',
        'Status',
    ];

    public function department()
    {
        return $this->belongsTo(Department::class, 'id_departemen', 'id_departemen');
    }

    public function attendances()
    {
        return $this->hasMany(Attendance::class, 'id_karyawan', 'id_Karyawan');
    }
}
