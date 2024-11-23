<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\DB;

class FromJsonSeeder extends Seeder
{
    public function run()
    {
        // Path ke file JSON
        $kecamatansPath = public_path('dataset/kecamatans.json');
        $kelurahansPath = public_path('dataset/kelurahans.json');
        $tpsPath        = public_path('dataset/tps.json');

        // Membaca file JSON
        $kecamatansData = json_decode(File::get($kecamatansPath), true);
        $kelurahansData = json_decode(File::get($kelurahansPath), true);
        $tpsData        = json_decode(File::get($tpsPath), true);

        // Memasukkan data ke tabel kecamatans
        foreach ($kecamatansData as $kecamatan) {
            DB::table('kecamatans')->insert([
                'id'             => $kecamatan['id'],
                'nama_kecamatan' => $kecamatan['name'],
            ]);
        }

        // Memasukkan data ke tabel kelurahans
        foreach ($kelurahansData as $kelurahan) {
            DB::table('kelurahans')->insert([
                'id'             => $kelurahan['id'],
                'nama_kelurahan' => $kelurahan['nama_kelurahan'],
                'kecamatan_id'   => $kelurahan['kecamatan_id'],
            ]);
        }

        // Memasukkan data ke tabel Tps
        foreach ($tpsData as $tps) {
            DB::table('tps')->insert([
                'id'            => $tps['id'],
                'kecamatan_id'  => $tps['kecamatan_id'],
                'kelurahan_id'  => $tps['kelurahan_id'],
                'nama_tps'      => $tps['nama_tps'],
            ]);
        }
    }
}
