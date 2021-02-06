@extends('layouts.app')

@section('content')
<div class="container">
<div class="row">
<div class="col-md-8 col-md-offset-2">

@if ( session('flash_message'))
<div class="alert alert-success text-center">{{ session('flash_message') }}</div>
@endif

<a href="{{ route ('address') }}">
<div class="form-group" align="right">
<button type="submit" class="btn btn-primary">
<font color="white">お届け先選択</font>
</button>
</div>
</a>

<div class="panel panel-default">
@if (!empty($carts_data))
<table class="table">
<tr>
<th>商品名</th>
<th>価格</th>
<th>購入数</th>
<th>小計</th>
<th>削除ボタン</th>
</tr>
@foreach ($carts_data as $cart)
<tr>
<td>{{ $cart['name'] }}</td>
<td>￥{{ $cart['price'] }}</td>
<td>{{ $cart['number_items'] }}</td>
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
<font size="5">
カートが空です
</font>
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
