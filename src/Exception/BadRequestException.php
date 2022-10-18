<?php

declare(strict_types=1);

namespace App\Exception;

use Symfony\Component\HttpFoundation\Response;

class BadRequestException extends AbstractBaseException
{
    private const DEFAULT_ERROR_CODE = 'BAD_REQUEST';

    public function __construct(string $message, string $code = self::DEFAULT_ERROR_CODE)
    {
        parent::__construct($message, Response::HTTP_BAD_REQUEST, $code);
    }
}
