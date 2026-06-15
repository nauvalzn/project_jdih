<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ReferensiSeeder extends Seeder
{
    public function run()
    {
        DB::table('jenis_referensi')->insert([
            [
                'id'        => 1,
                'deskripsi' => 'Jenis Dokumen',
                'status'    => 1,
            ],
            [
                'id'        => 2,
                'deskripsi' => 'Status Dokumen',
                'status'    => 1,
            ],
            [
                'id'        => 3,
                'deskripsi' => 'Status Verifikasi',
                'status'    => 1,
            ],
            [
                'id'        => 4,
                'deskripsi' => 'Tipe Dokumen',
                'status'    => 1,
            ],
            [
                'id'        => 5,
                'deskripsi' => 'Bidang Hukum',
                'status'    => 1,
            ],
            [
                'id'        => 6,
                'deskripsi' => 'Jenis Hukum',
                'status'    => 1,
            ],
            
        ]);
    }
}