<?php

namespace App;

class Application
{
    public function start( $target )
    {

        $controller_name = $target->controller === 'default' ? 'base' : $target->controller;

        if ( file_exists( APP_ROOT . "/app/$controller_name.php" ) )
        {
            require_once APP_ROOT . "/app/base.php";

            if ( $controller_name === 'base' ){
                $controller_name = 'homepage';
                $target->controller = 'homepage';
            }

            require_once APP_ROOT . "/app/$controller_name.php";

            $controller_class = \ucfirst( $controller_name );
            $controller = new $controller_class( $target );

        }
        else throw new \Exception( "Class or file $controller_name does't exists !" );
    }
}