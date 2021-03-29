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

<div class="panel panel-default">
<div class="panel-heading">注文履歴詳細</div>
<label>
<ul style="list-style:none;">
<li>ご注文番号：{{ $payment->id }}</li>
<li>注文日時：{{ date('Y年m月d日 H時i分', strtotime($payment->created_at)) }}</li>
<li>合計金額(税込)：￥{{ $payment->amount }}</li>
</ul>
</label>
</div>

<div class="panel panel-default">
<div class="panel-heading">お届け先</div>
<label>
<ul style="list-style:none;">
<li>お名前：{{ $payment->name }}</li>
<li>〒{{ substr($payment->zip, 0, 3) . "-" . substr($payment->zip, 3) }}</li>
<li>{{ $payment->prefectures }}</li>
<li>{{ $payment->city }}</li>
<li>{{ $payment->address }}</li>
<li>電話番号：{{ $payment->phone_number }}</li>
</ul>
</label>
</div>

<div class="panel panel-default">
<div class="panel-heading">注文商品詳細</div>
<ul style="list-style:none;">
<table class="table">
<tr>
<th>商品名</th>
<th>価格(税抜き)</th>
<th>購入数</th>
</tr>
@foreach ($payment->purchases as $purchase)
<tr>
<td>{{ $purchase->item_name }}</td>
<td>￥{{ $purchase->price }}</td>
<td>{{ $purchase->number_items }}</td>
</tr>
@endforeach
</table>
</ul>
</div>

@if ($payment->status === 0)
<form class="form-horizontal" method="POST" action="{{ route('payments.cancel', ['id' => $payment->id]) }}">
{{ csrf_field() }}
<div class="text-center form-group">
<button type="submit" id="cancel" class="btn btn-danger">注文をキャンセルする</button>
</div>
</form>
@endif

<a href="{{ route('payments.index') }}">購入履歴一覧へ戻る</a>
</div>
</div>
</div>
@endsection
