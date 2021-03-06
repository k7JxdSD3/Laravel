<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">

	<!-- CSRF Token -->
	<meta name="csrf-token" content="{{ csrf_token() }}">

	<title>{{ config('app.name', 'Laravel') }}</title>

	<!-- Styles -->
	<link href="{{ asset('css/app.css') }}" rel="stylesheet">
	<link href="{{ asset('css/stripe.css') }}" rel="stylesheet">
	<script src="https://ajaxzip3.github.io/ajaxzip3.js" charset="UTF-8"></script>

</head>
<body>
	<div id="app">
		<nav class="navbar navbar-default navbar-static-top">
			<div class="container">
				<div class="navbar-header">

					<!-- Collapsed Hamburger -->
					<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#app-navbar-collapse" aria-expanded="false">
						<span class="sr-only">Toggle Navigation</span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
					</button>

					<!-- Branding Image -->
					<a class="navbar-brand" href="{{ url('/item') }}">
						{{ config('app.name', 'Laravel') }}
					</a>
				</div>

				<div class="collapse navbar-collapse" id="app-navbar-collapse">
					<!-- Left Side Of Navbar -->
					<ul class="nav navbar-nav">
						&nbsp;
					</ul>

					<!-- Right Side Of Navbar -->
					<ul class="nav navbar-nav navbar-right">
						<!-- Authentication Links -->
						@guest
							<li><a href="{{ route('login') }}">ログイン</a></li>
							<li><a href="{{ route('register') }}">登録</a></li>
							@guest('admin')
								<li><a href="{{ route('admin.login') }}">管理者</a></li>
							@endguest
						@endguest
							<li><a href="{{ route('items') }}">商品一覧</a></li>
						@auth
							<li class="dropdown">
								<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false" aria-haspopup="true" v-pre>
									{{ Auth::user()->name }} <span class="caret"></span>
								</a>

								<ul class="dropdown-menu">
									<li><a href="{{ route('cart') }}">カート</a></li>
									<li><a href="{{ route('address') }}">住所一覧</a></li>
									<li><a href="{{ route('auth.edit') }}">ユーザー情報編集</a></li>
									<li><a href="{{ route('cards.index') }}">クレジットカード一覧</a></li>
									<li><a href="{{ route('payments.index') }}">購入履歴一覧</a></li>
									<li>
										<a href="{{ route('logout') }}"
											onclick="event.preventDefault();
												 document.getElementById('logout-form').submit();">
											Logout
										</a>

										<form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
											{{ csrf_field() }}
										</form>
									</li>
								</ul>
							</li>
							<li><a href="{{ route('payments.create') }}">注文確認</a></li>
						@endauth
					</ul>
				</div>
			</div>
		</nav>

		@yield('content')
	</div>

	<!-- Scripts -->
	<script src="{{ asset('js/app.js') }}"></script>
	<!-- stripe -->
	@if (request()->path() === 'payments/create' || request()->path() === 'cards/create')
		@include('payments.payment');
	@endif
</body>
</html>
