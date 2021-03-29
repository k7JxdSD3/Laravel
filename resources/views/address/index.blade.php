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

<h2>住所一覧</h2>

@if ($addresses_count < 5)
<div class="form-group">
<a href="{{ route('address.add') }}">
<button type="submit" class="btn btn-primary">
<font color="white">お届け先追加の入力フォーム</font>
</button>
</a>
</div>
@endif

@if (!empty($addresses[0]))
@if (isset($default_address_id))
<div class="form-group text-right">
<button type="submit" class="btn btn-success btn-sm">
<a href="{{ route('payments.create') }}">
<font color="white">デフォルトの住所でレジへ進む</font>
</a>
</button>
</div>
@endif
<form method="POST" action="{{ route('address.default') }}">
{{ csrf_field() }}
<div class="form-group text-right">
<button type="submit" class="btn btn-primary btn-sm">
<font color="white">お届け先を指定してレジに進む</font>
</button>
</a>
</div>
@foreach ($addresses as $address)
<div class="panel panel-default">
<label>
<ul style="list-style:none;">
<input type="radio" name="address_id" value="{{ $address->id }}" required>
<li>{{ $address->name }}</li>
<li>〒{{ substr($address->zip, 0, 3) . "-" . substr($address->zip, 3) }}</li>
<li>{{ $address->prefectures }}</li>
<li>{{ $address->city }}</li>
<li>{{ $address->address }}</li>
<li>電話番号：{{ $address->phone_number }}</li>
<br>
@if ($default_address_id === $address->id)
デフォルトの住所に指定されています
@endif
<br>
<span><a href="{{ route('address.edit', ['address_id' => $address->id]) }}">編集</a> |
<a href="{{ route('address.delete', ['address_id' => $address->id]) }}">削除</a></span>
</ul>
</label>
</div>
@endforeach
</form>
@else
<div class="panel panel-default">
<h3>　お届け先が登録されていません<br>
<br>『お届け先追加の入力フォーム』から登録して下さい</h3>
</div>
@endif

</div>
</div>
</div>
@endsection
