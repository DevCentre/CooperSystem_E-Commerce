<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DataTables;
use App\Produto;
use DB;


class ProdutosController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $produtos = new Produto;

        $produtos = $produtos->get();

        return view('home',compact('produtos'));
    }

    public function create(Request $request){

      $this->validate(request(), [
          'nomeProd' => 'required',
          'valorProd' => 'required',
          'quantidadeEstoque' => 'required',
          'situacaoProd' => 'required'
      ]);

      Produto::create([
         'nomeProd' => request('nomeProd'),
         'valorProd' => request('valorProd'),
         'quantidade_estoque' => request('quantidadeEstoque'),
         'tb_situacao_produto_idSituacao' => request('situacaoProd')
      ]);

      return redirect()->to('/admin_produtos');
    }

    public function gerenciaProdutos(){

      return view('Produtos/admin_produtos');


    }

    public function retrieveProdutos(){

      $produtos = new Produto;

      $produtos = $produtos->get();


      return \DataTables::of($produtos)->make(true);

    }

    public function edit(Request $request){

      $produto = Produto::find($request->idProduto);

      $this->validate(request(), [
          'nomeProd' => 'required',
          'valorProd' => 'required',
          'quantidadeEstoque' => 'required',
          'situacaoProd' => 'required'
      ]);

      $produto->nomeProd = request('nomeProd');
      $produto->valorProd = request('valorProd');
      $produto->quantidade_estoque = request('quantidadeEstoque');
      $produto->tb_situacao_produto_idSituacao = request('situacaoProd');

      $produto->save();

      return redirect()->to('/admin_produtos');
    }

    public function delete(Request $request){

      $produto = Produto::find($request->idProduto);

      $produto->delete();

      return response()->json('success');
    }


}
