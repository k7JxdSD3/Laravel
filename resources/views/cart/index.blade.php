@extends('layouts.app')

@section('content')
<div class="container">
	<div class="row">
		<div class="col-md-8 col-md-offset-2">
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
				<td>{{ $cart['price'] }}</td>
				<td>{{ $cart['total_items'] }}</td>
				<td>{{ $cart['subtotal'] }}</td>
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
			<p><font size="5">合計金額 {{ $total }}</font></p>
			@endif
			<a href="{{ route('items') }}">商品一覧へ</a>
		</div>
	</div>
</div>

@endsection
