@extends('layouts.app')

@section('content')
<table class="table">
<tr>
<th>商品名</th>
<th>値段</th>
<th>在庫の有無</th>
</tr>
@foreach ($items as $item)
<tr>
<td><a href="{{ route('item', ['id' => $item->id]) }}">{{ $item->name }}</a></td>
<td>{{ $item->price }}</td>
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
@endsection