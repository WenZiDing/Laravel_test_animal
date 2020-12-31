<?php

namespace App\Exceptions;


use App\Traits\ApiResponseTrait;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;


class Handler extends ExceptionHandler
{
	use ApiResponseTrait;
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
     * Register the exception handling callbacks for the application.
     *
     * @return void
     */
    public function register()
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }

    public function render($request, Throwable $exception){
        // dd($exception);
        if($request->expectsJson()){
					//Model找不到資源
            if($exception instanceof ModelNotFoundException){
              return $this->errorResponse('找不到資源', Response::HTTP_NOT_FOUND);
						}
						//網址輸入錯誤
						if($exception instanceof NotFoundHttpException){
              return $this->errorResponse('無法找到此網址', Response::HTTP_NOT_FOUND);
						}
						if($exception instanceof MethodNotAllowedHttpException){
							// return $this->errorResponse($exception->getMessage(), Response::HTTP_METHOD_NOT_ALLOWED);
							return $this->errorResponse($exception->getMessage(), Response::HTTP_METHOD_NOT_ALLOWED);
						}
        }

        return parent::render($request, $exception);
    }
}
