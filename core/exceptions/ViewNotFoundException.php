<?php

namespace App\core\exceptions;

use Exception;

class ViewNotFoundException extends Exception
{
    protected $code = 404;
}