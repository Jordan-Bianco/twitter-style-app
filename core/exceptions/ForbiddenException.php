<?php

namespace App\core\exceptions;

use Exception;

class ForbiddenException extends Exception
{
    protected $code = 403;
    protected $message = 'Permission denied';
}
