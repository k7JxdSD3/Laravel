@extends('layouts.app')

@section('content')
<div class="container">
<div class="row">
<div class="col-md-8 col-md-offset-2">
<div class="panel panel-default">
<div class="panel-heading">ユーザー編集画面</div>

<div class="panel-body">
@if (session('status'))
<div class="alert alert-success">
{{ session('status') }}
</div>
@endif

@if (session('warning'))
<div class="alert alert-danger">
{{ session('warning') }}
</div>
@endif

<form class="form-horizontal" method="POST" action="{{ route('auth.update') }}">
{{ csrf_field() }}

<div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
<label for="name" class="col-md-4 control-label">名前</label>

<div class="col-md-6">
<input id="name" type="text" class="form-control" name="name" value="{{ old('name', $user->name) }}" required autofocus>

@if ($errors->has('name'))
<span class="help-block">
<strong>{{ $errors->first('name') }}</strong>
</span>
@endif
</div>
</div>

<div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
<label for="email" class="col-md-4 control-label">メールアドレス</label>

<div class="col-md-6">
<input id="email" type="email" class="form-control" name="email" value="{{ old('email', $user->email) }}" required>

@if ($errors->has('email'))
<span class="help-block">
<strong>{{ $errors->first('email') }}</strong>
</span>
@endif
</div>
</div>

<div class="form-group{{ $errors->has('new_password') ? ' has-error' : '' }}">
<label for="password" class="col-md-4 control-label">新しいパスワード</label>

<div class="col-md-6">
<input id="password" type="password" class="form-control" name="new_password">

@if ($errors->has('new_password'))
<span class="help-block">
<strong>{{ $errors->first('new_password') }}</strong>
</span>
@endif
</div>
</div>

<div class="form-group{{ $errors->has('new_password_confirmation') ? ' has-error' : '' }}">
<label for="password-confirm" class="col-md-4 control-label">新しいパスワード（確認用）</label>
<div class="col-md-6">
<input id="password-confirm" type="password" class="form-control" name="new_password_confirmation">

@if ($errors->has('new_password_confirmation'))
<span class="help-block">
<strong>{{ $errors->first('new_password_confirmation') }}</strong>
</span>
@endif
</div>
</div>

<div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
<label for="password" class="col-md-4 control-label">現在のパスワード（必須）</label>

<div class="col-md-6">
<input id="password" type="password" class="form-control" name="password" required>

@if ($errors->has('password'))
<span class="help-block">
<strong>{{ $errors->first('password') }}</strong>
</span>
@endif
</div>
</div>

<div class="form-group">
<div class="col-md-6 col-md-offset-4">
<button type="submit" class="btn btn-primary">
編集
</button>
</div>
</div>
</form>
</div>
</div>
</div>
</div>
</div>
@endsection
