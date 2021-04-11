<?php

namespace App\Http\Controllers;

use App\Models\Loja;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Image; // instalado composer require intervention/image para redimencionamento das imagens

class LojaController extends Controller
{
    /** Como usar esta classe
     *
     * *****  IMPORTANTE OBRIGATÓRIO ENVIO DO  TOKEN DE ACESSO ******
     * caso não seja envido ira retornar esta  "message": "Unauthenticated."
     * --------------------------------------------------------------
     * 1° cadastro de loja campos obrigatórios,
     *  -> o cadastro e via post para url -> /api/loja
     *  -> nome, slug E celular caso um deles não seja enviado a api vai retornar para cliente erro 422
     *  com um json informando oque deve ser corrigido para que a loja possa ser salva.
     *  IMPORTANTE O SLUG E VALIDADO LOGO NÃO ACEITA NOME JA CADASTRADO.
     *  -> As Imagens pode ser redimensionada  alterado os parâmetros  logoAltura ,logoLargura , imgFundoAltura, imgFundolargura
     *
     * 2° Update dos dados da loja
     *  -> Rota de update url -> /api/loja/{idLoja}
     *  -> método de envio a ser utilizado é PUT
     *  -> o item a serem atualizado devem ser enviado via formulário.
     *  -> este método não atualiza as imagens
     *
     * 3° Deletar Loja(inativar)
     *  -> Rota de delete url -> /api/loja/{idLoja}
     *  -> método de envio a ser utilizado é DELETE
     *  -> este método inativa
     *  -> objetivo e inativar contas que não pagaram esta flag vai decidir se os produtos devem ser listado
     *
     * 4° Visualizar o cadastro
     *  -> Rota de get url -> /api/loja
     *  -> método de envio a ser utilizado é GET
     *  -> este método lista  aloja do usuário logado
     *
     *
     *
     */
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = Auth::user(); //Pega usuário da loga para puxa a loja dele
        $lojaUser = Loja::where('user_id',$user->id)->get();
        return response()->json( $lojaUser, 200);// retorna loja
    }


    /**
     * Store a newly created resource in storage.
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $lojaStatus = true;
        $user = Auth::user(); // pega usuario da session se não enviar o token o retorno e usuario sem premisão
        $lojaUser = Loja::where('user_id',$user->id)->get();

        if(sizeof($lojaUser)>0){//verifica se o usuário tem loja cadastrada
            return response()->json( 'Opss você já tem loja cadastrada! ' , 403); //retorna status 403 usuário sem permissão
        }

        //definição size das imagens.
        $logoAltura = 300;
        $logoLargura = 300;
        $imgFundoAltura =1200;
        $imgFundolargura =1200;
        $urlLogo ="";
        $urlFundo ="";
        $imgLogo = $request->file('logo');
        $imgFundo=  $request->file('imgFundo');

        $request->validate([ // valida o campo nome e descrição
            'name' => 'required|min:3',
            'celular'=>'required',
            'slug'=>'required|min:3|unique:lojas',
        ]);

        if($request->hasFile('logo')) {// verfica se foi enviado imagem na request ante de salvar
            if($imgLogo->extension() == "png" || $imgLogo->extension() =="jpeg" || $imgLogo->extension() =="jpg" ){
                $filename = md5(microtime()).".".$imgLogo->extension();// altera o nome da imagen trocando por um timestamp em microsegundo
                $imgResize = Image::make($imgLogo)->resize( $logoAltura,$logoLargura);
                $imgResize->save(public_path('storage/imagens/logo/' .$filename)); //salavado o imagen
                $urlLogo = "storage/imagens/logo/".$filename; // url da imagen

            }else{
                return response()->json('Servidor so aceita as extensões PNG,JPEG e JPG !', 403); //retorna status 403 usuário sem permissã
            }
        }
        if($request->hasFile('imgFundo')) {// verfica se foi enviado imagem na request ante de salvar
            if($imgFundo->extension() == "png" || $imgFundo->extension() =="jpeg" || $imgFundo->extension() =="jpg" ){
                $filename = md5(microtime()).".".$imgFundo->extension();// altera o nome da imagen trocando por um timestamp em microsegundo
                $imgResize = Image::make($imgFundo)->resize($imgFundoAltura,$imgFundolargura);
                $imgResize->save(public_path('storage/imagens/fundoSite/' .$filename)); //salavado o imagen
                $urlFundo = "storage/imagens/fundoSite/".$filename; // url da imagen
            }else{
                return response()->json('Servidor so aceita as extensões PNG,JPEG e JPG !', 403); //retorna status 403 usuário sem permissão
            }
        }
        $newLoja = Loja::create([// criação da loja
            'user_id'=>$user->id,
            'name'=>$request->name,
            'logo'=>$urlLogo,// logo da loja
            'imgFundo'=> $urlFundo, //Imagen fundo do site
            'telefone'=>$request->telefone,
            'celular'=>$request->celular,
            'descricao'=>$request->descricao,
            'rua'=>$request->rua,
            'bairro'=>$request->bairro,
            'cidade'=>$request->cidade,
            'estado'=>$request->estado,
            'cep'=>$request->cep,
            'complemento'=>$request->complemento,
            'cnpj'=>$request->cnpj,
            'inscEstadual'=>$request->inscEstadual,
            'inscMunicipal'=>$request->inscMunicipal,
            'email'=>$request->email,
            'facebook'=>$request->facebook,
            'instagram'=>$request->instagram,
            'corTitulo'=>$request->corTitulo,
            'corFundo'=>$request->corFundo,
            'corFonte'=>$request->corFonte,
            'pixelFacebook'=>$request->pixelFacebook,
            'pixelGoogle'=>$request->pixelGoogle,
            'status'=>$lojaStatus,
            'slug'=>$request->slug
            ]);
        return response()->json($newLoja, 200);// retorna loja criada com sucesso
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Loja  $loja
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Loja $loja)
    {
        // IMPORTANTE ESTE METODO NÃO ATUALIZA IMAGEN

        $user = Auth::user(); //pega o usuario logado
        if($user->id == $loja->user_id ){// verifica se o usuario logado e dono da loja
          $loja->update($request->all());
           return response()->json($loja, 200);// retorna a loja atualizada
        }else{
            return response()->json("Sem permissão para executar esta ação", 403);// retorna sem permissão para alterar a loja
        }


    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Loja  $loja
     * @return \Illuminate\Http\Response
     */
    public function destroy(Loja $loja)
    {


        if( $loja->status == true ){// verifica se o usuario logado e dono da loja
            $loja->update([
                'status'=> false
            ]);
            return response()->json('Inativado com sucesso', 200);// retorna a loja atualizada
        }
        if($loja->status == false){
            $loja->update([
                'status'=> true
            ]);
            return response()->json("Sem permissão para executar esta ação", 200);// retorna sem permissão para alterar a loja
        }else{
            $loja->update([
                'status'=> true
            ]);
        }
    }

}
