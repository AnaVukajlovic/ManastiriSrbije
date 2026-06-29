<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Monastery;
use App\Models\Ktitor;

class KustosController extends Controller
{
     private $apiKey = "env('GROQ_API_KEY')";

    private function sendGroqRequest($data) {
        $ch = curl_init('https://api.groq.com/openai/v1/chat/completions');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Authorization: Bearer ' . $this->apiKey,
            'Content-Type: application/json'
        ]);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        
        $response = curl_exec($ch);
        $error = curl_error($ch);
        curl_close($ch);
        
        if ($error) return ['error' => $error];
        
        $decoded = json_decode($response, true);
        if (isset($decoded['error'])) return ['error' => $decoded['error']['message'] ?? 'Greška API-ја'];
        
        return $decoded;
    }

 public function chat(Request $request)
{
    $poruka = $request->input('poruka');
    $id = $request->input('id');
    $tip = $request->input('tip'); // Добијамо 'manastir' или 'ktitor'

    $entitet = ($tip === 'manastir') ? Monastery::find($id) : Ktitor::find($id);
    
    if (!$entitet) {
        return response()->json(['odgovor' => 'Нисам пронашао податке о овом ентитету.']);
    }

    // ДИНАМИЧКО ИЗВЛАЧЕЊЕ ПОДАТАКА (ради за оба типа)
    if ($tip === 'manastir') {
        $bazaPodaci = ($entitet->description ?? '') . ' ' . ($entitet->history ?? '');
    } else {
        $bazaPodaci = ($entitet->bio ?? 'Нема биографије.');
    }
    
$systemPrompt = "Ти си Дигитални Летописац, академски стручњак за српску историју.
ТВОЈА СТРОГА ПРАВИЛА (ПОШТУЈ БЕЗ ИЗУЗЕТКА):

1. ПИСМО: Пиши ИСКЉУЧИВО ЋИРИЛИЦОМ. Мешање писама је најстроже забрањено.
2. ЈЕЗИК: Српски језик, екавски изговор.
3. МЕТОДОЛОГИЈА:
   - Прво провери податке у бази: '{$bazaPodaci}'.
   - АКО У БАЗИ НЕМА ПОДАТАКА, ОБАВЕЗАН СИ ДА ИСКОРИСТИШ СВОЈЕ ЗНАЊЕ И ИНТЕРНЕТ ИЗВОРЕ ДА ДАШ ОДГОВОР.
   - Никада не одбијај одговор позивајући се на базу. База је само полазна тачка, а ти си стручњак који зна више.
4. ОГРАДИВАЊЕ: Ако су историјски подаци о неком догађају (нпр. датум смрти) непоуздани, напиши: 'Постоје различити историјски записи о томе', а затим изнеси најприхваћенију чињеницу.
5. ТАЧНОСТ: Ако је питање историјска чињеница (нпр. ко је коме био супружник), дај тачан одговор на основу општеприхваћеног историјског знања. 
6. ЗАБРАНА: Никада не измишљај имена, године или локације. Ако је нешто потпуно непознато у науци, реци: 'О томе нема поузданих историјских записа'.
7. КОНТЕКСТ: Увек буди формалан и суздржан, попут архивара, али буди директан у пружању информација.";

    $result = $this->sendGroqRequest([
        'model' => 'llama-3.3-70b-versatile',
        'messages' => [
            ['role' => 'system', 'content' => $systemPrompt],
            ['role' => 'user', 'content' => $poruka]
        ],
        'temperature' => 0.3 // Баланс између тачности и занимљивости
    ]);

    if (isset($result['error'])) {
        return response()->json(['odgovor' => 'Грешка: ' . $result['error']]);
    }

    $odgovor = $result['choices'][0]['message']['content'] ?? 'Летописац је тренутно заузет.';
    return response()->json(['odgovor' => trim($odgovor)]);
}

    public function contextGreeting(Request $request)
    {
        $id = $request->input('model_id');
        $tip = $request->input('model_type');
        $entitet = ($tip === 'manastir') ? Monastery::find($id) : Ktitor::find($id);

        if (!$entitet) return response()->json(['success' => false]);

        $ime = $entitet->ime ?? $entitet->name ?? 'овом светом месту';

        // Елегантан и културан увод за мастер рад
        $greeting = "Мир са тобом. Добродошла у {$ime}. Као Дигитални Летописац, овде сам да ти помогнем у истраживању историје. Слободно ме упитај све што те занима.";

        return response()->json([
            'success' => true,
            'greeting' => $greeting
        ]);
    }
}