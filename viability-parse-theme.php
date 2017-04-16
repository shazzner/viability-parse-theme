<?php
/**
   Plugin Name: Viability Parse Theme
   Plugin URI: https://getviability.com/
   Description: Allows you to switch a theme based on url.
   Author: Chris Hardee <shazzner@gmail.com>
   Version: 1.0.0
   Stable tag: 3.1.0
   License: GPL2+
   Text Domain: viability-parse-theme
 */

class Viability_Parse_Theme {

    public $theme_list;
    public $refresh;

    // Constructor
    public function __construct() {

        add_filter( 'query_vars', array( $this, 'add_url_theme_filter' ) );
        add_action( 'parse_request', array( $this, 'url_to_theme' ) );

        $this->theme_list = array_keys( wp_get_themes( array( 'errors' => false, 'allowed' => null ) ) );

        $this->refresh = false;
    }

    // This checks if ?theme=<theme stylesheet> is in the url
    public function url_to_theme( $query ) {

        if ( @$query->query_vars[ 'theme' ] ) {

            $theme = esc_attr( (string) $query->query_vars[ 'theme' ] );

            if ( in_array( $theme, $this->theme_list ) && ! is_admin() ) {

                if ( $theme !== wp_get_theme()->stylesheet ) {
			        switch_theme($theme);

                    // Kind of a bad hack, refreshes after a second.
                    // Works but looks bad. Don't think it'll be an issue
                    // when working with all the same framework.
                    if ( $this->refresh ) {
                        echo '<META HTTP-EQUIV="REFRESH" CONTENT="1">' ;
                    }
                }
            }
            
        }
        
    }

    // This adds theme to the list in $query_vars
    public function add_url_theme_filter ( $vars ) {
        $vars[] = "theme";
        return $vars;
    }
}
new Viability_Parse_Theme();
