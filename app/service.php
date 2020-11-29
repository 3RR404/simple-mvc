<?php

use App\Route;

class Service extends Base
{
    public function default() : void
    {
        $this->setTitle('Service');

        $this->template->zones_data = \json_decode( $this->response->getZones( $this->services->id )->getJsonResponse() );

    }

    public function detail( $id ) : void
    {

        $response = \json_decode( $this->response->getZones( $this->services->id, $id )->getJsonResponse() );

        
        if ( isset($response->code) && $response->code === 404 )
        {
            (new Route)->err404();
        }
        else if ( isset($response->code) && $response->code === 500 )
        {
            (new Route)->err500();
        }
        else
        {
            $this->template->records = \json_decode( $this->response->getRecord( $this->services->id, $id )->getJsonResponse() );
        }
    }

    public function create()
    {
        $this->setTitle( 'Create new record' );
    }

    public function edit( $id )
    {
        echo "<h1>Edit $id record</h1>";
    }

    public function test()
    {
        var_dump( $_POST );

        exit;
    }

}