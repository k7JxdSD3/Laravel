@extends('layouts.app')

@section('content')
<div class="container">
<div class="row">
<div class="col-md-8 col-md-offset-2">

@if (session('success'))
<div class="alert alert-success text-center">
{{ session('success') }}
</div>
@endif
@if (session('error'))
<div class="alert alert-danger text-center">
{{ session('error') }}
</div>
@endif

<a href="{{ route ('address') }}">
<div class="form-group" align="right">
<button type="submit" class="btn btn-primary">
<font color="white">お届け先選択</font>
</button>
</div>
</a>

<a href="{{ route ('payments.create') }}">
<div class="form-group" align="right">
<button type="submit" class="btn btn-success">
<font color="white">注文確認へ</font>
</button>
</div>
</a>

<div class="panel panel-default">
@if (!empty($carts_data))
<table class="table text-center">
<tr>
<th class="text-center">商品名</th>
<th class="text-center">価格</th>
<th></th>
<th class="text-center">購入数</th>
<th class="text-center"></th>
<th class="text-center">小計</th>
<th class="text-center">削除ボタン</th>
</tr>
@foreach ($carts_data as $cart)
<tr>
<td>{{ $cart['name'] }}</td>
<td>￥{{ $cart['price'] }}</td>
<td>
@if ($cart['number_items'] > 1)
<a href="{{ route('cart.decrease', ['item_id' => $cart['item_id']]) }}">
<button type="submit" class="btn btn-danger btn-sm">-</button>
</a>
@endif
</td>
<td>
<font size="3">{{ $cart['number_items'] }}</font>
</td>
<td>
@if ($cart['item_stock'] > 0)
<a href="{{ route('cart.increase', ['item_id' => $cart['item_id']]) }}">
<button type="submit" class="btn btn-success btn-sm">+</button>
</a>
@endif
</td>
<td>￥{{ $cart['subtotal'] }}</td>
<td>
<form class="form-horizontal" method="POST" action="{{ route('cart.delete', ['item_id' => $cart['item_id']]) }}">
{{ csrf_field() }}
<button type="submit" class="btn btn-primary">
削除
</button>
</form>
</td>
</tr>
@endforeach
</table>
@else
カートが空です
@endif
</div>

@if (!empty($carts_data))
<p><font size="4">合計金額（税抜き）: ￥{{ $total }}</font></p>
<p><font size="4">合計金額（税込み）: ￥{{ $including_tax }}</font></p>
@endif

<a href="{{ route('items') }}">商品一覧へ</a>
</div>
</div>
</div>

@endsection
