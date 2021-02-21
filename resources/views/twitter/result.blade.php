@extends('layouts.app')

@section('content')
<div class="container">
@if (!empty($search_result))
@foreach ($search_result as $tweet)
<div class="card mb-2">
<div class="panel panel-default">
<div class="card-body">
<div class="media">
<ul style="list-style:none;">
<li><img src="{{ $tweet->user->profile_image_url }}" class="rounded-circle mr-4"></li>
<li><div class="media-body"></li>
<li><h5 class="d-inline mr-3"><strong>{{ $tweet->user->name }}</strong></h5></li>
<li><h6 class="d-inline text-secondary">{{ date('Y/m/d', strtotime($tweet->created_at)) }}</h6></li>
<li><p class="mt-3 mb-0">{{ $tweet->text }}</p></li>
</ul>
</div>
</div>
</div>
</div>
<br>
@endforeach
@else
<div class="panel panel-default">
<h4>　tweetが見つかりませんでした</h4>
</div>
@endif
<a href="{{ route('twitter.search') }}">Tweets Searchへ戻る</a>
</div>

@endsection
