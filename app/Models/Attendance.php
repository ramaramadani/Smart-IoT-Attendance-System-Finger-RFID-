<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Attendance extends Model
{
    use HasFactory;

    protected $table = 'absensi';
    protected $primaryKey = 'id_absensi';

    const CREATED_AT = 'cread_at';
    const UPDATED_AT = null;

    protected $fillable = [
        'id_karyawan',
        'Tanggal',
        'Jam_masuk',
        'Jam_keluar',
        'Durasi',
        'Status',
        'Keterangan',
    ];

    protected $casts = [
        'Tanggal' => 'date',
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class, 'id_karyawan', 'id_Karyawan');
    }

    public function getLateMinutesAttribute()
    {
        if (!$this->Jam_masuk) {
            return 0;
        }

        // Handle string/time formatting safely
        try {
            $checkIn = Carbon::createFromFormat('H:i:s', $this->Jam_masuk);
        } catch (\Exception $e) {
            try {
                $checkIn = Carbon::parse($this->Jam_masuk);
            } catch (\Exception $ex) {
                return 0;
            }
        }
        
        $shiftStart = Carbon::createFromFormat('H:i:s', '07:00:00');

        if ($checkIn->greaterThan($shiftStart)) {
            return $checkIn->diffInMinutes($shiftStart);
        }

        return 0;
    }
}
