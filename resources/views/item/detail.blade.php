@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
				<table class="table">
				<tr>
				<th>商品名</th>
				<th>商品説明</th>
				<th>値段</th>
				<th>在庫の有無</th>
				</tr>
				<tr>
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
            <a href="{{ route('items') }}">商品一覧へ</a>
        </div>
    </div>
</div>

@endsection
