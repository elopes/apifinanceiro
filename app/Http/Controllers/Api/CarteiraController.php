<?php

namespace App\Http\Controllers\Api;

use App\Models\Carteira;
use App\Http\Controllers\Controller;
use App\Http\Requests\CarteiraRequest;
use App\Util\UtilMessages;

/**
 * @OA\Tag(
 *     name="carteira",
 *     description="Operações relacionadas a carteira do usuário"
 * )
 */
class CarteiraController extends Controller
{
    private $carteira;
    public function __construct(Carteira $carteira)
    {
        $this->carteira = $carteira;
    }

    /**
     * @OA\Get(
     *     path="/api/v1/carteira",
     *     summary="Recupera uma lista de carteiras",
     *     tags={"carteira"},
     *     @OA\Response(
     *         response=200,
     *         description="Uma lista de carteiras",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/Carteira")
     *         )
     *     )
     * )
     */
    public function index()
    {
        $carteira = $this->carteira->paginate('10');
        return response()->json($carteira, '200');
    }

    /**
     * @OA\Get(
     *     path="/api/v1/carteira/{id}",
     *     summary="Recupera uma carteira pelo ID",
     *     tags={"carteira"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Carteira encontrada",
     *         @OA\JsonContent(ref="#/components/schemas/Carteira")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Carteira não encontrada"
     *     )
     * )
     */
    public function show($id)
    {
        try {
            $carteira = $this->carteira->findOrFail($id);

            return response()->json([
                'carteira' => $carteira
            ], 200);
        } catch (\Exception $e) {
            $message = new UtilMessages($e->getMessage());
            return response()->json($message->getMessage(), 401);
        }
    }

    /**
     * @OA\Post(
     *     path="/api/v1/carteira",
     *     summary="Cria uma nova carteira",
     *     tags={"carteira"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/Carteira")
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Carteira criada com sucesso",
     *         @OA\JsonContent(ref="#/components/schemas/Carteira")
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Requisição inválida"
     *     )
     * )
     */
    public function store(CarteiraRequest $request)
    {
        $data = $request->all();

        try {
            // $carteira = $this->carteira->create($data); // Mass Assigment
            $this->carteira->create($data);
            return response()->json([
                'data' => [
                    'msg' => 'Carteira cadastrada com sucesso!'
                ]
            ], 200);
        } catch (\Exception $e) {
            $message = new UtilMessages($e->getMessage());
            return response()->json($message->getMessage(), 401);
        }
    }


    /**
     * @OA\Put(
     *     path="/api/v1/carteira/{id}",
     *     summary="Atualiza uma carteira pelo ID",
     *     tags={"carteira"},
     *     @OA\Parameter(
     *          name="id",
     *          in="path",
     *          required=true,
     *          @OA\Schema(type="integer")
     *      ),
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(ref="#/components/schemas/Carteira")
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Carteira atualizada com sucesso",
     *          @OA\JsonContent(ref="#/components/schemas/Carteira")
     *      ),
     *      @OA\Response(
     *          response=404,
     *          description="Carteira não encontrada"
     *      )
     *  )
     */
    public function update($id, CarteiraRequest $request)
    {
        $data = $request->all();

        try {
            $carteira = $this->carteira->findOrFail($id);
            $carteira->update($data); // Mass Assigment

            return response()->json([
                'data' => [
                    'msg' => 'Carteira atualizada com sucesso!'
                ]
            ], 200);
        } catch (\Exception $e) {
            $message = new UtilMessages($e->getMessage());
            return response()->json($message->getMessage(), 401);
        }
    }

    public function destroy($id)
    {
        try {
            $carteira = $this->carteira->findOrFail($id);
            $carteira->delete(); // Mass Assigment

            return response()->json([
                'data' => [
                    'msg' => 'Carteira removida com sucesso!'
                ]
            ], 200);
        } catch (\Exception $e) {
            $message = new UtilMessages($e->getMessage());
            return response()->json($message->getMessage(), 401);
        }
    }
}
