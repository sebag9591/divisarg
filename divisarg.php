<?php
/**
 * Plugin name: divisARG
 * Plugin URI: https://arcast.tv
 * Description: divisARG - Get information for dollar/euro cotization from external APIs
 * Author: sebag9591
 * Author URI: https://arcast.tv
 * version: 0.1.0
 * License: GPL2 or later.
 * text-domain: divisarg
 */

// If this file is access directly, abort!!!
defined( 'ABSPATH' ) or die( 'Unauthorized Access' );

add_shortcode( 'divisarg', 'divisarg_callback_function' );

function divisarg_callback_function() {

    

    $url = 'https://api.bluelytics.com.ar/v2/latest';
    
    $arguments = array(
        'method' => 'GET'
    );

	$response = wp_remote_get( $url, $arguments );

	if ( is_wp_error( $response ) ) {
		$error_message = $response->get_error_message();
		return "Something went wrong: $error_message";
	} 

    $results = json_decode(wp_remote_retrieve_body( $response ));
    
    //var_dump($results);
    
    $html = '';

    $html .= '<div id="widget-slider">
        <div class="slider">
  
            <a href="#slide-1">USD OF</a>
            <a href="#slide-2">USD BLUE</a>
            <a href="#slide-3">EU OF</a>
            <a href="#slide-4">EU BLUE</a>
        
            <div class="slides">
            <div id="slide-1">
                <h4>DOLAR OFICIAL</h4>
                <div>
                    <table>
                        <tr>
                            <td>COMPRA</td>
                            <td>VENTA</td>
                        </tr>
                        
                        <tr>
                            <td>$'.$results->oficial->value_buy.'</td>
                            <td>$'.$results->oficial->value_sell.'</td>
                        </tr>
                    </table>
                </div>
            </div>
            <div id="slide-2">
                <h4>DOLAR BLUE</h4>
                <div>
                    <table>
                        <tr>
                            <td>COMPRA</td>
                            <td>VENTA</td>
                        </tr>
                        
                        <tr>
                            <td>$'.$results->blue->value_buy.'</td>
                            <td>$'.$results->blue->value_sell.'</td>
                        </tr>
                    </table>
                </div>
            </div>
            <div id="slide-3">
                <h4>EURO OFICIAL</h4>
                <div>
                    <table>
                        <tr>
                            <td>COMPRA</td>
                            <td>VENTA</td>
                        </tr>
                        
                        <tr>
                            <td>$'.$results->oficial_euro->value_buy.'</td>
                            <td>$'.$results->oficial_euro->value_sell.'</td>
                        </tr>
                    </table>
                </div>
            </div>
            <div id="slide-4">
                <h4>EURO BLUE</h4>
                <div>
                    <table>
                        <tr>
                            <td>COMPRA</td>
                            <td>VENTA</td>
                        </tr>
                        
                        <tr>
                            <td>$'.$results->blue_euro->value_buy.'</td>
                            <td>$'.$results->blue_euro->value_sell.'</td>
                        </tr>
                    </table>
                </div>
            </div>
            </div>';
    date_default_timezone_set ('America/Argentina/Buenos_Aires');
    $html .= '<center>';
    $html .= '<small>Última actualización: '. date("d/m/Y H:i:s", strtotime($results->last_update))  .'</small>';
    $html .= '</center>';
    $html .= '</div>';
    $html .= '</div>';
    
    return $html;
    
}	

add_action('wp_enqueue_scripts', 'divisarg_callback_for_setting_up_scripts');
function divisarg_callback_for_setting_up_scripts() {
    wp_enqueue_script('divisarg', plugins_url('/js/divisarg.js', __FILE__), array('jquery'), '1.2.3', true);
    wp_enqueue_style( 'divisarg', plugins_url('/css/divisarg.css', __FILE__), false, '1.0.0', 'all');
}