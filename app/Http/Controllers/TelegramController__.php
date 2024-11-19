<?php

namespace App\Http\Controllers;

use App\Models\Kecamatan;
use App\Models\Kelurahan;
use App\Models\Tps;
use App\Models\Vote;
use Illuminate\Http\Request;
use Telegram\Bot\Api;
use Illuminate\Support\Facades\Log;

class TelegramController extends Controller
{
    protected $telegram;

    public function __construct(Api $telegram)
    {
        $this->telegram = $telegram;
    }

    public function handleWebhook(Request $request)
    {
        try {
            $update = $this->telegram->getWebhookUpdate();
            $message = $update->getMessage();

            // Handle potential error when message is null
            if (is_null($message)) {
                Log::warning("Received an update without a message: " . json_encode($update));
                return response()->noContent(); // Return a 204 No Content response
            }

            $chatId = $message->getChat()->getId();
            $text = $message->getText();

            Log::info("Incoming message data: " . json_encode($update));

            $userState = $this->getUserState($chatId);

            if (strpos($text, '/start') === 0) {
                $this->sendKecamatanList($chatId);
                $this->updateUserState($chatId, 'select_kecamatan');
            } else {
                $this->handleTextMessage($chatId, $text);
            }
        } catch (\Exception $e) {
            Log::error("Error processing Telegram webhook: " . $e->getMessage());
            // Optionally, send error message to admin or log error to monitoring service
        }
    }

    private function handleTextMessage($chatId, $text)
    {
        $userState = $this->getUserState($chatId);
        Log::info("User state for $chatId: " . $userState); // Log user state
    
        try {
            if (is_numeric($text)) {
                $selectedId = (int)$text;
    
                switch ($userState) {
                    case 'select_kecamatan':
                        $kecamatan = Kecamatan::find($selectedId);
                        if ($kecamatan) {
                            $this->sendKelurahanList($chatId, $selectedId);
                            $this->updateUserState($chatId, 'select_kelurahan');
                            // Store selected kecamatan ID in user session
                            session(["selected_kecamatan_$chatId" => $selectedId]);
                        } else {
                            $this->telegram->sendMessage([
                                'chat_id' => $chatId,
                                'text' => "ID Kecamatan tidak valid. Silakan pilih ID dari daftar."
                            ]);
                        }
                        break;
    
                    case 'select_kelurahan':
                        $kelurahan = Kelurahan::find($selectedId);
                        if ($kelurahan) {
                            $this->sendTpsList($chatId, $selectedId);
                            $this->updateUserState($chatId, 'select_tps');
                            // Store selected kelurahan ID in user session
                            session(["selected_kelurahan_$chatId" => $selectedId]);
                        } else {
                            $this->telegram->sendMessage([
                                'chat_id' => $chatId,
                                'text' => "ID Kelurahan tidak valid. Silakan pilih ID dari daftar."
                            ]);
                        }
                        break;
    
                    case 'select_tps':
                        $tps = Tps::find($selectedId);
                        if ($tps) {
                            $this->telegram->sendMessage([
                                'chat_id' => $chatId,
                                'text' => "Silakan kirim perolehan suara untuk TPS {$tps->nama_tps} (format: 100#150#50)"
                            ]);
                            $this->updateUserState($chatId, 'select_vote');
                            // Store selected TPS ID in user session
                            session(["selected_tps_$chatId" => $selectedId]);
                        } else {
                            $this->telegram->sendMessage([
                                'chat_id' => $chatId,
                                'text' => "ID TPS tidak valid. Silakan pilih ID dari daftar."
                            ]);
                        }
                        break;
    
                    case 'select_vote':
                        $this->handleVote($chatId, $text, session("selected_tps_$chatId")); 
                        break;
    
                    default:
                        $this->telegram->sendMessage([
                            'chat_id' => $chatId,
                            'text' => "Perintah tidak valid."
                        ]);
                        break;
                }
            } else {
                $this->telegram->sendMessage([
                    'chat_id' => $chatId,
                    'text' => "Silakan kirim ID dari daftar pilihan."
                ]);
            }
        } catch (\Exception $e) {
            Log::error("Error handling text message: " . $e->getMessage());
            $this->telegram->sendMessage([
                'chat_id' => $chatId,
                'text' => "Terjadi kesalahan. Silakan coba lagi."
            ]);
        }
    }
    

    private function sendKecamatanList($chatId)
    {
        $kecamatanList = Kecamatan::all();

        $text = "Silahkan pilih kecamatan:\n";
        foreach ($kecamatanList as $kecamatan) {
            $text .= "{$kecamatan->id}. {$kecamatan->nama_kecamatan}\n";
        }

        $this->telegram->sendMessage([
            'chat_id' => $chatId,
            'text' => $text
        ]);
    }

    private function sendKelurahanList($chatId, $kecamatanId)
    {
        $kelurahanList = Kelurahan::where('kecamatan_id', $kecamatanId)->get();

        $text = "Silahkan pilih kelurahan:\n";
        foreach ($kelurahanList as $kelurahan) {
            $text .= "{$kelurahan->id}. {$kelurahan->nama_kelurahan}\n";
        }

        $this->telegram->sendMessage([
            'chat_id' => $chatId,
            'text' => $text
        ]);
    }

    private function sendTpsList($chatId, $kelurahanId)
    {
        $tpsList = Tps::where('kelurahan_id', $kelurahanId)->get();

        $text = "Silahkan pilih TPS:\n";
        foreach ($tpsList as $tps) {
            $text .= "{$tps->id}. TPS {$tps->nama_tps}\n";
        }

        $this->telegram->sendMessage([
            'chat_id' => $chatId,
            'text' => $text
        ]);
    }

    private function handleVote($chatId, $text, $tpsId)
    {
        $votes = explode('#', $text);
        if (count($votes) == 3) {
            try {
                $tps = Tps::find($tpsId);
                if (!$tps) {
                    throw new \Exception("TPS not found");
                }
                $kelurahan = Kelurahan::find($tps->kelurahan_id);
                $kecamatan = Kecamatan::find($kelurahan->kecamatan_id);

                $vote = new Vote();
                $vote->tps_id = $tpsId; 
                $vote->paslon_1_vote = $votes[0];
                $vote->paslon_2_vote = $votes[1];
                $vote->paslon_3_vote = $votes[2];
                $vote->user_id = $chatId;
                $vote->save();

                $this->telegram->sendMessage([
                    'chat_id' => $chatId,
                    'text' => "Terima kasih, suara Anda untuk TPS " . $tps->nama_tps . " di Kelurahan " . $kelurahan->nama_kelurahan . ", Kecamatan " . $kecamatan->nama_kecamatan . " telah tercatat."
                ]);

                $this->updateUserState($chatId, 'complete');
            } catch (\Exception $e) {
                Log::error("Error saving vote data: " . $e->getMessage());
                $this->telegram->sendMessage([
                    'chat_id' => $chatId,
                    'text' => "Terjadi kesalahan menyimpan data. Silakan coba lagi."
                ]);
            }
        } else {
            $this->telegram->sendMessage([
                'chat_id' => $chatId,
                'text' => "Format suara salah. Harap kirim dengan format: 100#150#50"
            ]);
        }
    }

    private function getUserState($chatId)
    {
        return session("user_state_$chatId"); 
    }
    
    private function updateUserState($chatId, $state)
    {
        session(["user_state_$chatId" => $state]);
        Log::info("User $chatId state updated to: $state");
    }
}
