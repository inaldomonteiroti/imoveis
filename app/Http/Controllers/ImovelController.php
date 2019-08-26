<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Imovel;
use Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Pagination\Paginator;


class ImovelController extends Controller
{
    //Validação de dados 

    protected function validarImovel($request){
        $validator = Validator::make($request->all(), [
            "descricao" => "required",
            "logradouroEndereco"=> "required",
            "bairroEndereco" => "required",
            "numeroEndereco" => "required | numeric",
            "cepEndereco" => "required ",
            "cidadeEndereco" => "required",
            "preco" => "required | numeric",
            "qtdQuartos" => "required | numeric",
            "tipo" => "required",
            "finalidade" => "required"
        ]);
        return $validator;
   
        }
    /**
     * Exibe uma lista de recursos dp banco (Imoveis).
     *
     * @return \Illuminate\Http\Response
     */

    public function index(Request $request)
{
    $qtd = $request['qtd'] ?: 4;
    $page = $request['page'] ?: 1;
    $buscar = $request['buscar'];
    Paginator::currentPageResolver(function () use ($page){
        return $page;
    });
    if($buscar){
        $imoveis = DB::table('imoveis')->where('cidadeEndereco', '=', $buscar)->paginate($qtd);
    }else{  
        $imoveis = DB::table('imoveis')->paginate($qtd);
    }
    $imoveis = $imoveis->appends(Request::capture()->except('page'));
    return view('imoveis.index', compact('imoveis'));
}
    
    /**
     * Mostra o formulário para criar um novo recurso(Imoveis).
     * criamos a view para exibir o formulário
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('imoveis.create');
    }

    /**
     * Armazene um recurso recém criado no aramazenamento (Imoveis).
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = $this->validarImovel($request);
        if($validator->fails()){
            return redirect()->back()->withErrors($validator->errors());
        }

        $dados = $request->all(); //Captura todos os dados da requisicao e salva na váriavel
        Imovel::create($dados);  //Chama o metodo create passando os $dados da requisicao

        return redirect()->route('imoveis.index'); // Rota responsavel de listar imoveis
    }

    /**
     * Exibe o recurso especificado. Através de um ID passado por parametro (Imoveis).
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $imovel = Imovel::find($id);
 
        return view('imoveis.show', compact('imovel'));
    }
    /**
     * Mostra o formulário para editar o recurso especificado. (Imoveis).
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $imovel = Imovel::find($id);

        return view('imoveis.edit', compact('imovel'));
    }

    /**
     * Atualize o recurso no banco de dados. (Imoveis).
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $validator = $this->validarImovel($request);
        if($validator->fails()){
            return redirect()->back()->withErrors($validator->errors());
        }

        $imovel = Imovel::find($id);
        $dados = $request->all();
        $imovel->update($dados);

        return redirect()->route('imoveis.index');
    }

    /**
     * Remove um recurso especificado do banco de dados. (Imoveis).
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $imovel = Imovel::find($id)->delete();
        return redirect()->route('imoveis.index');
    }
    public function remover($id)
    {
        $imovel = Imovel::find($id);
        return view('imoveis.remove', compact('imovel'));
    }
}
