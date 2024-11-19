<?php

namespace App\Http\Controllers;

use App\Events\VotesUpdated;
use Illuminate\Http\Request;
use Telegram\Bot\Laravel\Facades\Telegram;
use App\Models\Votes;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

/*
 Text Only
*/
// class TelegramController extends Controller
// {
//     public function handleTelegramWebhook(Request $request)
//     {
//         $text = $request->message['text'];
//         $chat_id = $request->message['chat']['id'];
        
//         // if (preg_match('/^Real Count (\w+) (\d+) (\d+)$/i', $text, $matches)) { //real count tps001 100 100
//         // if (preg_match('/^(\w+)\s+(\d+)\s+(\d+)$/i', $text, $matches)) { //tps001 100 100
//         if (preg_match('/^(\w+)\s+(\d+)\s+(\d+)\s+(\d+)$/i', $text, $matches)) { //tps001 100 100 100
//             $tps_id = strtoupper($matches[1]);
//             $paslon1_votes = $matches[2];
//             $paslon2_votes = $matches[3];
//             $paslon3_votes = $matches[4];

//             // Cek apakah data TPS sudah ada
//             $existingResult = Votes::where('tps_id', $tps_id)->first();
//             if ($existingResult) {
//                 // Jika sudah ada, kirim pesan balik
//                 Telegram::sendMessage([
//                     'chat_id' => $chat_id,
//                     'text' => "Data untuk {$tps_id} sudah ada."
//                 ]);
//             } else {
//                 // Simpan data baru ke database
//                 Votes::create([
//                     'tps_id' => $tps_id,
//                     'paslon1_votes' => $paslon1_votes,
//                     'paslon2_votes' => $paslon2_votes,
//                     'paslon3_votes' => $paslon3_votes
//                 ]);

                
//                 // Kirim pesan sukses
//                 Telegram::sendMessage([
//                     'chat_id' => $chat_id,
//                     'text' => "Data berhasil dikirim untuk $tps_id.\nPaslon 01 : $paslon1_votes suara.\nPaslon 02 : $paslon2_votes suara.\nPaslon 03 : $paslon3_votes suara. "
//                 ]);

//                 // Ambil data total untuk semua paslon setelah diupdate
//                 $totalVotes = Votes::selectRaw('SUM(paslon1_votes) as paslon1, SUM(paslon2_votes) as paslon2, SUM(paslon3_votes) as paslon3')
//                 ->first();

//                  // Broadcast event untuk memperbarui chart secara real-time
//                 event(new VotesUpdated($totalVotes->paslon1, $totalVotes->paslon2, $totalVotes->paslon3));
                
//             }
//         } else {
//             // Format pesan tidak sesuai
//             Telegram::sendMessage([
//                 'chat_id' => $chat_id,
//                 'text' => "Format tidak valid. Gunakan format:\nTPSxxx SUARA1 SUARA2 SUARA3\nContoh: TPS001 100 100 100"
//             ]);
//         }

//     }
// }

/*
 Text & Foto
*/
// class TelegramController extends Controller
// {
//     public function handleTelegramWebhook(Request $request)
//     {
//         $data = $request->all();  // Ambil semua data dari request

//         // Cek apakah ada key 'message'
//         if (!isset($data['message'])) {
//             return response()->json(['status' => 'ignored'], 200);  // Jika tidak ada pesan, abaikan
//         }

//         $message = $data['message'];
//         $chat_id = $message['chat']['id'];  // Ambil chat_id

//         // Tentukan apakah pesan berupa teks atau foto dengan caption
//         $text = $message['text'] ?? $message['caption'] ?? null;

//         if ($text) {
//             // Validasi format teks menggunakan regex
//             if (preg_match('/^(\w+)\s+(\d+)\s+(\d+)\s+(\d+)$/i', $text, $matches)) {
//                 $tps_id = strtoupper($matches[1]);
//                 $paslon1_votes = $matches[2];
//                 $paslon2_votes = $matches[3];
//                 $paslon3_votes = $matches[4];

//                 // Cek apakah data TPS sudah ada di database
//                 $existingResult = Votes::where('tps_id', $tps_id)->first();
//                 if ($existingResult) {
//                     // Kirim pesan jika data TPS sudah ada
//                     Telegram::sendMessage([
//                         'chat_id' => $chat_id,
//                         'text' => "Data untuk {$tps_id} sudah ada."
//                     ]);
//                 } else {
//                     // Simpan data baru ke database
//                     Votes::create([
//                         'tps_id' => $tps_id,
//                         'paslon1_votes' => $paslon1_votes,
//                         'paslon2_votes' => $paslon2_votes,
//                         'paslon3_votes' => $paslon3_votes
//                     ]);

