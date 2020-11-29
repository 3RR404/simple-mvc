<?php

use App\Controller;
use App\DNSRemote;

class Base extends Controller
{

    /** @var DNSRemote $DNSresponse */
    public $DNSresponse;

    /** @var object */
    public $service;

    public function startup() : void
    {
        parent::startup();

        // // $this->DNSresponse = new DNSRemote( 'xxx', 'xxx' );
        $this->services = $this->DNSresponse->getService()->getResponse();

        $this->template->title = @$this->title ? "{$this->title} - Websupport Api | Error404 App" : "Websupport Api | Error404 App";

    }
}