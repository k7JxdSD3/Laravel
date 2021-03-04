@extends('layouts.app')

@section('content')
<div class="container">
<div class="row">
<div class="col-md-8 col-md-offset-2">
<h3>ご注文が完了しました</h3>
<div class="panel-body">

<div class="panel panel-default">
<div class="panel-heading">ご注文ありがとうございます</div>
<label>
<ul style="list-style:none;">
<li>ご注文番号</li>
<li>{{ $charge_id }}</li>
</ul>
</label>
</div>

<a href="{{ route('items') }}">商品一覧へ戻る</a>
</div>
</div>
</div>
</div>

@endsection
