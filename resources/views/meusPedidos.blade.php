@extends('layouts.app')

@section('css')
<link rel="stylesheet" type="text/css" href="/plugins/DataTables/datatables.min.css"/>
<style>
  .linkSpan:hover{
    cursor:pointer;
  }
</style>
@endsection

@section('content')

<div class="container">
  <div class="col-md-10" style='margin-top:5%'>
    <div class="panel panel-default">
      <div class="panel-heading">Meus Pedidos <a data-toggle="modal" href="/home" style="color:#65cb63" class="pull-right"><span class="fa fa-lg fa-plus-circle"></span></a></div>
      <div class="panel-body datatablePedidosBody">
        <table class="table responsive table-hover table-bordered table-striped datatablePedidos" width="100%">
          <thead>
            <tr>
              <th>Id Pedido</th>
              <th>Produtos</th>
              <th>Quantidade</th>
              <th>Valor Total</th>
              <th>Situação</th>
              <th width="20%">Ações</th>
            </tr>
          </thead>
        </table>
      </div>
    </div>
  </div>
</div>

@endsection

@section('scripts')
<script type="text/javascript" src="/plugins/DataTables/datatables.min.js"></script>
<script >
$(document).ready(function() {
  var tableProd =
    $('.datatablePedidos').DataTable({
      processing: true,
      serverSide: false,
      responsive: true,
      // select: true,
      order: ([1, 'asc']),
      language: {
        'url': '/json/dataTables.pt-br.json' //Use this file to translate Datatable to pt-br
      },
      ajax: '{{ route('datatable/getDadosPedidos') }}',
      initComplete: function() {
        $('.dataTables_paginate').css('margin-top', '20%');
        $('.dataTables_paginate').css('margin-left', '-20%');
      },
      columns: [{
          data: 'idPedido',
          className: 'text-center'
        },
        {
          data: 'nomeProdutos',
          className: 'text-center'
        },
        {
          data: 'quantidade',
          className: 'text-center'
        },
        {
          // data: 'valorProd',
          mRender:function(type,data,row){
            return "R$ "+row.valor_total_pedido;
          },
          className: 'text-center'
        },
        {
          data:'situacaoPedido'
        },
        {
          mRender: function(data, type, row) {
            return '<span class="text-center"><a onclick="reportar()" class="linkSpan edit-modal" title="reportar" style="color:#b87854"><i class="fa fa-exclamation-triangle"></i></a> | <a onclick="detalhePedido()" class="linkSpan"  title="ativar"><i class="fa fa-eye"></i></a></span>';

          },
          className: 'text-center',
          orderable: false
        }
      ],
      search: {
        "regex": true
      }
    });
  // tableProd.column(1).visible(false); // or true, if you want to show it
});

function reportar(){
  Swal.fire({
      title: 'Repotar este item',
      text: 'Deseja reportar algum problema com a compra?',
      type: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: 'Sim, Reportar!',
      cancelButtonText:'Cancelar'
    }).then((result) => {
      if (result.value) {
        Swal.fire(
          'Sucesso!',
          'Item reportado.',
          'success'
        );
      }
    });
}
function detalhePedido(){
  Swal.fire({
    title: 'Funcionalidade em Desenvolvimento!',
    text: 'Em breve: Veja os detalhes de seu pedido !',
    type: 'warning',
    confirmButtonText: 'Ok'
  });
}

</script>
@endsection
