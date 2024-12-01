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
use Illuminate\Support\Facades\Storage;

class VoteApiController extends Controller
{
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            // Validasi data input
            $validated = $request->validate([
                'kecamatan_id'  => 'required|integer|exists:kecamatans,id',
                'kelurahan_id'  => 'required|integer|exists:kelurahans,id',
                'tps_id'        => 'required|integer|exists:tps,id',
                'paslon_1_vote' => 'required|integer|min:0',
                'paslon_2_vote' => 'required|integer|min:0',
                'paslon_3_vote' => 'required|integer|min:0',
                'foto_c1_plano' => 'required|image|mimes:jpeg,png,jpg|max:7168',
            ]);

            // Validasi kombinasi unik
            $exists = Votes::where('kecamatan_id', $request->kecamatan_id)
                ->where('kelurahan_id', $request->kelurahan_id)
                ->where('tps_id', $request->tps_id)
                ->exists();

            if ($exists) {
                $kecamatan = Kecamatan::find($request->kecamatan_id)->nama_kecamatan ?? '-';
                $kelurahan = Kelurahan::find($request->kelurahan_id)->nama_kelurahan ?? '-';
                $tps       = Tps::find($request->tps_id)->nama_tps ?? '-';

                return response()->json([
                    'status'  => false,
                    'message' => "Data untuk Kecamatan $kecamatan, Gampong $kelurahan, TPS00$tps sudah ada.",
                ], 422);
            }

            // Ambil ID user yang terautentikasi
            $user = JWTAuth::parseToken()->authenticate();
            if (!$user) {
                return response()->json([
                    'status'  => false,
                    'message' => 'User tidak terautentikasi.',
                ], 401);
            }

            // Proses upload foto
            if ($request->hasFile('foto_c1_plano')) {
                $filePath = $request->file('foto_c1_plano')->store('formulir-c1', 'public');
                $validated['foto_c1_plano'] = $filePath;
            }

            // Set created_by menjadi user yang terautentikasi
            $validated['created_by'] = $user->id;

            // Simpan data ke database
            $votes = Votes::create($validated);

            // Kembalikan response sukses
            return response()->json([
                'status'  => true,
                'data'    => $votes,
                'message' => 'Data berhasil dikirim.',
            ], 201);
        } catch (\Exception $e) {
            // Log error untuk debugging
            Log::error('Error storing vote data: ' . $e->getMessage());

            // Kembalikan response error
            return response()->json([
                'status'  => false,
                'message' => 'Terjadi kesalahan saat mengirim data.',
                'error'   => $e->getMessage(),
            ], 500);
        }
    }
}
