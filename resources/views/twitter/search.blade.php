@extends('layouts.app')

@section('content')
<div class="container">
	<div class="row">
		<div class="col-md-8 col-md-offset-2">
			<div class="panel panel-default">
				<div class="panel-heading">ツイートの検索</div>

				<div class="panel-body">
					<form class="form-horizontal" method="POST" action="{{ route('twitter.result') }}">
						{{ csrf_field() }}

						<div class="form-group{{ $errors->has('keyword') ? ' has-error' : '' }}">
							<label for="keyword" class="col-md-4 control-label">キーワード</label>

							<div class="col-md-6">
								<input id="keyword" type="text" class="form-control" name="keyword" value="" required autofocus>

								@if ($errors->has('keyword'))
									<span class="help-block">
										<strong>{{ $errors->first('keyword') }}</strong>
									</span>
								@endif
							</div>
						</div>

						<div class="form-group">
							<div class="col-md-8 col-md-offset-4">
								<button type="submit" class="btn btn-primary">
								 検索
								</button>

							</div>
						</div>
					</form>
				</div>
			</div>
			<!--<a href="{{ route('admin.items') }}">商品一覧へ</a>--!>
		</div>
	</div>
</div>
@endsection