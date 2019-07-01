<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DataTables;
use App\Pedido;
use Auth;
use DB;


class PedidosController extends Controller
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

        return view('meusPedidos');
    }

    public function create(Request $request){

      $dataPedido = date("Y-m-d H:i:s");

      $quantidadeTotal=$request->quantidadeTotal;
      $valorTotalPedido = str_replace("R$ ", "", request('valorTotalPedido'));
      $valorTotalPedido = str_replace(",", "", $valorTotalPedido);

      $pedido = new Pedido;

      $pedido->quantidade = request('quantidadeTotal');
      $pedido->valor_total_pedido = $valorTotalPedido;
      $pedido->tb_situacao_pedido_idSituacao_pedido = 1;
      $pedido->users_id = Auth::user()->id;
      $pedido->data_pedido = $dataPedido;

      $pedido->save();

      $idPedido = $pedido->idPedido;

      foreach($request->dadosCarrinho as $items){
        if($items){
          $idProduto = $items[0];
          $quantidadeIndividual = $items[3];
          DB::table('tb_produtos_pedido')
          ->insert([
            'tb_produto_idProduto'=>$idProduto,
            'tb_pedido_idPedido'=>$idPedido,
            'quantidade_individual'=>$quantidadeIndividual
          ]);
        }
      }

      return response()->json('success');
    }

    public function retrievePedidos(){

      DB::statement("SET sql_mode = '' ");//workaround mysql ONLY_FULL_GROUP_BY
      $produtosPedido = DB::table('tb_pedido as P')
      ->select(DB::raw('group_concat(PR.nomeProd ORDER BY nomeProd SEPARATOR "," ) as nomeProdutos'),'P.quantidade','P.idPedido','P.data_pedido','P.valor_total_pedido','PP.quantidade_individual','SP.situacaoPedido')
      ->join('tb_produtos_pedido as PP','PP.tb_pedido_idPedido','P.idPedido')
      ->join('tb_produto as PR','PR.idProduto','PP.tb_produto_idProduto')
      ->join('tb_situacao_pedido as SP','SP.idSituacao_pedido','P.tb_situacao_pedido_idSituacao_pedido')
      ->where('users_id',Auth::user()->id)
      ->groupBy('P.idPedido')
      ->get();


      return \DataTables::of($produtosPedido)->make(true);

    }



}
