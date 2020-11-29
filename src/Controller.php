<?php

namespace App;

require "View.php";

use App\View;

class Controller
{
    /** @var Controller */
    protected $controller;

    protected $params;

    protected $target;

    public $baseLayout;

    /** @var object */
    public $template;

    /** @var object */
    public $layout;

    /** @var View */
    public $view;

    /** @var string */
    public $title;

    function __get($name) {
        switch ($name) {
            case "controller":  return $this->controller;
            case "params":      return $this->params;
            case "template":    return $this->template;
            default: throw new \Exception($name);
        }
    }

    protected function getParams() { return $this->params; }

    protected function startup() : void {}

    protected function default() : void {}

    protected function detail( string $id ) : void {}

    protected function setTitle( string $title ) : void
    {
        $this->title = $title;

        $this->template->title = @$title ? "{$title} - Websupport Api | Error404 App" : "Websupport Api | Error404 App";
    }

    function __construct( $target )
    {
        $this->target = $target;

        $this->params = $target;

        $action = $target->action;
        $actionArg1 = $target->id;
        $actionArg2 = $target->action;
        
        $this->controller = $this;

        $this->template = (object)[];

        if ( !method_exists( $this, $action ) && method_exists( $this, 'detail' ) )
        {
            $action = 'detail';
            $actionArg1 = $target->action;
            $actionArg2 = $target->id;

        }

        $this->view = new View( "views/{$this->target->controller}/{$action}.phtml", $this->template );

        $base_layout = new View( "views/base.phtml", $this->template );
        
        $this->startup();

        $this->{$action}($actionArg1, $actionArg2);
        
        $this->template->content = $this->view;
        $this->template->basePath = Route::getUri()->root;
        $this->template->params = Route::getParams();
        
        $base_layout->render( $this->template );

    }

    public function returnJsonResponse( $response )
    {
        header('Content-Type: application/json');
        
        if ( $response instanceof Response )
            $result = json_encode([
                'type' => $response->getType(),
                'title' => $response->getTitle(),
                'message' => $response->getMessage()
            ]);
        
        else $result = json_encode( $response );

        echo $result;
        exit;
    }
}