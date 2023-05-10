<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use App\Util\Utilmessages;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

/**
 * @OA\Tag(
 *     name="user",
 *     description="Operações relacionadas a usuários"
 * )
 */
class UserController extends Controller
{
    private $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    /**
     * Display a listing of the resource.
     */
    /**
     * @OA\Get(
     *     path="/api/v1/users",
     *     summary="Recupera uma lista de usuários",
     *     tags={"user"},
     *     @OA\Response(
     *         response=200,
     *         description="Uma lista de usuários",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/User")
     *         )
     *     )
     * )
     */
    public function index()
    {
        $user = $this->user->with(['profile', 'carteira'])->paginate('50');
        // $user = $this->user->paginate('50');
        return response()->json($user, '200');
    }

    /**
     * Store a newly created resource in storage.
     */
    /**
     * @OA\Post(
     *     path="/api/v1/users",
     *     summary="Cria um novo usuário",
     *     tags={"user"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/User")
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Usuário criado com sucesso",
     *         @OA\JsonContent(ref="#/components/schemas/User")
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Requisição inválida"
     *     )
     * )
     */
    public function store(Request $request)
    {
        $data = $request->all();

        if (!$request->has('password') || !$request->get('password')) {
            $message = new UtilMessages('É necessário informar uma senha para  usuário...');
            return response()->json($message->getMessage(), 401);
        }

        Validator::make($data, [
            'cpf' => 'unique:user_perfil',
            'cnpj' => 'unique:user_perfil',
            'tipo' => 'required'
        ])->validate();

        try {
            $data['password'] = bcrypt($data['password']);

            $user = $this->user->create($data); // Mass Assigment

            $user->profile()->create(
                [
                    'nome' => $data['nome'],
                    'cpf' => $data['cpf'],
                    'cnpj' => $data['cnpj'],
                    'tipo' => $data['tipo']
                ]
            );

            return response()->json([
                'data' => [
                    'msg' => 'Usuário cadastrado com sucesso!'
                ]
            ], 200);
        } catch (\Exception $e) {
            $message = new UtilMessages($e->getMessage());
            return response()->json($message->getMessage(), 401);
        }
    }

    /**
     * Display the specified resource.
     */
    /**
     * @OA\Get(
     *     path="/api/v1/users/{id}",
     *     summary="Recupera um usuário pelo ID",
     *     tags={"user"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Usuário encontrado",
     *         @OA\JsonContent(ref="#/components/schemas/User")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Usuário não encontrado"
     *     )
     * )
     */
    public function show(string $id)
    {
        try {
            // $user = $this->user->with('profile')->findOrFail($id);
            $user = $this->user->with(['profile', 'carteira'])->findOrFail($id);

            if (isset($user['profile']['nome'])) {
                $user['profile']['nome completo'] = $user['profile']['nome'];
                unset($user['profile']['nome']);
            }

            return response()->json([
                //'user' => $user->name
                'user' => $user
            ], 200);
        } catch (\Exception $e) {
            $message = new UtilMessages($e->getMessage());
            return response()->json($message->getMessage(), 401);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    /**
     * @OA\Put(
     *     path="/api/v1/users/{id}",
     *     summary="Atualiza um usuário pelo ID",
     *     tags={"user"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/User")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Usuário atualizado com sucesso",
     *         @OA\JsonContent(ref="#/components/schemas/User")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Usuário não encontrado"
     *     )
     * )
     */
    public function update(Request $request, string $id)
    {
        $data = $request->all();

        if ($request->has('password') && $request->get('password')) {
            $data['password'] = bcrypt($data['password']);
        } else {
            unset($data['password']);
        }

        Validator::make($data, [
            'cpf' => 'unique:user_perfil',
            'cnpj' => 'unique:user_perfil',
            'tipo' => 'required'
        ])->validate();

        try {
            $user = $this->user->findOrFail($id);
            $user->update($data); // Mass Assigment

            return response()->json([
                'data' => [
                    'msg' => 'Usuário atualizada com sucesso!'
                ]
            ], 200);
        } catch (\Exception $e) {
            $message = new Utilmessages($e->getMessage());
            return response()->json($message->getMessage(), 401);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    /**
     * @OA\Delete(
     *     path="/api/v1/users/{id}",
     *     summary="Exclui um usuário pelo ID",
     *     tags={"user"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=204,
     *         description="Usuário excluído com sucesso"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Usuário não encontrado"
     *     )
     * )
     */
    public function destroy(string $id)
    {
        try {
            $user = $this->user->findOrFail($id);
            $user->delete(); // Mass Assigment

            return response()->json([
                'data' => [
                    'msg' => 'usuário removido com sucesso!'
                ]
            ], 200);
        } catch (\Exception $e) {
            $message = new Utilmessages($e->getMessage());
            return response()->json($message->getMessage(), 401);
        }
    }
}
