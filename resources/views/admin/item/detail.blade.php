@extends('layouts.app_admin')

@section('content')
<div class="container">
<div class="row align-items-center">
<div class="col-md-8 col-md-offset-2">
@if (session('success'))
<div class="alert alert-success">
{{ session('success') }}
</div>
@endif

@if (session('error'))
<div class="alert alert-danger">
{{ session('error') }}
</div>
@endif

<div class="form-group">
<a href="{{ route('admin.item.edit', ['id' => $item->id]) }}">
<button type="submit" class="btn btn-primary">
<font color="white">商品編集はこちら</font>
</button>
</a>
</div>

<div class="panel panel-default">
<div class="panel-heading">商品詳細</div>
<table class="table">
<tr>
@if ($item->image_name)
<th></th>
@endif
<th>商品名</th>
<th>商品説明</th>
<th>値段</th>
<th>在庫の有無</th>
</tr>
<tr>
@if ($item->image_name)
<td><div class="text-center"><img src="{{ asset('storage/item_image/' . $item->image_name) }}" alt="image"></div></td>
@endif
<td>{{ $item->name }}</td>
<td>{{ $item->explanation }}</td>
<td>{{ $item->price }}</td>
<td>
@if ($item->stock === 0)
在庫無し
@elseif ($item->stock >= 1)
在庫あり
@endif
</td>
</tr>
</table>
</div>
<a href="{{ route('admin.items') }}">商品一覧へ</a>
</div>
</div>
</div>

@endsection
