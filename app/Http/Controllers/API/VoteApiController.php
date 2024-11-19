<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Kecamatan;
use App\Models\Kelurahan;
use App\Models\Tps;
use App\Models\Votes;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Tymon\JWTAuth\Facades\JWTAuth;

class VoteApiController extends Controller
{
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        // Ambil user yang terautentikasi dari JWT
        // $user = JWTAuth::user();

        // Validasi data input
        $validated = $request->validate([
            'kecamatan_id'  => 'required|integer',
            'kelurahan_id'  => 'required|integer',
            'tps_id'        => 'required|integer',
            'paslon_1_vote' => 'required|integer',
            'paslon_2_vote' => 'required|integer',
            'paslon_3_vote' => 'required|integer',
            'foto_c1_plano' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        // Validasi kombinasi unik
        $exists = \App\Models\Vote::where('kecamatan_id', $request->kecamatan_id)
            ->where('kelurahan_id', $request->kelurahan_id)
            ->where('tps_id', $request->tps_id)
            ->exists();

        if ($exists) {

            $kecamatan = Kecamatan::find($request->kecamatan_id)->nama_kecamatan ?? '-';
            $kelurahan = Kelurahan::find($request->kelurahan_id)->nama_kelurahan ?? '-';
            $tps       = Tps::find($request->tps_id)->nama_tps ?? '-';  

            return response()->json([
                'status'  => false,
                'message' => 'Data untuk Kecamatan ' .$kecamatan. ' Gampong ' .$kelurahan. ' TPS00' .$tps. ' sudah ada.',
            ], 422);
        }

        // Proses unggahan file jika ada
        if ($request->hasFile('foto_c1_plano')) {
            $filePath = $request->file('foto_c1_plano')->store('formulir-c1', 'public');
            $validated['foto_c1_plano'] = $filePath;
        }
            
        // Simpan data ke database
        $votes = Votes::create($validated);

        // Kembalikan response dalam format JSON menggunakan VoteApiResource
        return response()->json([
            'status'  => true, 
            'data'    => $votes, 
            'message' => 'Success', 
        ]);
    }


}

