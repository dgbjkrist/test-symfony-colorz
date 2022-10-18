<?php

declare(strict_types=1);

namespace App\Exception;

use Symfony\Component\HttpFoundation\Response;

class UnexpectedException extends AbstractBaseException
{
    private const DEFAULT_STATUS_CODE = Response::HTTP_INTERNAL_SERVER_ERROR;

    private const DEFAULT_ERROR_CODE = 'UNEXPECTED_ERROR';

    public const UNEXPECTED_HTTP_ERROR = 'UNEXPECTED_HTTP_ERROR';

    public function __construct(string $message, int $httpStatusCode = self::DEFAULT_STATUS_CODE, string $code = self::DEFAULT_ERROR_CODE)
    {
        parent::__construct($message, $httpStatusCode, $code);
    }
}
