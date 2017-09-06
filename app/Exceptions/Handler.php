<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that should not be reported.
     *
     * @var array
     */
    protected $dontReport = [
        \Illuminate\Auth\AuthenticationException::class,
        \Illuminate\Auth\Access\AuthorizationException::class,
        \Symfony\Component\HttpKernel\Exception\HttpException::class,
        \Illuminate\Database\Eloquent\ModelNotFoundException::class,
        \Illuminate\Session\TokenMismatchException::class,
        \Illuminate\Validation\ValidationException::class,
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
    public function render($request, Exception $e)
    {
        $code = method_exists($e, 'getStatusCode') ? $e->getStatusCode() : 403;
        $errors = null;
        if ( method_exists($e, 'getErrors') && $e instanceof \Watson\Validating\ValidationException ) {
            $errors = $e->getErrors();
        }
        if ( $request->ajax() || $request->wantsJson() ) {
            $message = !empty($e->getMessage()) ? $e->getMessage() : 'An unknown error has occurred.';
            if ( empty($e->getMessage()) && is_a($e, 'Illuminate\Session\TokenMismatchException') ) {
                $message = 'Your form has expired, please refresh the page and try again.';
            }
            $data = ['success' => false, 'message' => $message, 'file' => $e->getFile(), 'line' => $e->getLine()];
            if ( !is_null($errors) ) {
                $data['errors'] = $errors->all();
                $data['message'] = implode(', ', $errors->all());
            }
            return response()->json($data, $code);
        } else {
            if ( !$request->isMethod('GET') ) {
                if ( $e instanceof \App\Exceptions\AppException || $e instanceof  \Stripe\Error\Base ) {
                    if ( !is_null($errors) ) {
                        return back()->withErrors($errors)->withInput();
                    } else {
                        \Msg::danger($e->getMessage());
                        return back()->withInput();
                    }
                } else {
                    return parent::render($request, $e);
                }
            } else {
                if ( $e instanceof \App\Exceptions\AppException ) {
                    abort(403, $e->getMessage());
                } else {
                    //$layout = in_array($request->segment(1), ['admin', 'account']) ? $request->segment(1) : 'index';
                    //\View::share('error_layout', $layout);
                    return parent::render($request, $e);
                }
            }
        }
    }

    /**
     * Convert an authentication exception into an unauthenticated response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Illuminate\Auth\AuthenticationException  $exception
     * @return \Illuminate\Http\Response
     */
    protected function unauthenticated($request, AuthenticationException $exception)
    {
        if ($request->expectsJson()) {
            return response()->json(['error' => 'Unauthenticated.'], 401);
        }

        return redirect()->guest(route('login'));
    }
}
