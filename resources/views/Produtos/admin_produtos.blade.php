@extends('layouts.app')

@section('css')
  {{-- DataTables --}}
  <link rel="stylesheet" type="text/css" href="/plugins/DataTables/datatables.min.css"/>
  <style>
    .linkSpan:hover{
      cursor:pointer;
    }
  </style>
@endsection

@section('content')



  <div class="container">
    {{-- Modal cadastra Produto --}}
    <div class="modal fade" id="CriaProd" tabindex="-1" role="dialog" aria-labelledby="" aria-hidden="false">
      <div class="modal-dialog modal-lg" style='left: 80px;'>
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            <h4 class="modal-title">Novo Produto</h4>
            {{-- <button class="pull-right ExpandirEncolherTodos"></button> --}}
          </div>
          <div class="modal-body">
              <form method="POST" action="/Produtos" id='formNewProduto'>
                @csrf
                <fieldset class="form-group">
                  <br />
                  <label>Nome Produto</label>
                  <input type='text' name='nomeProd' class='form-control' placeholder="Nome Produto"/>
                </fieldset>
                <fieldset class="form-group">
                  <label>Valor</label>
                  <input type="text" id='valorProduto' class="form-control" name="valorProd" placeholder="Valor unitário" required>
                </fieldset>
                <fieldset class="form-group">
                  <label>Quantidade em Estoque</label>
                  <input type="number" min='0' class="form-control" name="quantidadeEstoque" placeholder="Quantidade em estoque" required>
                </fieldset>
                <fieldset class="form-group">
                  <label>Situação</label>
                  <select class="form-control" name="situacaoProd">
                    <option value="1">Disponível</option>
                    <option value="2">Indisponível</option>
                  </select>
                </fieldset>
                <div class="modal-footer">
                  <button type="button" class="btn" data-dismiss="modal">Fechar</button>
                  <button type="submit" class="btn btn-primary pull-right">Cadastrar</button>
                </div>
            </form>
          </div>
        </div>
      </div>
    </div>
    <div class="modal fade" id="editProd" tabindex="-1" role="dialog" aria-labelledby="" aria-hidden="false">
      <div class="modal-dialog modal-lg" style='left: 80px;'>
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            <h4 class="modal-title">Novo Produto</h4>
            {{-- <button class="pull-right ExpandirEncolherTodos"></button> --}}
          </div>
          <div class="modal-body">
              <form method="POST" action="/Produtos/edit" id='formEditProduto'>
                @csrf
                <input type='hidden' id='idProdutoInput' name='idProduto'/>
                <fieldset class="form-group">
                  <br />
                  <label>Nome Produto</label>
                  <input type='text' name='nomeProd' class='form-control' placeholder="Nome Produto" id='nomeProd'/>
                </fieldset>
                <fieldset class="form-group">
                  <label>Valor</label>
                  <input type="text" class="form-control" name="valorProd" placeholder="Valor unitário" id='valorProdEdit' required>
                </fieldset>
                <fieldset class="form-group">
                  <label>Quantidade em Estoque</label>
                  <input type="number" min='0' class="form-control" name="quantidadeEstoque" placeholder="Quantidade em estoque" id='quantidadeProd' required>
                </fieldset>
                <fieldset class="form-group">
                  <label>Situação</label>
                  <select class="form-control" name="situacaoProd" id='situacaoProd'>
                    <option value="1">Disponível</option>
                    <option value="2">Indisponível</option>
                  </select>
                </fieldset>
                <div class="modal-footer">
                  <button type="button" class="btn" data-dismiss="modal">Fechar</button>
                  <button type="submit" class="btn btn-primary pull-right">Cadastrar</button>
                </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  {{--<div class="row"> --}}
    <div class="col-md-10" style='margin-top:5%'>
      <div class="panel panel-default">
        <div class="panel-heading">Produtos <a data-toggle="modal" href="#CriaProd" style="color:#65cb63" class="pull-right"><span class="fa fa-lg fa-plus-circle"></span></a></div>
        <div class="panel-body datatableProdBody">
          <table class="table responsive table-hover table-bordered table-striped datatableProd" width="100%">
            <thead>
              <tr>
                <th>Id</th>
                <th>Produto</th>
                <th>Valor Unitário</th>
                <th>Quantidade</th>
                <th>Situação</th>
                <th width="20%">Ações</th>
              </tr>
            </thead>
          </table>
        </div>
      </div>
    </div>
</div><br /><br />
@endsection


