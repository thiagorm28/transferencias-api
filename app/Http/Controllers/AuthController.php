<?php

namespace App\Http\Controllers;

use App\DTO\Auth\LoginDTO;
use App\DTO\Auth\RegisterUserDTO;
use App\Exceptions\Auth\WrongPasswordExeception;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterUserRequest;
use Illuminate\Http\Request;
use App\Repositories\Auth\RegisterUserRepository;
use App\Services\Auth\LoginService;
use Exception;
use Symfony\Component\HttpFoundation\Response;

class AuthController extends Controller
{
    private $loginService;
    private $registerUserRepository;

    public function __construct()
    {
        $this->loginService = new LoginService();
        $this->registerUserRepository = new RegisterUserRepository();
    }

    /**
     * Cria um novo usuário
     */
    public function register(RegisterUserRequest $request)
    {
        try {
            $this->registerUserRepository->execute(new RegisterUserDTO(
                name: $request->name,
                email: $request->email,
                cpf_cnpj: $request->cpf_cnpj,
                password: $request->password,
                type: $request->type,
            ));
        } catch (Exception $e) {
            return response()->json(['message' => 'Houve um erro ao criar o usuário!'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return response()->json(['message' => 'Usuário criado com sucesso!'], Response::HTTP_CREATED);
    }

    /**
     * Faz login do usuário
     */
    public function login(LoginRequest $request)
    {
        try {
            $token = $this->loginService->execute(new LoginDTO(
                email: $request->email,
                password: $request->password,
            ));
        } catch (WrongPasswordExeception $e) {
            return response()->json([
                'error' => $e->getMessage(),
            ], $e->getCode());
        } catch (Exception $e) {
            return response()->json(['message' => 'Houve um erro ao tentar logar!'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return response()->json([
            'access_token' => $token,
            'token_type' => 'Bearer',
        ]);
    }

    /**
     * Faz logout do usuário
     */
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json(['message' => 'Logout realizado com sucesso.']);
    }
}