//                     // Kirim pesan sukses
//                     Telegram::sendMessage([
//                         'chat_id' => $chat_id,
//                         'text' => "Data berhasil dikirim untuk $tps_id.\nPaslon 01: $paslon1_votes suara.\nPaslon 02: $paslon2_votes suara.\nPaslon 03: $paslon3_votes suara."
//                     ]);

//                     // Hitung total suara setelah update
//                     $totalVotes = Votes::selectRaw(
//                         'SUM(paslon1_votes) as paslon1, 
//                          SUM(paslon2_votes) as paslon2, 
//                          SUM(paslon3_votes) as paslon3'
//                     )->first();

//                     // Broadcast event untuk memperbarui chart secara real-time
//                     event(new VotesUpdated(
//                         $totalVotes->paslon1, 
//                         $totalVotes->paslon2, 
//                         $totalVotes->paslon3
//                     ));
//                 }
//             } else {
//                 // Kirim pesan jika format tidak valid
//                 Telegram::sendMessage([
//                     'chat_id' => $chat_id,
//                     'text' => "Format tidak valid. Gunakan format:\nTPSxxx SUARA1 SUARA2 SUARA3\nContoh: TPS001 100 100 100"
//                 ]);
//             }
//         } else {
//             // Jika tidak ada teks atau caption, abaikan pesan
//             Telegram::sendMessage([
//                 'chat_id' => $chat_id,
//                 'text' => 
//                 "Foto anda tidak dikenali, pastikan anda mengambil foto dengan jelas\n(terdapat No. TPS dan angka hasil perolehan suara)."
//             ]);
//         }

//         return response()->json(['status' => 'success'], 200);
//     }
// }

/*
 Tesseract windows on local server
*/
// class TelegramController extends Controller
// {
//     public function handleTelegramWebhook(Request $request)
//     {
//         $data = $request->all();

//         if (!isset($data['message'])) {
//             return response()->json(['status' => 'ignored'], 200);
//         }

//         $message = $data['message'];
//         $chat_id = $message['chat']['id'];

//         // Jika pesan berisi foto
//         if (isset($message['photo'])) {
//             // Ambil foto dengan resolusi tertinggi
//             $file_id = end($message['photo'])['file_id'];
            
//             // Log::info("Mendapatkan file_id: {$file_id}");
        
//             // Ambil URL file dari Telegram API
//             $file_url = $this->getFileUrl($file_id);
        
//             if ($file_url) {
//                 // Log::info("File URL berhasil didapat: {$file_url}");
        
//                 // Unduh foto dan simpan sementara
//                 $local_path = storage_path('app/public/temp_image.jpg');
//                 $image_content = file_get_contents($file_url);
        
//                 if ($image_content) {
//                     file_put_contents($local_path, $image_content);
//                     // Log::info("Gambar berhasil diunduh dan disimpan di: {$local_path}");
        
//                     // Jalankan Tesseract OCR untuk ekstraksi teks
//                     $extracted_text = $this->extractTextFromImage($local_path);
//                     // Log::info("Teks yang diekstrak: {$extracted_text}");
        
//                     // Proses teks untuk mendapatkan data TPS dan suara
//                     $this->processExtractedText($chat_id, $extracted_text);
//                     return response()->json(['status' => 'success'], 200);
//                 } else {
//                     Log::error("Gagal mengunduh gambar dari URL: {$file_url}");
//                 }
//             } else {
//                 Log::error("Gagal mendapatkan URL file untuk file_id: {$file_id}");
//             }
//         } else {
//             Telegram::sendMessage([
//                 'chat_id' => $chat_id,
//                 'text' => "Kirim foto formulir untuk diproses."
//             ]);
//         }
        
//     }

//     // Fungsi untuk mendapatkan URL file Telegram
//     private function getFileUrl($file_id)
//     {
//         $response = Http::get("https://api.telegram.org/bot" . env('TELEGRAM_BOT_TOKEN') . "/getFile?file_id={$file_id}");

//         if ($response->ok()) {
//             $file_path = $response->json()['result']['file_path'];
    
//             // Log file_path
//             // Log::info("File path: {$file_path}");
            
//             // Buat URL lengkap
//             $file_url = "https://api.telegram.org/file/bot" . env('TELEGRAM_BOT_TOKEN') . "/{$file_path}";
            
//             // Log file_url
//             // Log::info("File URL: {$file_url}");
            
//             return $file_url;
//         } else {
//             Log::error("Gagal mendapatkan file dari Telegram: " . $response->body());
//             return null;
//         }
//     }

//     // Fungsi untuk menjalankan Tesseract OCR
//     private function extractTextFromImage($imagePath)
//     {
//         $tesseractPath = '"C:/Program Files/Tesseract-OCR/tesseract.exe"'; // Path absolut
//         $output = [];
//         $return_var = 0;
    
