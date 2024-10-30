<?php
/*
Plugin Name: BPRFT Show recent buddypress forums topics
Plugin URI: http://www.bgextensions.bgvhod.com
Description: Display recent buddypress forums topics
Version: 1.0.0
Author: BgExtensions
Author URI: http://www.bgextensions.bgvhod.com
License: GPL2
*/

/*  Copyright 2012  BgExtensions  (email : bgextensions@bgvhod.com)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

function bprft_init() {
    require( dirname( __FILE__ ) . '/bp-recent-groups-topics.php' );
}
add_action( 'bp_include', 'bprft_init' );

function bprft_locale_init () {
	$plugin_dir = basename(dirname(__FILE__));
	$locale = get_locale();
	$mofile = WP_PLUGIN_DIR . "/bp-recent-groups-topics/languages/bprft-$locale.mo";

      if ( file_exists( $mofile ) )
      		load_textdomain( 'bprft', $mofile );
}
add_action ('plugins_loaded', 'bprft_locale_init');

function add_bprft_stylesheet() {
	    $myStyleUrl = WP_PLUGIN_URL . '/bp-recent-groups-topics/css/bprft.css';
        wp_register_style( 'mybprftSheets', $myStyleUrl );
        wp_enqueue_style( 'mybprftSheets' );
}
add_action('wp_print_styles', 'add_bprft_stylesheet');
?>