namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class GameController extends Controller
{
    // Generisanje priče i zagonetke za Nivo 1 preko Groq API-ja
    public function getRiddle(Request $request)
    {
        $level = $request->query('level', 1);

        if ($level == 1) {
            $prompt = "Nalaziš se na Nivou 1 igre 'Светосавски Скиптар: Сенке Бездна'. Naziv nivoa je 'Манастир који је нестао'. " .
                      "Igrač je bakljom osvetlio oltarsku fresku i na njoj su se pojavila svetleća slova. " .
                      "Ti si narator/monah čuvar. Generiši mističnu, pravoslavnu istorijsku zagonetku čiji je tačan odgovor reč 'Студеница' " .
                      "(majka svih srpskih manastira, zadužbina Stefana Nemanje). " .
                      "Zagonetka mora zvučati arhaično, duhovno i tajanstveno, npr. pominjati Bogorodicu, mermer, i kosti utemeljivača dinastije. " .
                      "Napiši samo tekst zagonetke, bez ikakvih drugih rečenica ili uvoda, na srpskom jeziku (može ćirilica).";
        } else {
            $prompt = "Generiši zagonetku za sledeći nivo {$level} na temu srpske istorije.";
        }

        $response = Http::withToken(env('GROQ_API_KEY'))
            ->post('https://api.groq.com/openai/v1/chat/completions', [
                'model' => 'llama3-8b-8192',
                'messages' => [['role' => 'user', 'content' => $prompt]]
            ]);

        $riddle = $response->json()['choices'][0]['message']['content'];

        return response()->json(['riddle' => $riddle]);
    }

    // Verifikacija unosa za Nivo 1
    public function verifyAnswer(Request $request)
    {
        $level = $request->input('level');
        $unos = trim(mb_strtolower($request->input('unos'), 'UTF-8'));

        if ($level == 1) {
            // Provera da li unos sadrži ključnu reč "studenica" (bilo ćirilicom ili latinicom)
            if (str_contains($unos, 'studenica') || str_contains($unos, 'студеница')) {
                return response()->json([
                    'success' => true,
                    'message' => "☦️ ТАЧНО! Зидови манастира трепере! Из фреске Светог Симеона избија заслепљујућа светлост која тера Бездан у крик! Пронашао си прvi фрагмент Скиптра Светог Саве! Спреми се, време те преноси даље... 👑"
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => "👹 ХАХАХА! Твоја свећа бледи! Прогрешио си име манастира, тама гута твоје сећање. Размисли поново ко је мајка свих српских светиња подно Радочела!"
                ]);
            }
        }

        return response()->json(['success' => false, 'message' => 'Непознат ниво.']);
    }
}