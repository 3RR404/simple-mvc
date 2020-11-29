<?php
namespace App;

class Route
{
    private static $routes = [];
    private static $pathNotFound = null;
    private static $methodNotAllowed = null;
    private static $params = [];
    private static $root;

    public static function add( string $path, array $actions )
    {
        self::$routes[$path] = $actions;
    }

    public static function getParams()
    {
        return self::$params;
    }

    public function getRoutes()
    {
        return self::$routes;
    }

    public static function pathNotFound($function)
    {
        self::$pathNotFound = $function;
    }

    public static function methodNotAllowed($function)
    {
        self::$methodNotAllowed = $function;
    }

    public static function getUri()
    {
        return (object) [
            'root' => self::$root
        ];
    }

    private static function matchItem( $uriItems, $maskItems, $defaults )
    {
        $i = 0;
        $values = [];

        foreach($maskItems as $maskItem)
        {

            if(strpos($maskItem, ':') === 0) {
                $key = substr($maskItem, 1);

                if(isset($uriItems[$i]) && !empty($uriItems[$i])) {
                    $values[$key] = $uriItems[$i];
                } else {
                    if(is_null($defaults) || !isset($defaults[$key])) {
                        return false;
                    } else {
                        $values[$key] = $defaults[$key];
                    }
                }
            } elseif(!isset($uriItems[$i]) || $maskItem !== $uriItems[$i]) {
                return false;
            }

            $i++;
        }

        return array_merge($defaults, $values);
    }

    public static function run( $basepath = '/' )
    {
        $subFolder = dirname($_SERVER['PHP_SELF']);

        $subFolder == "\\" || $subFolder == "/" ? '' : "$subFolder";

        $protocolDetect = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off') ? 'https' : 'http';
        $protocol = isset($_SERVER['HTTP_X_FORWARDED_PROTO']) ? $_SERVER['HTTP_X_FORWARDED_PROTO'] : $protocolDetect;
        self::$root = "$protocol://{$_SERVER['HTTP_HOST']}$subFolder";
        
        // Parse current url
        $parsed_url = parse_url( self::$root . substr( $_SERVER['REQUEST_URI'], strlen($subFolder) ) ); //Parse Uri

        $path = substr( $parsed_url['path'], strlen($subFolder) );

        $params = explode( '/', $path );

        self::$params = (object)[
            'controller' => @$params[0] ? $params[0] : 'default',
            'action' => @$params[1] ? $params[1] : 'default',
            'id' => @$params[2] ? $params[2] : (@$params[1] ? $params[1] : null)
        ];


        $pos = strpos( $path, '?' );
        $uriItems = explode('/', $pos === false ? $path : substr($path, 0, $pos));
        $count = count($uriItems);

        // Get current request method
        $method = $_SERVER['REQUEST_METHOD'];

        $path_match_found = false;

        $route_match_found = false;

        foreach (self::$routes as $mask => $route)
        {
            $maskItems = explode('/', $mask);

            if(count($maskItems) < $count) {
                continue;
            }
            
            $values = self::matchItem($uriItems, $maskItems, $route);

            if($values !== false) {
                return $values;
            }
        }

        $path_match_found = true;
        $route_match_found = true;
        

        // No matching route was found
        if (!$route_match_found) {

            // But a matching path exists
            if ($path_match_found) {
                header("HTTP/1.0 405 Method Not Allowed");
                if (self::$methodNotAllowed) {
                    call_user_func_array(self::$methodNotAllowed, array($path, $method));
                }
            } else {
                header("HTTP/1.0 404 Not Found");
                if (self::$pathNotFound) {
                    call_user_func_array(self::$pathNotFound, array($path));
                }
            }
        }
    }

    public function err404()
    {
        self::$params = (object)[
            'controller' => 'errors',
            'action' => '__404',
            'id' => null
        ];

    }

    public function err500()
    {
        self::$params = (object)[
            'controller' => 'errors',
            'action' => '__500',
            'id' => null
        ];

    }
}
