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

<h3>注文の確認</h3>
<div class="panel-body">

@if (!empty($address) && !empty($carts) && !empty($default_card->brand))
<form class="form-horizontal" method="POST" action="{{ route('payments.store') }}">
{{ csrf_field() }}
@endif
<div class="panel panel-default">
<div class="panel-heading">お届け先住所</div>
<label>
<ul style="list-style:none;">
@if (!empty($address))
<li>{{ $address->name }}</li>
<li>〒{{ substr($address->zip, 0, 3) . "-" . substr($address->zip, 3) }}</li>
<li>{{ $address->prefectures }}</li>
<li>{{ $address->city }}</li>
<li>{{ $address->address }}</li>
<li>電話番号：{{ $address->phone_number }}</li>
<br>
<a href="{{ route('address') }}">住所を変更する</a>
@else
<li>お届け先が設定されていません</li>
<br>
<a href="{{ route('address') }}">お届け先を設定する</a>
@endif
</ul>
</label>
</div>

<div class="panel panel-default">
<div class="panel-heading">お支払い方法</div>
<ul style="list-style:none;">
@if (!empty($default_card->brand))
<li>{{ $default_card->brand }}</li>
<li>下4桁 {{ $default_card->last4 }}</li>
<br>
<a href="{{ route('cards.index') }}">他のカードに変更する</a>
@else
<li>クレジットカードが登録されていません</li>
<br>
<a href="{{ route('cards.create') }}">クレジットカードを登録する</a>
@endif
</ul>
</div>

<div class="panel panel-default">
<div class="panel-heading">注文する商品</div>
<ul style="list-style:none;">
@if (!empty($carts))
<table class="table">
<tr>
<th>商品名</th>
<th>価格</th>
<th>購入数</th>
<th>小計</th>
</tr>
@foreach ($carts as $cart)
<tr>
<td>{{ $cart['name'] }}</td>
<td>￥{{ $cart['price'] }}</td>
<td>{{ $cart['number_items'] }}</td>
<td>￥{{ $cart['subtotal'] }}</td>
</tr>
@endforeach
</table>
<p><font size="4">合計金額（税抜き）: ￥{{ $total }}</font></p>
<p><font size="4">合計金額（税込み）: ￥{{ $including_tax }}</font></p>
<br>
<a href="{{ route('cart') }}">カートへ戻る</a>
@else
<li>カートに商品がありません</li>
@endif
<br>
<a href="{{ route('items') }}">商品一覧へ</a>
</ul>
</div>

@if (!empty($address) && !empty($carts) && !empty($default_card->brand))
<div class="text-center form-group">
<button type="submit" id="create_token" class="btn btn-primary">注文を確定する</button>
</div>
</form>
@endif

</div>
</div>
</div>
</div>

@endsection
