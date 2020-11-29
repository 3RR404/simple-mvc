<?php

namespace App;

/**
 * View-specific wrapper.
 * Limits the accessible scope available to templates.
 */
class View{
    /**
     * Template being rendered.
     */
    protected $template = null;

    /** @var object */
    protected $data = null;

    /**
     * Initialize a new view context.
     */
    public function __construct( $template, $data )
    {
        $this->template = $template;
        $this->data = $data;
    }

    public function render() : void
    {

        if ( $this->data )
        {
            extract( (array)$this->data );
        }

        ob_start();

        include( APP_DIR . DIRECTORY_SEPARATOR . "$this->template");
        
        $content = ob_get_contents();
        
        ob_end_clean();
        
        echo $content;
    }

    // public function render( ?object $data = null ) : void
    // {
    //     // APP_DIR . DIRECTORY_SEPARATOR . "$this->template";
    // }
}
