<?php

namespace App\Http\Controllers;
use App\Models\Categoria;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class CategoriaController extends Controller
{
    /**
     * Como Usar esta classe
     *
     * 1° o usuário deve enviar o token de acesso caso não seja envido ira retornar esta  "message": "Unauthenticated."
     *
     * 2° Para o cadastro o campo nome e descrição são obrigatórios.
     *
     * 3° Acessado todas as categorias deve ser realizado um GET para rota-> api/categoria
     *
     * 4°Adicionado categoria envie um POST para rota-> api/categoria
     *
     * 5°Pesquisando uma categoria em especifica envie um GET para rota -> api/categoria/ID QUE DESEJA CONSULTA
     *
     * 6°Atualizando uma categoria envie um PUT para rota -> api/categoria/ID QUE DESEJA ATUALIZAR
     *
     * 7°Excluindo uma categoria envie um DELETE para rota -> api/categoria/ID QUE DESEJA DELETAR
     *
     * IMPORTANTE O SISTEMA VALIDA SE A CATEGORIA PERTENCE AO USUÁRIO, CASO SEJA IDENTIFICADO QUE A CATEGORIA
     * NÃO SEJA DO USUÁRIO O SISTEMA INFORMA CATEGORIA INDISPONÍVEL.
     *
     */
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index(): JsonResponse
    {
        $user = Auth::user(); // pega usuario da session se não enviar o token o retorno e usuario sem premisão
        $categorias = Categoria::where('user_id', $user->id)->get(); // lista as categorias do usuario logado
        return response()->json($categorias, 200); // retorna a categoria cadastrada
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request): JsonResponse
    {
        $user = Auth::user(); // pega usuario da session se não enviar o token o retorno e usuario sem premisão

        $request->validate([ // valida o campo nome e descrição
            'name' => 'required|min:3',
            'descricao' => 'required',
        ]);
        $newCategoria = Categoria::create([ // para cadastar uma categoria e necessario enviaar o nome e a descricao.
            'name' => $request['name'],
            'descricao' => $request['descricao'],
            'user_id' => $user->id
            ,
        ]);
        return response()->json($newCategoria, 201); // retorna a categoria cadastrada
    }

    /**
     * Display the specified resource.
     * @param Categoria $categoria
     * @return JsonResponse
     */
    public function show($categoria)
    {
        $idCategoria = $this->validaCategoria($categoria);// função para verificar se a categoria e do usuario
        $showCategoria = Categoria::find($idCategoria);// seleciona a categoria
        if (empty($showCategoria) || $showCategoria === false) { // caso a categoria não exista no banco de dados retorna 404
            return response()->json(['error' => 'Categoria Indisponível '], 404);
        }
        return response()->json($showCategoria, 200);// retorna a categoria para usuairo
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param Categoria $categoria
     * @return JsonResponse
     */
    public function update(Request $request, $categoria)
    {
        $user = Auth::user(); // pega usuario da session se não enviar o token o retorno e usuario sem premisão
        $idCategoria = $this->validaCategoria($categoria);// função para verificar se a categoria e do usuario
        $updateCategoria = Categoria::find($idCategoria);// atualiza a categoria
        if (empty($idCategoria) || $idCategoria === false) { // caso a categoria não exista no banco de dados retorna 404
            return response()->json(['error' => 'Categoria Indisponível '], 404);
        }
        $updateCategoria->update($request->all());// atualiza a categoria
        $categorias = Categoria::where('user_id', $user->id)->get(); //pega a lista de categoria com o item alterado
        return response()->json($categorias, 200); // retorna todas as categoria
        //  return response()->json($categoriaPesquisada ,200);// retorna a categoria pesquisada
    }

    /**
     * Remove the specified resource from storage.
     * @param Categoria $categoria
     * @return JsonResponse|Response
     */
    public function destroy($categoria)
    {
        $idCategoria = $this->validaCategoria($categoria);
        if (empty($idCategoria) || $idCategoria === false) { // caso a categoria não exista no banco de dados retorna 404
            return response()->json(['error' => 'Categoria Indisponível '], 404);
        }
        $destroyCategoria = Categoria::find($idCategoria);
        $destroyCategoria->delete();
        return response()->json(['success' => 'Categoria excluida com sucesso '], 200);

    }

    public function validaCategoria($categoria): bool
    {
        // esta função e utilizada para validar se a categoria pertence ao usuario solicitante

        $user = Auth::user(); // pega usuario da session se não enviar o token o retorno e usuario sem premisão
        $cat = Categoria::find($categoria);// seleciona a categoria desejada
        if (empty($cat)) { // caso a categoria não exista no banco de dados retorna 404
            return false;
        }
        $categoriaPesquisada = Categoria::where('user_id', $user->id)// verifica se a categoria pertense ao usuário solicitante
        ->where('id', $cat->id)
            ->get();
        if (empty($categoriaPesquisada)) { //caso a categoria não exista no banco de dados retorna 404
            return false;
        }
        if (empty($categoriaPesquisada[0])) {
            return false;
        } else {
            return $cat->id; // tudo ok pode executar updade delete retorna o id
        }
    }
}
