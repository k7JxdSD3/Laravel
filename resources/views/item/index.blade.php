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
<table class="table">
<tr>
<th>商品名</th>
<th>値段</th>
<th>在庫の有無</th>
</tr>
@foreach ($items as $item)
<tr>
<td><a href="{{ route('item', ['id' => $item->id]) }}">{{ $item->name }}</a></td>
<td>￥{{ $item->price }}</td>
<td>
@if ($item->stock === 0)
在庫無し
@elseif ($item->stock >= 1)
在庫あり
@endif
</td>
</tr>
@endforeach
</table>
<div class="text-center">{{ $items->links() }}</div>
</div>
</div>
</div>
</div>
@endsection
