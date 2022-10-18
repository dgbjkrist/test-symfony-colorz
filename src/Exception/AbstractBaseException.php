<?php

declare(strict_types=1);

namespace App\Exception;

abstract class AbstractBaseException extends \Exception
{
    protected int $httpStatusCode;

    protected string $codeError;

    /**
     * @var array<mixed>
     */
    protected array $metadatas = [];

    public function __construct(string $message, int $httpStatusCode, string $code)
    {
        parent::__construct($message);
        $this->message = $message;
        $this->httpStatusCode = $httpStatusCode;
        $this->codeError = $code;
    }

    /**
     * @param mixed $value
     */
    public function addMetadatas(string $key, $value): void
    {
        $this->metadatas[$key] = $value;
    }

    /**
     * @return array<mixed>
     */
    public function toArray(): array
    {
        return [
            'code' => $this->codeError,
            'message' => $this->message,
            'metadatas' => $this->metadatas,
        ];
    }

    public function getHttpStatusCode(): int
    {
        return $this->httpStatusCode;
    }
}
