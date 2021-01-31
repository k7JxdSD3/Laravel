<?php

return [
	'client_id'     => env('TWITTER_API_KEY'),
	'client_secret' => env('TWITTER_API_SECRET'),
	'access_token'  => env('TWITTER_ACCESS_TOKEN'),
	'access_token_secret' => env('TWITTER_ACCESS_TOKEN_SECRET'),
	'redirect'      => env('TWITTER_CALLBACKURL'),
];
