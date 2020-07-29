<?php

namespace App\Exceptions;

class Exception extends \Exception
{
    public function render($request)
    {
        flash($this->getMessage())->error();
        return redirect()->back();
    }
}
