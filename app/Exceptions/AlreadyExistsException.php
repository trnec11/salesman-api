<?php
declare(strict_types=1);

namespace App\Exceptions;

use Exception;

class AlreadyExistsException extends Exception
{
    /**
     * @param string $message
     * @param int $code
     */
    public function __construct(string $message = '', int $code = 409)
    {
        parent::__construct($message, $code);
    }
}
