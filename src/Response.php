<?php

namespace App;

use App\Interfaces\IResponse;

class Response implements IResponse
{
    /** @var string */
    protected $message;

    /** @var int */
    protected $code;
    
    public function __construct( string $message, int $code )
    {
        $this->message = $message;
        $this->code = $code;
    }

    public function getMessage() : string
    {
        return $this->message;
    }

    public function getCode() : int
    {
        return $this->code;
    }

    public function getType() : string
    {
        switch( $this->code )
        {
            case 400 : 
            case 401 : 
            case 403 : 
            case 404 : 
            case 405 : 
            case 409 : 
            case 422 : 
            case 500 : 
            case 503 : 
            case 504 : 
            case 505 : 
                return 'error';
            break;

            case 200 : 
            case 201 :
            case 202 : return 'success'; break;
        }
    }

    public function getTitle() : string
    {
        switch( $this->code )
        {
            case 400 : return 'Bad Request'; break;
            case 401 : return 'Incorrect api key or signature'; break;
            case 404 : return 'Forbidden'; break;
            case 404 : return 'Not Found'; break;
            case 405 : return 'Method Not Allowed'; break;
            case 409 : return 'Conflict'; break;
            case 422 : return 'Unprocessable Entity'; break;
            case 500 : return 'Internal Server Error'; break;
            case 503 : return 'Service Unavailable'; break;
            case 504 : return 'Gateway Timeout'; break;
            case 505 : return 'HTTP Version Not Supported'; break;
            case 200 : return 'Ok'; break;
            case 201 : return 'Created'; break;
            case 201 : return 'Accepted'; break;
        }
    }
}