<?php

namespace App\Exceptions;

use Exception;
//Auth Login
use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'password',
        'password_confirmation',
    ];

    /**
     * Report or log an exception.
     *
     * This is a great spot to send exceptions to Sentry, Bugsnag, etc.
     *
     * @param  \Exception  $exception
     * @return void
     */
    public function report(Exception $exception)
    {
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Exception  $exception
     * @return \Illuminate\Http\Response
     */
    public function render($request, Exception $exception)
    {
		if ($exception instanceof MethodNotAllowedHttpException) {
			return redirect('/item');
		}

		if ($exception instanceof ModelNotFoundException) {
			return redirect('/item');
		}

		if ($this->isHttpException($exception)) {
			switch ($exception->getStatusCode()) {
				//not fouond
				case 404:
					return redirect('/item');
					break;
				//internal error
				case 500:
					return redirect('/item');
					break;
				default:
					return $this->renderHttpException($exception);
					break;
			}
		}

		return parent::render($request, $exception);
    }
	//認証していない場合にガードをみてそれぞれのログインページに飛ばす
	public function unauthenticated($request, AuthenticationException $exception)
	{
		if ($request->expectsJson()) {
			return response()->json(['message' => $exception->getMessage()], 401);
		}

		if (in_array('admin', $exception->guards())) {
			return redirect()->guest(route('admin.login'));
		}
		return redirect()->guest(route('login'));
	}
}
