@extends('layouts.app_admin')

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
<div class="panel-heading">商品の追加</div>

<div class="panel-body">
<form class="form-horizontal" method="POST" action="{{ route('admin.item.add') }}" enctype="multipart/form-data">
{{ csrf_field() }}

<div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
<label for="name" class="col-md-4 control-label">商品名</label>

<div class="col-md-6">
<input id="name" type="name" class="form-control" name="name" value="{{ old('name') }}" required autofocus>

@if ($errors->has('name'))
<span class="help-block">
<strong>{{ $errors->first('name') }}</strong>
</span>
@endif
</div>
</div>

<div class="form-group{{ $errors->has('explanation') ? ' has-error' : '' }}">
<label for="explanation" class="col-md-4 control-label">商品説明</label>

<div class="col-md-6">
<input id="explanation" type="text" class="form-control" name="explanation" value="{{ old('explanation') }}" required autofocus>

@if ($errors->has('explanation'))
<span class="help-block">
<strong>{{ $errors->first('explanation') }}</strong>
</span>
@endif
</div>
</div>

<div class="form-group{{ $errors->has('price') ? ' has-error' : '' }}">
<label for="price" class="col-md-4 control-label">値段</label>

<div class="col-md-6">
<input id="price" type="number" class="form-control" name="price" value="{{ old('price') }}" required autofocus>

@if ($errors->has('price'))
<span class="help-block">
<strong>{{ $errors->first('price') }}</strong>
</span>
@endif
</div>
</div>

<div class="form-group{{ $errors->has('stock') ? ' has-error' : '' }}">
<label for="stock" class="col-md-4 control-label">在庫数</label>

<div class="col-md-6">
<input id="stock" type="number" class="form-control" name="stock" value="{{ old('stock') }}" required autofocus>

@if ($errors->has('stock'))
<span class="help-block">
<strong>{{ $errors->first('stock') }}</strong>
</span>
@endif
</div>
</div>

<div class="form-group{{ $errors->has('image_name') ? ' has-error' : '' }}">
<label for="image_name" class="col-md-4 control-label">商品画像</label>

<div class="col-md-6">
<input id="image_name" type="file" class="form-control" name="image_name" autofocus>

@if ($errors->has('image_name'))
<span class="help-block">
<strong>{{ $errors->first('image_name') }}</strong>
</span>
@endif
</div>
</div>

<div class="form-group">
<div class="col-md-8 col-md-offset-4">
<button type="submit" class="btn btn-primary">
登録
</button>

</div>
</div>
</form>
</div>
</div>
<a href="{{ route('admin.items') }}">商品一覧へ</a>
</div>
</div>
</div>
@endsection
