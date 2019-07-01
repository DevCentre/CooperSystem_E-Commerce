@extends('layouts.app')

@section('css')
<style >
table thead tr th {
  color: #B87333;
}

span.text-center {
  display: block;
  margin-bottom: 10px;
  color: red;
}

button {
  font-size: 13px!important;
}
button i {
  font-size: 15px!important;
}</style>
@endsection

@section('content')
<div class="container" ng-app="myApp" ng-controller="myCtrl">
  <div class='row'>
    <div class="col-md-4">
        <h2>Produtos</h2>
        <table class="table table-hover">
          <thead>
            <tr>
              <th class='hidden'>ID</th>
              <th>Produto</th>
              <th>Preço</th>
              <th>Ação</th>
            </tr>
          </thead>
          <tbody>
            <tr ng-repeat="item in items">
              <td class='hidden'><% item.id %></td>
              <td><% item.name %></td>
              <td>R$ <% item.price %></td>
              <td>
                <button ng-click="addItem(item)" class="btn btn-sm btn-info">
                  Add ao carrinho
                  <i class="fa fa-shopping-cart"></i>
                </button>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
      <div class="col-md-4">
        <h2>Meu Carrinho</h2>
        <table class="table table-hover" id='tableCarrinho'>
          <thead>
            <th class='hidden'>ID</th>
            <th>Produto</th>
            <th>Preço</th>
            <th>Quantidade</th>
          </thead>
          <tbody id="ok">
            <tr ng-repeat="myItem in myItems | reverse" class='itemsCarrinho'>
              <td class="hidden" id='itemID'><% myItem.id %></td>
              <td id='itemName'><% myItem.name %></td>
              <td id='itemPrice'>R$ <% myItem.price %></td>
              <td id='itemQuantidade'><% myItem.count %></td>
            </tr>
          </tbody>
        </table>
        <span class="text-center" ng-show="myItems.length == 0">
          Seu carrinho está vazio.
        </span>
        <div class="clearfix"></div>
        <span class="pull-right">Preço Total: <span class='valorTotalPedido'><% !totalPrice ? "0" : totalPrice | currency:'R$ ' %></span></span>
        <button ng-click="removeBasket()" ng-show="myItems.length > 0" class="pull-left alert alert-danger">Limpar Carrinho</button>
        <button style='margin-top: 20%;margin-left: -35%;' ng-click="finalizarCompra()" ng-show="myItems.length > 0" class="alert alert-success">Finalizar Compra</button>
      </div>
  </div>


</div>

@endsection

@section('scripts')
<script >
var app = angular.module('myApp', [], function($interpolateProvider) {
    $interpolateProvider.startSymbol('<%');
    $interpolateProvider.endSymbol('%>');
});

let produtos;

produtos = '{!! $produtos !!}';

app.controller('myCtrl', function($scope) {

  // produtos
  $scope.items = [
    @foreach ($produtos as $produto)
      {
        id: {{ $produto->idProduto }},
        name: '{{ $produto->nomeProd }}',
        price: parseFloat({{ $produto->valorProd }})
      },
    @endforeach
  ];

  // carrinho
  $scope.myItems = [];
  //add ao carrinho
  $scope.addItem = function(newItem) {
    if($scope.myItems.length == 0) {
      newItem.count = 1;
      $scope.myItems.push(newItem);
    }else {
      var repeat = false;
      for( var i = 0; i < $scope.myItems.length; i++ ) {if (window.CP.shouldStopExecution(1)){break;}
        if($scope.myItems[i].id == newItem.id) {
          $scope.myItems[i].count++;
          repeat = true;
        }
      }
      window.CP.exitedLoop(1);

      if(!repeat) {
        newItem.count = 1;
        $scope.myItems.push(newItem);
      }
    }
    updatePrice();
  };

  // update preco total
  var updatePrice = function() {
    var totalPrice = 0;
    for( var i = 0; i < $scope.myItems.length; i++ ) {if (window.CP.shouldStopExecution(2)){break;}
      totalPrice += ($scope.myItems[i].count) * ($scope.myItems[i].price);
    }
    window.CP.exitedLoop(2);

    $scope.totalPrice = totalPrice;
  };

  //limpar carrinho
  $scope.removeBasket = function() {
    $scope.myItems.splice(0, $scope.myItems.length);
    updatePrice();
  };


  let cartInfo;
  let dadosCarrinho;
  let quantidadeTotal;
  let valorTotalPedido;
  $scope.finalizarCompra = function(){
    Swal.fire({
        title: 'Finalizar sua compra?',
        // text: "You won't be able to revert this!",
        type: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Sim, Finalizar!',
        cancelButtonText:'Cancelar'
      }).then((result) => {
        if (result.value) {
          cartInfo = getCartInfo();
          dadosCarrinho = cartInfo[0];
          quantidadeTotal = cartInfo[1];
          valorTotalPedido = $('.valorTotalPedido').html();
          console.log(dadosCarrinho);
          console.log(quantidadeTotal);
          $.ajax({
            url: '/Pedido/add',
            type: 'POST',
            dataType: 'json',
            data: {_token:'{{ csrf_token() }}',dadosCarrinho: dadosCarrinho,quantidadeTotal:quantidadeTotal,valorTotalPedido:valorTotalPedido}
          })
          .done(function(data) {
            if(data=='success'){
              Swal.fire(
                'Sucesso!',
                'Compra realizada com sucesso.',
                'success'
              ).then((result)=>{
                window.location='/meusPedidos';
              });
            }
            console.log("success");
          })
          .fail(function() {
            console.log("error");
          })
          .always(function() {
            console.log("complete");
          });
        }
      })
    };
});

app.filter('reverse', function() {
  return function(items) {
    var x = items.slice().reverse();
    if( items.length > 1 ) {
      angular.element('#ok tr').css('background','#fff');
      angular.element('#ok tr').filter(':first').css('background','lightyellow');
      setTimeout(function() {
        angular.element('#ok tr') .filter(':first').css('background','#fff');
      }, 500);
    }
    return x;
  };
});



let produtosCarrinho = new Array();
let quantidadeTotal=0;
function getCartInfo(){

  $("#tableCarrinho tr").each(function(i, v){
      if (!this.rowIndex) return; // pular primeira linha
      produtosCarrinho[i] = Array();
      $(this).children('td').each(function(ii, vv){
          produtosCarrinho[i][ii] = $(this).text();
          if(ii==3){
            quantidadeTotal++;
          }
      });
  })
  // console.log(produtosCarrinho);
  return [produtosCarrinho,quantidadeTotal];

}



</script>
@endsection