//         exec("{$tesseractPath} \"{$imagePath}\" stdout", $output, $return_var);
    
//         $outputString = implode("\n", $output);
    
//         if ($return_var !== 0) {
//             Log::error("Tesseract gagal dengan kode return {$return_var}: {$outputString}");
//         } else {
//             // Log::info("Teks yang diekstrak: {$outputString}");
//             return $outputString;
//         }
    
//     }
    

//     // Fungsi untuk memproses teks yang diekstrak
//     private function processExtractedText($chat_id, $text)
//     {
//         Log::info("Teks yang diterima: {$text}");
    
//         // Perbaikan regex agar lebih toleran terhadap variasi format
//         preg_match('/TPS\s*:\s*(\d+)/i', $text, $tpsMatches);
//         preg_match('/Nomor\s+Urut\s+01[\s.:]*\s*(\d+)/i', $text, $paslon1Matches);
//         preg_match('/Nomor\s+Urut\s+0*2[\s.:]*\s*(\d+)/i', $text, $paslon2Matches);
//         preg_match('/Nomor\s+Urut\s+0*3[\s.:]*\s*(\d+)/i', $text, $paslon3Matches);
    
//         // Logging hasil regex untuk debugging
//         Log::info("TPS: " . json_encode($tpsMatches));
//         Log::info("Paslon 1: " . json_encode($paslon1Matches));
//         Log::info("Paslon 2: " . json_encode($paslon2Matches));
//         Log::info("Paslon 3: " . json_encode($paslon3Matches));
    
//         if ($tpsMatches && $paslon1Matches && $paslon2Matches && $paslon3Matches) {
//             $tps_id = 'TPS' . str_pad($tpsMatches[1], 3, '0', STR_PAD_LEFT);
//             $paslon1_votes = $paslon1Matches[1];
//             $paslon2_votes = $paslon2Matches[1];
//             $paslon3_votes = $paslon3Matches[1];
    
//             $existingResult = Votes::where('tps_id', $tps_id)->first();
//             if ($existingResult) {
//                 Telegram::sendMessage([
//                     'chat_id' => $chat_id,
//                     'text' => "Data untuk {$tps_id} sudah ada."
//                 ]);
//             } else {
//                 Votes::create([
//                     'tps_id' => $tps_id,
//                     'paslon1_votes' => $paslon1_votes,
//                     'paslon2_votes' => $paslon2_votes,
//                     'paslon3_votes' => $paslon3_votes
//                 ]);
    
//                 Telegram::sendMessage([
//                     'chat_id' => $chat_id,
//                     'text' => "Data berhasil dikirim untuk {$tps_id}.\n"
//                             . "Paslon 01: {$paslon1_votes} suara.\n"
//                             . "Paslon 02: {$paslon2_votes} suara.\n"
//                             . "Paslon 03: {$paslon3_votes} suara."
//                 ]);
    
//                 $totalVotes = Votes::selectRaw('SUM(paslon1_votes) as paslon1, SUM(paslon2_votes) as paslon2, SUM(paslon3_votes) as paslon3')
//                     ->first();
//                 event(new VotesUpdated($totalVotes->paslon1, $totalVotes->paslon2, $totalVotes->paslon3));
//             }
//         } else {
//             Telegram::sendMessage([
//                 'chat_id' => $chat_id,
//                 'text' => "Format teks tidak valid. Pastikan foto memuat nomor TPS dan suara paslon."
//             ]);
//         }
//     }
    
    
    

    
// }

/*
 Tesseract ubuntu on vps AWS
*/
class TelegramController extends Controller
{
    public function handleTelegramWebhook(Request $request)
    {
        $data = $request->all();

        if (!isset($data['message'])) {
            return response()->json(['status' => 'ignored'], 200);
        }

        $message = $data['message'];
        $chat_id = $message['chat']['id'];

        // Jika pesan berisi foto
        if (isset($message['photo'])) {
            // Ambil foto dengan resolusi tertinggi
            $file_id = end($message['photo'])['file_id'];
            
            // Log::info("Mendapatkan file_id: {$file_id}");
        
            // Ambil URL file dari Telegram API
            $file_url = $this->getFileUrl($file_id);
        
            if ($file_url) {
                // Log::info("File URL berhasil didapat: {$file_url}");
        
                // Unduh foto dan simpan sementara
                $local_path = storage_path('app/public/temp_image.jpg');
                $image_content = file_get_contents($file_url);
        
                if ($image_content) {
                    file_put_contents($local_path, $image_content);
                    // Log::info("Gambar berhasil diunduh dan disimpan di: {$local_path}");
        
                    // Jalankan Tesseract OCR untuk ekstraksi teks
                    $extracted_text = $this->extractTextFromImage($local_path);
                    // Log::info("Teks yang diekstrak: {$extracted_text}");
        
                    // Proses teks untuk mendapatkan data TPS dan suara
                    $this->processExtractedText($chat_id, $extracted_text);
                    return response()->json(['status' => 'success'], 200);
                } else {
                    Log::error("Gagal mengunduh gambar dari URL: {$file_url}");
                }
            } else {
                Log::error("Gagal mendapatkan URL file untuk file_id: {$file_id}");
            }
        } else {
            Telegram::sendMessage([
                'chat_id' => $chat_id,
                'text' => "Kirim foto formulir untuk diproses."
            ]);
        }
        
    }

