<?php

namespace App\Exceptions;

use App\Extensions\ApiResponseTrait;
use App\Extensions\ResponseEnum;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Throwable;

class Handler extends ExceptionHandler
{
    use ApiResponseTrait;

    /**
     * A list of exception types with their corresponding custom log levels.
     *
     * @var array<class-string<\Throwable>, \Psr\Log\LogLevel::*>
     */
    protected $levels = [
        //
    ];

    /**
     * A list of the exception types that are not reported.
     *
     * @var array<int, class-string<\Throwable>>
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed to the session on validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
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

    public function render($request, Throwable $e)
    {
        // 请求类型错误异常抛出
        if ($e instanceof MethodNotAllowedHttpException) {
            $this->throwBusinessException(ResponseEnum::CLIENT_METHOD_HTTP_TYPE_ERROR);
        }

        // 参数校验错误异常抛出
        if ($e instanceof ValidationException) {
            $errors = $e->errors();
            $firstError = reset($errors);
            $this->throwBusinessException(ResponseEnum::CLIENT_PARAMETER_ERROR, $firstError[0]);
        }

        // 未授权异常抛出
        if ($e instanceof AuthenticationException) {
            $this->throwBusinessException(ResponseEnum::CLIENT_HTTP_UNAUTHORIZED);
        }

        // 自定义错误异常抛出
        if ($e instanceof BusinessException) {
            return response()->json([
                'code' => $e->getCode(),
                'message' => $e->getMessage(),
            ]);
        }

        return parent::render($request, $e);
    }
}
