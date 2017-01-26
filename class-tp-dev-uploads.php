<?php
/**
 * Plugin Name: Development Kitten Uploads
 * Description: Placeholder images of kittens for development and staging environments.
 *
 * Plugin URI: https://github.com/marthesselink/dev-uploads
 *
 * Author: Mart Hesselink
 * Author URI: https://github.com/marthesselink
 *
 * Version: 1.0.6
 */

class TP_Dev_Uploads {

    function __construct() {
        if( defined( 'WP_ENV' ) )
          add_filter( 'mod_rewrite_rules', array( $this, 'placehold' ) );
    }

    /**
     * Redirect images from uploads to placehold.it on develop and release environments if they don't exist
     *
     * @param  string $rules WordPress' own rules
     * @return string        New rules
     */
    function placehold( $rules ) {
        if( 'development' == WP_ENV || 'staging' == WP_ENV ) {
            $dir = wp_upload_dir();

            $uploads_rel_path = str_replace( trailingslashit( home_url() ), '', $dir['baseurl'] );

            $tp_images_rules = array(
                '',
                '# BEGIN TP Development uploads',
                'RewriteCond %{REQUEST_FILENAME} !-f',
                'RewriteRule ^' . $uploads_rel_path . '/(.*)-([0-9]+)x([0-9]+).(gif|jpe?g|png|bmp)$ http://www.placekitten.com/$2x$3 [NC,L]',
                'RewriteCond %{REQUEST_FILENAME} !-f',
                'RewriteRule ^' . $uploads_rel_path . '/(.*)(gif|jpe?g|png|bmp)$ http://www.placekitten.com/600x600 [NC,L]',
                '# END TP Development uploads',
                '',
            );

            $rules = explode( "\n", $rules );
            $rules = wp_parse_args( $rules, $tp_images_rules );
            $rules = implode( "\n", $rules );
        }

        return $rules;
    }

} new TP_Dev_Uploads;
