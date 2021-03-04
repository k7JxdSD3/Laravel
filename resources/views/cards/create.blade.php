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
<div class="panel-heading"><h3>クレジットカード登録</h3></div>

<div class="panel-body">

<div class="card-body">
<form action="{{ route('cards.store') }}" class="card-form" id="form_payment" method="POST">
{{ csrf_field() }}
<div class="form-group">
<label for="name">カード番号</label>
<div id="cardNumber" class="stripe-input"></div>
</div>
<div class="form-group">
<label for="name">セキュリティコード</label>
<div id="securityCode" class="stripe-input"></div>
</div>
<div class="form-group">
<label for="name">有効期限</label>
<div id="expiration" class="stripe-input"></div>
</div>
<div class="form-group">
<label for="name">カード名義</label><br>
<input type="text" name="cardName" id="cardName" class="stripe-input" value="" placeholder="カード名義を入力">
</div>
<div class="form-group">
<button type="submit" id="create_token" class="btn btn-primary">カードを登録する</button>
</div>
</form>
</div>

</div>
</div>
<a href="{{ route('cards.index') }}">クレジットカード一覧へ</a>
</div>
</div>
</div>

@endsection
