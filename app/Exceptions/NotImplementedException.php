<?php

declare(strict_types=1);

namespace App\Exceptions;

use Exception;
use Throwable;

final class NotImplementedException extends Exception
{
    public function __construct(string $message = 'Not implemented.', int $code = 0, ?Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
