<?php

namespace App;


class DNSRemote
{
    /** @var string API_URI */
    const API_URI = 'https://rest.websupport.sk';

    /** @var string */
    protected $ckey;

    /** @var string */
    protected $csecret;

    /** @var string */
    protected $path;

    /** @var string */
    protected $method;
    
    /** @var string */
    protected $response;

    /** @var string */
    protected $data;


    function __construct( string $api_key, string $api_secret )
    {
        $this->ckey = $api_key;
        $this->csecret = $api_secret;
    }

    public function setPath( ?string $path = 'user/self' ) : void
    {
        $this->path = "/v1/$path";
    }

    protected function setMethod( ?string $method = 'GET' ) : void
    {
        $this->method = \strtoupper( $method );
    }

    protected function setData( string $data ) : void
    {
        $this->data = $data;
    }
    
    protected function connect( ?int $time = null ) : self
    {
        $time = $time ?: time();
        $api = 'https://rest.websupport.sk';
        $canonicalRequest = sprintf('%s %s %s', $this->method, $this->path, $time);
        $signature = hash_hmac('sha1', $canonicalRequest, $this->csecret);
        
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, sprintf('%s:%s', $api, $this->path));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
        curl_setopt($ch, CURLOPT_USERPWD, "$this->ckey:$signature");
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Date: ' . gmdate('Ymd\THis\Z', $time),
            'Content-Type: application/json',
        ]);

        if ( $this->data && !empty( $this->data ) ) curl_setopt($ch, CURLOPT_POSTFIELDS, $this->data);;
        
        $this->response = curl_exec($ch);
        curl_close($ch);

        return $this;
    }
    
    public function getJsonResponse() : string
    {
        return $this->response;
    }
    
    /**
     * List all of services
     * @param int $service_id - ID of service
     * @param null|string $domain_name - Name of the domain belong by service id
     */
    public function getService()
    {
        $this->setPath();
        $this->setMethod();

        return $this->connect();
    }

    public function getZones( string $service_id, ?string $domain_name = null ) : self
    {
        $path = $domain_name ? "user/$service_id/zone/$domain_name" : "user/$service_id/zone";
        
        $this->setPath( $path );
        $this->setMethod();

        return $this->connect();
    }

    public function getRecord( string $service_id, ?string $domain_name = null ) : self
    {
        $path = $domain_name ? "user/$service_id/zone/$domain_name/record" : "user/$service_id/zone";
        
        $this->setPath( $path );
        $this->setMethod();

        return $this->connect();
    }

    public function createRecord( string $service_id, string $domain_name, array $fields )
    {
        $path = "user/$service_id/zone/$domain_name/record";

        $data = json_encode( $fields );

        $this->setPath( $path );
        $this->setMethod('post');
        $this->setData( $data );
    }

}