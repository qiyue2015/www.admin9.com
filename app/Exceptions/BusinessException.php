<?php

namespace App\Exceptions;

use Exception;

class BusinessException extends Exception
{
    /**
     * 业务异常构造函数
     * @param array $codeResponse
     * @param string $customInfo
     */
    public function __construct(array $codeResponse, $customInfo = '')
    {
        [$code, $message] = $codeResponse;
        parent::__construct($customInfo ?: $message, $code);
    }
}
