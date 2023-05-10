<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use App\Models\Transfere;
use App\Util\UtilMessages;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Http;

/**
 * @OA\Tag(
 *     name="transfere",
 *     description="Operações relacionadas a transferências entre usuários comuns e lojistas"
 * )
 */
class TransfereController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/v1/transferencias",
     *     summary="Recupera todas as transferências",
     *     description="Este método recupera todas as transferências e retorna uma resposta JSON com um código de status 200.",
     *     tags={"transfere"},
     *     @OA\Response(
     *         response=200,
     *         description="Uma lista de transferências",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/Transfere")
     *         )
     *     )
     * )
     */
    public function index()
    {   //Paginação desativada para testes
        //$transferencias = Transfere::paginate(10);
        $transferencias = Transfere::all();
        return response()->json($transferencias, 200);
    }

    /**
     * @OA\Post(
     *     path="/api/v1/transfere",
     *     summary="Realiza uma transferência de valores entre usuários",
     *     description="Este método lida com a transferência de valores entre usuários. Ele recebe uma solicitação com os parâmetros origem, destino e quantia. O método verifica se o usuário de origem é um lojista e se ele tem saldo suficiente para realizar a transferência. Se essas condições forem atendidas, uma transação é iniciada para atualizar os saldos das carteiras dos usuários de origem e destino.",
     *     tags={"transfere"},
     *     @OA\RequestBody(
     *         required=true,
     *         description="Dados da transferência",
     *         @OA\JsonContent(
     *             required={"origem", "destino", "quantia"},
     *             @OA\Property(property="origem", type="integer", description="ID do usuário de origem"),
     *             @OA\Property(property="destino", type="integer", description="ID do usuário de destino"),
     *             @OA\Property(property="quantia", type="number", description="Quantia a ser transferida")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Transferência realizada com sucesso"
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Erro na transferência (lojista não pode enviar transferências ou saldo insuficiente)"
     *     )
     * )
     */
    public function transfere(Request $request)
    {

        $origem = User::with(['carteira', 'profile'])->find($request->input('origem'));
        $destino = User::with(['carteira', 'profile'])->find($request->input('destino'));
        $quantia = $request->input('quantia');

        $carteira_origem = $origem->carteira;
        $profile_origem = $origem->profile;
        $carteira_destino = $destino->carteira;
        $profile_destino = $destino->profile;

        $tipo = $profile_origem->tipo;

        // Verifica se o usuário de origem é um lojista
        if ($origem->profile->tipo == 'lojista') {
            return response()->json(['error' => 'Lojistas não podem enviar transferências'], 400);
        }

        // Verifica se o usuário de origem tem saldo suficiente
        if ($origem->carteira->saldo < $quantia) {
            return response()->json(['error' => 'Saldo insuficiente'], 400);
        }
        try {
            DB::transaction(function () use ($origem, $destino, $quantia) {
                // Consulta ao serviço autorizador externo
                $response = Http::get('http://run.mocky.io/v3/f2fe9a2d-090f-4129-b9bf-70d283c97d5c');
                $autorizado = $response->json()['messagem'] == 'autorizado';

                if ($autorizado) {
                    // Atualiza os saldos das carteiras
                    $origem->carteira->decrement('saldo', $quantia);
                    $destino->carteira->increment('saldo', $quantia);

                    // Registra a transferência
                    Transfere::create([
                        'origem_id' => $origem->id,
                        'transferido_de' => $origem->name,
                        'destinatario_id' => $destino->id,
                        'recebido_por' => $destino->name,
                        'quantia' => $quantia,
                    ]);
                }
            });
            // Transação concluída com sucesso
            // Envia a notificação para o usuário 
            $notificationMessage = null;
            try {
                $response = Http::get('http://run.mocky.io/v3/4ce65eb0-2eda-4d76-8c98-8acd9cfd2d39');
                $statusCode = $response->status();

                if ($response->successful()) {
                    $notificationMessage = 'A notificação foi enviada com sucesso';
                }
            } catch (\Exception $e) {
                // Ocorreu um erro ao enviar a notificação
                $notificationMessage = 'Ocorreu um erro ao enviar a notificação: '
                    . $e->getMessage();
            }
        } catch (\Exception $e) {
            $message = new UtilMessages($e->getMessage());
            return response()->json($message->getMessage(), 401);
        }

        return response()->json([
            'success' => 'Transferência realizada com sucesso',
            'De' => $profile_origem->nome,
            'Para' => $profile_destino->nome,
            'Valor transferido' => $quantia,
            'Erro de notificação' => $notificationMessage,
            'Status da notificação' => $statusCode,
        ], 200);
    }
}
