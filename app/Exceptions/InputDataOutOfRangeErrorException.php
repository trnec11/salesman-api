<?php
declare(strict_types=1);

namespace App\Exceptions;

use Exception;

class InputDataOutOfRangeErrorException extends Exception
{
    /**
     * @param string $message
     * @param int $code
     */
    public function __construct(string $message = '', int $code = 416)
    {
        parent::__construct($message, $code);
    }
}
