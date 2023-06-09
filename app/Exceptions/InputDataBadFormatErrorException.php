<?php
declare(strict_types=1);

namespace App\Exceptions;

use Exception;

class InputDataBadFormatErrorException extends Exception
{
    /**
     * @param string $message
     * @param int $code
     */
    public function __construct(string $message = '', int $code = 400)
    {
        parent::__construct($message, $code);
    }
}