@section('scripts')
  <script type="text/javascript" src="/plugins/DataTables/datatables.min.js"></script>
  <script src="/plugins/moneyMask/jquery.maskMoney.min.js" type="text/javascript"></script>
  <script>

  $(function() {
    $("#valorProduto").maskMoney({prefix:'R$ ', allowNegative: true, decimal:'.', affixesStay: false});
  })

  $(function() {
    $("#valorProdEdit").maskMoney({prefix:'R$ ', allowNegative: true, decimal:'.', affixesStay: false});
  })

  let removedCommas;
  $('#formNewProduto').on('submit',function(){
    $("#valorProduto").maskMoney('unmasked');
    removedCommas = $("#valorProduto").val().replace(/,/g, '');
    $("#valorProduto").val(removedCommas);
  });


  $('#formEditProduto').on('submit',function(){
    $("#valorProdEdit").maskMoney('unmasked');
    removedCommas = $("#valorProdEdit").val().replace(/,/g, '');
    removedCommas = $("#valorProdEdit").val().replace('R$ ', '');
    $("#valorProdEdit").val(removedCommas);
  });


  $(document).ready(function() {
    var tableProd =
      $('.datatableProd').DataTable({
        processing: true,
        serverSide: false,
        responsive: true,
        // select: true,
        order: ([1, 'asc']),
        language: {
          'url': '/json/dataTables.pt-br.json' //Use this file to translate Datatable to pt-br
        },
        ajax: '{{ route('datatable/getDadosProduto') }}',
        initComplete: function() {
          $('.dataTables_paginate').css('margin-top', '20%');
          $('.dataTables_paginate').css('margin-left', '-20%');
        },
        columns: [{
            data: 'idProduto',
            className: 'text-center'
          },
          {
            data: 'nomeProd',
            className: 'text-center'
          },
          {
            // data: 'valorProd',
            mRender:function(type,data,row){
              return "R$ "+row.valorProd;
            },
            className: 'text-center'
          },
          {
            data: 'quantidade_estoque',
            className: 'text-center'
          },
          {
            mRender: function(data,type,row){
              if(row.tb_situacao_produto_idSituacao==1){
                return "Disponível";
              }else{
                return "Indisponível";
              }
            }
          },
          {
            mRender: function(data, type, row) {
              if(row.tb_situacao_produto_idSituacao==1){
                return '<span class="text-center"><a onclick="editProd(\'' + row.idProduto + '\',\'' + row.nomeProd + '\',\'' + row.valorProd + '\',\'' + row.quantidade_estoque +
                  '\',\'' + row.tb_situacao_produto_idSituacao +'\')" class="linkSpan edit-modal" title="editar"><i class="fa fa-edit"></i></a> | <a onclick="enableDisableProd(\'' + row.idProduto + '\')" class="linkSpan" style="color:#d96b53" title="desativar"><i class="fa fa-ban"></i></a> | <a onclick="deleteProd(\'' + row.idProduto + '\')" class="linkSpan" style="color:#d96b53" title="deletar"><i class="fa fa-trash"></i></a></span>';
              }else{
                return '<span class="text-center"><a onclick="editProd(\'' + row.idProduto + '\',\'' + row.nomeProd + '\',\'' + row.valorProd + '\',\'' + row.quantidade_estoque +
                  '\',\'' + row.tb_situacao_produto_idSituacao +'\')" class="linkSpan edit-modal" title="editar"><i class="fa fa-edit"></i></a> | <a onclick="enableDisableProd(\'' + row.idProduto + '\')" class="linkSpan" style="color:#a9e489" title="ativar"><i class="fa fa-check"></i></a> | <a onclick="deleteProd(\'' + row.idProduto + '\')" class="linkSpan" style="color:#d96b53" title="deletar"><i class="fa fa-trash"></i></a></span>';
              }
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


  function deleteProd(idProd){
    $.ajax({
      url: '/Produtos/deleteProd',
      type: 'POST',
      dataType: 'json',
      data: {_token: '{{ csrf_token() }}', idProduto: idProd}
    })
    .done(function(data) {
      if(data=='success'){
        Swal.fire({
          title: 'Sucesso!',
          text: 'Produto excluido com sucesso',
          type: 'success',
          confirmButtonText: 'Ok'
        });
        $('.datatableProd').DataTable().ajax.reload();
      }else{

      }
    })
    .fail(function() {
      console.log("error");
    })
    .always(function() {
      console.log("complete");
    });

  }

  function editProd(idProduto, nomeProd ,valorProd , quantidade_estoque, situacao){
    $('#idProdutoInput').val(idProduto);
    $('#nomeProd').val(nomeProd);
    $('#valorProdEdit').val('R$ '+valorProd);
    $('#quantidadeProd').val(quantidade_estoque);
    $('#situacaoProd').val(situacao);
    $('#editProd').modal('show');
  }

  </script>
@endsection
