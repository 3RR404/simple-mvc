<?php

namespace App\Interfaces;

interface IResponse 
{
    public function getMessage() : string;
    
    public function getCode() : int;
    
    public function getType() : string;
    
    public function getTitle() : string;
    
}