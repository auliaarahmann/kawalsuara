<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Kecamatan;
use App\Models\Kelurahan;
use App\Models\Tps;

class DependentSelectApiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    // Mendapatkan daftar kecamatan
    public function getKecamatan()
    {
        $kecamatan = Kecamatan::all(['id', 'nama_kecamatan']);
        return response()->json($kecamatan);
    }

    // Mendapatkan daftar kelurahan berdasarkan kecamatan
    public function getKelurahan($kecamatanId)
    {
        $kelurahan = Kelurahan::where('kecamatan_id', $kecamatanId)
            ->get(['id', 'kecamatan_id', 'nama_kelurahan']); 

        return response()->json($kelurahan);
    }


    // Mendapatkan daftar TPS berdasarkan kelurahan
    public function getTps($kelurahanId)
    {
        $tps = Tps::where('kelurahan_id', $kelurahanId)
            ->get(['id', 'kelurahan_id', 'nama_tps'])
            ->map(function ($item) {
                $item->nama_tps = 'TPS00' . $item->nama_tps; // Tambahkan prefix TPS00
                return $item;
            });
    
        return response()->json($tps);
    }
    
}
