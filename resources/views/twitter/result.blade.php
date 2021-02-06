@extends('layouts.app')

@section('content')
<div class="container">
@foreach ($search_result as $tweet)
<div class="card mb-2">
<div class="card-body">
<div class="media">
<img src="{{ $tweet->user->profile_image_url }}" class="rounded-circle mr-4">
<div class="media-body">
<h5 class="d-inline mr-3"><strong>{{ $tweet->user->name }}</strong></h5>
<h6 class="d-inline text-secondary">{{ date('Y/m/d', strtotime($tweet->created_at)) }}</h6>
<p class="mt-3 mb-0">{{ $tweet->text }}</p>
</div>
</div>
</div>
<div class="card-footer bg-white border-top-0">
<div class="d-flex flex-row justify-content-end">
<div class="mr-5"><i class="far fa-comment text-secondary"></i></div>
<div class="mr-5"><i class="fas fa-retweet text-secondary"></i></div>
<div class="mr-5"><i class="far fa-heart text-secondary"></i></div>
</div>
</div>
</div>
@endforeach
<a href="{{ route('twitter.search') }}">Tweets Searchへ戻る</a>
</div>

@endsection
