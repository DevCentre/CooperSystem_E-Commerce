<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Pedido extends Model
{
  protected $table = 'tb_pedido';
  protected $primaryKey = 'idPedido';

  protected $fillable = [
    'quantidade','data_pedido','solicitante','despachante','valor_total_pedido'
  ];

}
