<?php

use App\Controller;

class Errors extends Base
{
    function __404()
    {
        header("HTTP/1.0 404 Not Found");
        
        echo "404 Not Found";
        
    }
    
    function __500()
    {
        header("HTTP/1.0 500 Server error");
        
        echo "500 Server error";
        
    }
}
