<?php

use App\Route;

class Homepage extends Base
{

    public function startup() : void
    {
        parent::startup();

        $zones_data = \json_decode( $this->response->getZones( $this->services->id )->getJsonResponse() );
        $this->template->zones_data = $zones_data->items;
    }

    public function default() {}
}
