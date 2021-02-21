@extends('layouts.app_admin')
@section('content')
<div class="container">
<div class="row">
<div class="col-md-8 col-md-offset-2">
<div class="panel panel-default">
<div class="panel-heading">会員一覧</div>
<table class="table">
<tr>
<th>ID</th>
<th>会員名</th>
<th>作成日時</th>
</tr>
@foreach ($users as $user)
<tr>
<td>{{ $user->id }}</td>
<td><a href="{{ route('admin.users.show', ['user' => $user->id]) }}">{{ $user->name }}</a></td>
<td>{{ $user->created_at }}</td>
</tr>
@endforeach
</table>
</div>
</div>
</div>
</div>
@endsection
