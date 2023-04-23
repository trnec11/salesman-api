<?php
declare(strict_types=1);

namespace App\Exceptions;

use Exception;

class ForbiddenErrorException extends Exception
{
    /**
     * @param string $message
     * @param int $code
     */
    public function __construct(string $message = '', int $code = 403)
    {
        parent::__construct($message, $code);
    }
}
