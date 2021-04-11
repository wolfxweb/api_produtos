<?php

namespace App\Http\Controllers;

use App\Models\Cadastro;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Http\Response;
use Illiminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Laravel\Passport\HasApiTokens;
use Laravel\Passport\Passport;

/*
Como utilizar esta classe

1° Cadastrando usuario.
    Campos obrigatorio dos campos name, email, password e password_confirmation.
    Metodo de envio POST --- rota cadastro -> api/cadastro
    Caso os campos sejan enviado com nome diferente retorna erro 422 para o usuario.
    Esta é a operação não precisa enviar o token de acesso.

2° Realizando o login do usuario
    Campos obrigatorio dos campos email, password
    Metodo de envio POST --- rota cadastro -> api/login
    Caso o usuario não tenha permissão para acessar a api vai retornar erro 403 usuario sem permissão
    Esta é a operação não precisa enviar o token de acesso.

3° Niveis de usuario
   1 Admin (Acesso a todas as contas)
   2 Vendedor
   3 Cliente

4° Bloqueio de acesso
   0 o usurio que estiver com nivel setado neste valor não terar acesso ao sistema

5° Exclusão do usuario
   Metodo de envio DELETE  --- rota cadastro -> api/cadastro/id que deseja deletar
   IMPORTATE ESTE METODO NÃO EXCLUI O USUARIO DA BASE ELE APENAS BLOQUEIA O ACESSO DO USUARIO AO SISTEMA

*/


class CadastroController extends Controller
{

    public function login(Request $request)
    {


        $validacao = $request->validate([ //verifica se os campos email e senha foram enviardos
            'email' => 'required|string|email',
            'password' => 'required',
        ]);
        if(Auth::attempt(['email'=> $request->email ,'password'=> $request->password])){
            $user  = auth()->user();// seleciona usuario logado
            if($user->nivel == 0){
                return response()->json(['error' =>'Usuário Inativo entre em contato com gestor do sistema '], 403); //retorna erro 403 usuario sem permissão
            }
            $user->token = $user->createToken($user->email)->accessToken; // cria um tokem para o usuario
            return response()->json($user, 200); // retorna o usuario logado
        }else{
           return response()->json(['error' =>'usuário ou senha invalida'], 403); //retorna erro 403 usuario sem permissão
        }

    }


    /**
     * Store a newly created resource in storage.
     * @param Illuminate\Http\Request $request
     * @return Response
     */
    public function store(Request $request)
    {



       // return response()->json($request);

         $request->validate([ // valida nome email e senha
            'name' => 'required|min:3|string',
            'email' => 'required|string|email|unique:users',
            'password' => 'required|string|confirmed',

        ]);

        $user = User::create([// cadastra o usuario no banco de dados
                'name'=>$request->name,
                'email'=>$request->email,
                'password'=>bcrypt($request->password),
                'nivel'=>'1',
        ]);

        $user->save();
        $user->token = $user->createToken($user->email)->accessToken; // cria um tokem para o usuario
        return $user; // retorna a usuario cadastrado
    }

    /**
     * Display the specified resource.
     *
     * @param Cadastro $cadastro
     * @return Response
     */
    public function show(Cadastro $cadastro)
    {

        return "show";
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param Cadastro $cadastro
     * @return Response
     */
    public function update(Request $request, Cadastro $cadastro)
    {

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Cadastro $cadastro
     * @return Response
     */
    public function destroy($cadastro)
    {
       $user = User::find($cadastro);
        if (empty($user) ) { // caso a categoria não exista no banco de dados retorna 404
            return response()->json(['error' => 'Usuario Indisponível '], 404);
        }
        $user->update([
            'name'=>$user->id,
            'email'=>$user->email,
            'password'=>bcrypt($user->email),
            'nivel'=>0,// desativa o usuario
        ]);
        return $user;
    }
}
