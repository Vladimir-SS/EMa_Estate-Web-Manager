<?php
class InternalException extends Exception
{
    protected $message = "Internal server error: ";
    protected $code = 500;

    public function __construct($errors)
    {
        $this->message .= $errors;
    }
}
