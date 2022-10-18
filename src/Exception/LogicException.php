<?php

declare(strict_types=1);

namespace App\Exception;

use Symfony\Component\HttpFoundation\Response;

class LogicException extends AbstractBaseException
{
    private const DEFAULT_ERROR_CODE = 'LOGIC_EXCEPTION';

    public function __construct(string $message, string $code = self::DEFAULT_ERROR_CODE)
    {
        parent::__construct($message, Response::HTTP_INTERNAL_SERVER_ERROR, $code);
    }
}
