<?php


namespace App\Exceptions;


use Symfony\Component\HttpKernel\Exception\HttpException;

class InputValidationException extends HttpException
{

    /**
     * InputValidationException constructor.
     */
    public function __construct($message)
    {
        parent::__construct(422, $message);
    }
}