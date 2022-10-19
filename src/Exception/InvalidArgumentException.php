<?php

declare(strict_types=1);

namespace App\Exception;

use Symfony\Component\HttpFoundation\Response;

class InvalidArgumentException extends AbstractBaseException
{
    private const DEFAULT_STATUS_CODE = Response::HTTP_INTERNAL_SERVER_ERROR;
    private const DEFAULT_ERROR_CODE = 'INVALID_ARGUMENT_EXCEPTION';

    public function __construct(string $message, string $code = self::DEFAULT_ERROR_CODE)
    {
        parent::__construct($message, self::DEFAULT_STATUS_CODE, $code);
    }
}
