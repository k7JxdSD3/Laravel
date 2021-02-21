@extends('layouts.app_admin')

@section('content')
<div class="container">
<div class="row">
<div class="col-md-8 col-md-offset-2">
<div class="panel panel-default">
<div class="panel-heading">会員詳細</div>
<table class="table">
<tr>
<th>名前</th>
<th>メールアドレス</th>
</tr>
<tr>
<td>{{ $user->name }}</td>
<td>{{ $user->email }}</td>
</tr>
</table>
</div>

@if (!empty($user->addresses[0]))
@foreach ($user->addresses as $key => $address)
<div class="panel panel-default">
<div class="panel-heading">お届け先住所<?= $key + 1 ?></div>
<label>
<ul style="list-style:none;">
<li>お名前：{{ $address->name }}</li>
<li>〒{{ substr($address->zip, 0, 3) . "-" . substr($address->zip, 3) }}</li>
<li>{{ $address->prefectures }}</li>
<li>{{ $address->city }}</li>
<li>{{ $address->address }}</li>
<li>電話番号：{{ $address->phone_number }}</li>
</ul>
</label>
</div>
@endforeach
@else
<div class="panel panel-default">
<h4>このユーザーはお届け先が登録されていません</h4>
</div>
@endif
<a href="{{ route('admin.users.index') }}">会員一覧へ戻る</a>
</div>
</div>
</div>
@endsection
