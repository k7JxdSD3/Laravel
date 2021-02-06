@extends('layouts.app')

@section('content')
<div class="container">
<div class="row">
<div class="col-md-8 col-md-offset-2">
<div class="panel panel-default">
<div class="panel-heading"><h3>住所登録</h3></div>

<div class="panel-body">
<form class="form-horizontal" method="POST" action="{{ route('address.add') }}">
{{ csrf_field() }}

<div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
<label for="name" class="col-md-4 control-label">氏名</label>

<div class="col-md-6">
<input id="name" type="text" class="form-control" name="name" value="{{ old('name') }}" required autofocus>

@if ($errors->has('name'))
<span class="help-block">
<strong>{{ $errors->first('name') }}</strong>
</span>
@endif
</div>
</div>

<div class="form-group{{ $errors->has('zip') ? ' has-error' : '' }}">
<label for="zip" class="col-md-4 control-label">郵便番号</label>
半角数字7桁(ハイフン無し)
<div class="col-md-6">
<input id="zip" type="number" class="form-control" name="zip" onKeyUp="AjaxZip3.zip2addr(this, '', 'prefectures', 'city');" value="{{ old('zip') }}" required autofocus>

@if ($errors->has('zip'))
<span class="help-block">
<strong>{{ $errors->first('zip') }}</strong>
</span>
@endif
</div>
</div>

<div class="form-group{{ $errors->has('prefectures') ? ' has-error' : '' }}">
<label for="prefectures" class="col-md-4 control-label">都道府県</label>

<div class="col-md-6">
<select name="prefectures" class="form-control">
@foreach(config('pref') as $key => $name)
<option name="prefectures" value="{{ $name }}">{{ $name }}</option>
@endforeach
</select>

@if ($errors->has('prefectures'))
<span class="help-block">
<strong>{{ $errors->first('prefectures') }}</strong>
</span>
@endif
</div>
</div>

<div class="form-group{{ $errors->has('city') ? ' has-error' : '' }}">
<label for="city" class="col-md-4 control-label">市区町村</label>

<div class="col-md-6">
<input id="city" type="text" class="form-control" name="city" value="{{ old('city') }}" required autofocus>

@if ($errors->has('city'))
<span class="help-block">
<strong>{{ $errors->first('city') }}</strong>
</span>
@endif
</div>
</div>

<div class="form-group{{ $errors->has('address') ? ' has-error' : '' }}">
<label for="address" class="col-md-4 control-label">以下住所</label>

<div class="col-md-6">
<input id="address" type="text" class="form-control" name="address" value="{{ old('address') }}" required autofocus>

@if ($errors->has('address'))
<span class="help-block">
<strong>{{ $errors->first('address') }}</strong>
</span>
@endif
</div>
</div>


<div class="form-group{{ $errors->has('phone_number') ? ' has-error' : '' }}">
<label for="phone_number" class="col-md-4 control-label">電話番号</label>
半角数字(ハイフン無し)
<div class="col-md-6">
<input id="phone_number" type="number" class="form-control" name="phone_number" value="{{ old('phone_number') }}" required autofocus>

@if ($errors->has('phone_number'))
<span class="help-block">
<strong>{{ $errors->first('phone_number') }}</strong>
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
<a href="{{ route('address') }}">住所一覧へ</a>
</div>
</div>
</div>
@endsection
