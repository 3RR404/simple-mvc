<?php

use App\Route;

class Homepage extends Base
{

    public function startup() : void
    {
        parent::startup();

        $zones_data = $this->DNSresponse->getZones( $this->services->id )->getResponse();
        $this->template->zones_data = $zones_data->items;
    }

    public function default() : void {}
}
