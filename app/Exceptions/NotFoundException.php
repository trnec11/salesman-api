<?php
declare(strict_types=1);

namespace App\Exceptions;

use Exception;

/**
 * Class NotFoundException
 * @package App\Exceptions
 */
class NotFoundException extends Exception
{
    /**
     * @param string $message
     * @param int $code
     */
    public function __construct(string $message = '', int $code = 404)
    {
        parent::__construct($message, $code);
    }
}
