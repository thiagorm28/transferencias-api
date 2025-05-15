<?php

namespace App\Http\Controllers;

use App\DTO\Auth\LoginDTO;
use App\DTO\Auth\LogoutDTO;
use App\DTO\Auth\RegisterUserDTO;
use App\Exceptions\Auth\InvalidCredentialsException;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterUserRequest;
use App\Services\Auth\AuthService;
use Exception;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AuthController extends Controller
{
    public function __construct(private AuthService $authService) {}

    /**
     * Cria um novo usuário
     */
    public function register(RegisterUserRequest $request)
    {
        try {
            $this->authService->registerUser(new RegisterUserDTO(
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
            $token = $this->authService->login(new LoginDTO(
                email: $request->email,
                password: $request->password,
            ));
        } catch (InvalidCredentialsException $e) {
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
        try {
            $this->authService->logout(new LogoutDTO(
                token: $request->bearerToken()
            ));
        } catch (Exception $e) {
            return response()->json(['message' => 'Houve um erro ao tentar sair do sistema!'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return response()->json(['message' => 'Logout realizado com sucesso.']);
    }
}
