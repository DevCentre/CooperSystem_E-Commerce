<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Produto extends Model
{
  protected $table = 'tb_produto';
  protected $primaryKey = 'idProduto';

  protected $fillable = [
    'nomeProd','valorProd','quantidade_estoque','tb_situacao_produto_idSituacao'
  ];

}