    // Fungsi untuk mendapatkan URL file Telegram
    private function getFileUrl($file_id)
    {
        $response = Http::get("https://api.telegram.org/bot" . env('TELEGRAM_BOT_TOKEN') . "/getFile?file_id={$file_id}");

        if ($response->ok()) {
            $file_path = $response->json()['result']['file_path'];
    
            // Log file_path
            // Log::info("File path: {$file_path}");
            
            // Buat URL lengkap
            $file_url = "https://api.telegram.org/file/bot" . env('TELEGRAM_BOT_TOKEN') . "/{$file_path}";
            
            // Log file_url
            // Log::info("File URL: {$file_url}");
            
            return $file_url;
        } else {
            Log::error("Gagal mendapatkan file dari Telegram: " . $response->body());
            return null;
        }
    }

    // Fungsi untuk menjalankan Tesseract OCR
    private function extractTextFromImage($imagePath)
    {
        $output = [];
        $return_var = 0;

        // Jalankan Tesseract di Ubuntu (tanpa path absolut)
        exec("tesseract \"{$imagePath}\" stdout", $output, $return_var);

        $outputString = implode("\n", $output);

        if ($return_var !== 0) {
            Log::error("Tesseract gagal dengan kode return {$return_var}: {$outputString}");
            return null;
        } else {
            return $outputString;
        }
    }

    

    // Fungsi untuk memproses teks yang diekstrak
    private function processExtractedText($chat_id, $text)
    {
        Log::info("Teks yang diterima: {$text}");
    
        // Perbaikan regex agar lebih toleran terhadap variasi format
        preg_match('/TPS\s*:\s*(\d+)/i', $text, $tpsMatches);
        preg_match('/Nomor\s+Urut\s+01[\s.:]*\s*(\d+)/i', $text, $paslon1Matches);
        preg_match('/Nomor\s+Urut\s+0*2[\s.:]*\s*(\d+)/i', $text, $paslon2Matches);
        preg_match('/Nomor\s+Urut\s+0*3[\s.:]*\s*(\d+)/i', $text, $paslon3Matches);
    
        // Logging hasil regex untuk debugging
        Log::info("TPS: " . json_encode($tpsMatches));
        Log::info("Paslon 1: " . json_encode($paslon1Matches));
        Log::info("Paslon 2: " . json_encode($paslon2Matches));
        Log::info("Paslon 3: " . json_encode($paslon3Matches));
    
        if ($tpsMatches && $paslon1Matches && $paslon2Matches && $paslon3Matches) {
            $tps_id = 'TPS' . str_pad($tpsMatches[1], 3, '0', STR_PAD_LEFT);
            $paslon1_votes = $paslon1Matches[1];
            $paslon2_votes = $paslon2Matches[1];
            $paslon3_votes = $paslon3Matches[1];
    
            $existingResult = Votes::where('tps_id', $tps_id)->first();
            if ($existingResult) {
                Telegram::sendMessage([
                    'chat_id' => $chat_id,
                    'text' => "Data untuk {$tps_id} sudah ada."
                ]);
            } else {
                Votes::create([
                    'tps_id' => $tps_id,
                    'paslon1_votes' => $paslon1_votes,
                    'paslon2_votes' => $paslon2_votes,
                    'paslon3_votes' => $paslon3_votes
                ]);
    
                Telegram::sendMessage([
                    'chat_id' => $chat_id,
                    'text' => "Data berhasil dikirim untuk {$tps_id}.\n"
                            . "Paslon 01: {$paslon1_votes} suara.\n"
                            . "Paslon 02: {$paslon2_votes} suara.\n"
                            . "Paslon 03: {$paslon3_votes} suara."
                ]);
    
                $totalVotes = Votes::selectRaw('SUM(paslon1_votes) as paslon1, SUM(paslon2_votes) as paslon2, SUM(paslon3_votes) as paslon3')
                    ->first();
                event(new VotesUpdated($totalVotes->paslon1, $totalVotes->paslon2, $totalVotes->paslon3));
            }
        } else {
            Telegram::sendMessage([
                'chat_id' => $chat_id,
                'text' => "Format teks tidak valid. Pastikan foto memuat nomor TPS dan suara paslon."
            ]);
        }
    }
    
    
    

    
}