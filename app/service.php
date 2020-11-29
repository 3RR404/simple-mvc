<?php

use App\Response;
use App\Route;

class Service extends Base
{

    public function default() : void
    {
        $this->setTitle('Service');

        $this->template->zones_data = $this->DNSresponse->getZones( $this->services->id );

    }

    public function detail( $id ) : void
    {
        $response = $this->DNSresponse->getZones( $this->services->id, $id );
        $api_response = $this->DNSresponse->getRecord( $this->services->id, $id );

        if ( isset($response->code) )
        {
            switch( $response->code )
            {
                case 404: 
                    (new Route)->err404();
                break;
                case 500 :
                    (new Route)->err500();
                break;
            }
        }
        else
        {
            $this->template->service_id = $id;

            $this->template->records = @$api_response->items ? $api_response->items : [];
        }
    }
    
    public function create( $service_id )
    {
        $this->setTitle( 'Create new record' );
        
        $this->template->service_id = $service_id;
    }

    public function edit( $id )
    {
        echo "<h1>Edit $id record</h1>";
    }

    public function insert()
    {
        if ( @$_POST )
        {
            $type = $_POST['type'];
            $id = $_POST['service_id'];

            $response = null;

            $name = filter_input( INPUT_POST, 'name', FILTER_SANITIZE_STRING );
                if ( !$name )
                    $response = new Response('Bad value for name input !', 422);

            $ttl = filter_input( INPUT_POST, 'ttl', FILTER_VALIDATE_INT );
            if ( !$ttl )
                $ttl = 600;

            if ( $type === 'A' )
            {
                $content = filter_var( $_POST['content'], FILTER_VALIDATE_IP, FILTER_FLAG_IPV4 );
                if( !$content )
                    $response = new Response( 'Bad Value for IP', 422 );

                if ( !$response )
                {
                    $api_connect = $this->DNSresponse->createRecord( $this->services->id, $id, [
                        'type' => $type,
                        'name' => $name,
                        'content' => $content,
                        'ttl' => $ttl
                    ]);

                    $api_response = $api_connect->getResponse();
                    if ( $api_response->status === 'success' ) 
                        $response = new Response( 'All done !', 201 );

                    if ( $api_response->status === 'error' )
                        $response = $api_response->errors;

                }
            }
            else if ( $type === 'AAAA' )
            {

                $content = filter_var( $_POST['content'], FILTER_VALIDATE_IP, FILTER_FLAG_IPV6 );
                if( !$content )
                    $response = new Response( 'Bad Value for IP', 422 );

                if ( !$response )
                {
                    $api_connect = $this->DNSresponse->createRecord( $this->services->id, $id, [
                        'type' => $type,
                        'name' => $name,
                        'content' => $content,
                        'ttl' => $ttl
                    ]);

                    $api_response = $api_connect->getResponse();
                    if ( $api_response->status === 'success' ) 
                        $response = new Response( 'All done !', 201 );

                    if ( $api_response->status === 'error' )
                        $response = $api_response->errors;

                }
            }
            else if ( $type === 'MX' )
            {

                $content = filter_var( $_POST['content'], FILTER_SANITIZE_URL );
                if( !$content )
                    $response = new Response( 'Bad Value for domain name', 422 );

                $prior = filter_var( $_POST['prio'], FILTER_SANITIZE_NUMBER_INT );
                if ( !$prior )
                    $response = new Response( 'Priority is required', 422 );

                if ( !$response )
                {
                    $api_connect = $this->DNSresponse->createRecord( $this->services->id, $id, [
                        'type' => $type,
                        'name' => $name,
                        'content' => $content,
                        'prio' => $prior,
                        'ttl' => $ttl
                    ]);

                    $api_response = $api_connect->getResponse();
                    if ( $api_response->status === 'success' ) 
                        $response = new Response( 'All done !', 201 );

                    if ( $api_response->status === 'error' )
                        $response = $api_response->errors;
                }
            }
            else if ( $type === 'ANAME' || $type === 'CNAME' )
            {
                $content = filter_var( $_POST['content'], FILTER_SANITIZE_STRING );
                if( !$content )
                    $response = new Response( 'Bad Value for domain name', 422 );

                if ( !$response )
                {
                    $api_connect = $this->DNSresponse->createRecord( $this->services->id, $id, [
                        'type' => $type,
                        'name' => $name,
                        'content' => $content,
                        'ttl' => $ttl
                    ]);

                    $api_response = $api_connect->getResponse();
                    if ( $api_response->status === 'success' ) 
                        $response = new Response( 'All done !', 201 );

                    if ( $api_response->status === 'error' )
                        $response = $api_response->errors;

                }
            }
            else if ( $type === 'TXT' )
            {
                $content = filter_var( $_POST['content'], FILTER_SANITIZE_STRING );
                if( !$content )
                    $response = new Response( 'Bad Value for domain name', 422 );

                if ( !$response )
                {
                    $api_connect = $this->DNSresponse->createRecord( $this->services->id, $id, [
                        'type' => $type,
                        'name' => $name,
                        'content' => $content,
                        'ttl' => $ttl
                    ]);

                    $api_response = $api_connect->getResponse();
                    if ( $api_response->status === 'success' ) 
                        $response = new Response( 'All done !', 201 );

                    if ( $api_response->status === 'error' )
                        $response = $api_response->errors;

                }
            }
            else if ( $type === 'SRV' )
            {
                $content = filter_var( $_POST['content'], FILTER_SANITIZE_STRING );
                if( !$content )
                    $response = new Response( 'Bad Value for domain name', 422 );

                $prior = filter_var( $_POST['prio'], FILTER_SANITIZE_NUMBER_INT );
                if ( !$prior )
                    $response = new Response( 'Priority is required', 422 );

                $port = filter_var( $_POST['port'], FILTER_SANITIZE_NUMBER_INT );
                if ( !$port )
                    $response = new Response( 'Port is required', 422 );

                $weight = filter_var( $_POST['weight'], FILTER_SANITIZE_NUMBER_INT );
                if ( !$weight )
                    $response = new Response( 'Weight is required', 422 );

                if ( !$response )
                {
                    $api_connect = $this->DNSresponse->createRecord( $this->services->id, $id, [
                        'type' => $type,
                        'name' => $name,
                        'content' => $content,
                        'prior' => $prior,
                        'port' => $port,
                        'weight' => $weight,
                        'ttl' => $ttl
                    ]);

                    $api_response = $api_connect->getResponse();
                    if ( $api_response->status === 'success' ) 
                        $response = new Response( 'All done !', 201 );

                    if ( $api_response->status === 'error' )
                        $response = $api_response->errors;

                }
            }

            if ( $response )
                $this->returnJsonResponse( $response );
        }
        else exit( 'Somethin\'s really wrong !' );
    }

    public function delete()
    {
        if ( @$_POST )
        {
            $domain_name = $_POST['service_id'];
            $id = $_POST['record_id'];
            
            $api_response = $this->DNSresponse->deleteRecord( $this->services->id, $domain_name, $id )->getResponse();

            if ( @$api_response->message )
                $api_response = new Response( $api_response->message, $api_response->code );
            
            if ( $api_response->status === 'success' )
            {
                header('Location: ' . Route::getUri()->root . "{$this->params->controller}/$domain_name" );
            }

            return $this->returnJsonResponse( $api_response );
        }
    }

    public function changeType()
    {
        if ( @$_POST['type'] )
        {
            $this->template->record_type = $_POST['type'];
            $this->template->service_id = $_POST['service_id'];
        }
        else exit('Sorry !');
    }

}