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
<div class="panel-heading"><h4>購入履歴一覧</h4></div>
<table class="table">
<tr>
<th>ご注文番号</th>
<th>購入金額</th>
<th>購入日時</th>
<th>配送状況</th>
<th></th>
</tr>
@foreach ($payments as $key => $payment)
<tr>
<td>{{ $payment->id }}</td>
<td>{{ $payment->amount }}</td>
<td>{{ date('Y年m月d日', strtotime($payment->created_at)) }}</td>
<td>
@if ($payment->status === 0)
手配中
@elseif ($payment->status === 1)
配送中
@elseif ($payment->status === 2)
配送済み
@elseif ($payment->status === 3)
キャンセル
@endif
</td>
<td><a href="{{ route('payments.show', ['id' => $payment->id]) }}">履歴詳細へ</a></td>
</tr>
@endforeach
</table>
</div>
</div>
</div>
</div>
@endsection
