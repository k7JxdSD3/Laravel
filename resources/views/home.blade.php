@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
		@if ( session('flash_message'))
		<div class="alert alert-success text-center">{{ session('flash_message') }}</div>
		@endif
            <div class="panel panel-default">
                <div class="panel-heading">Dashboard</div>

                <div class="panel-body">
                    @if (session('status'))
                        <div class="alert alert-success">
                            {{ session('status') }}
                        </div>
                    @endif

                    ログインしました!
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
