<?php


namespace App\Exceptions;


use Exception;

class DBException extends Exception
{
    protected $message = "";


    /**
     * @return string message
     */
    public function getFailedConnectionMessage()
    {
        return "Error on line " . $this->getLine() . " in " . $this->getFile()
        . ": Connection failed" . $this->getMessage();
    }
}