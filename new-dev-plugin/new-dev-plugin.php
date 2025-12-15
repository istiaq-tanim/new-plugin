<?php

/** 
 * Plugin Name: New Dev Plugin
 * Description: My First Plugin
 * Author: Kazi Istiaq Mahamud
 * Version : 1.0.0
 * Author URI: https://portfolio-client-sable.vercel.app/
 * Text Domain: new-dev-plugin
 * Domain Path: /languages
 * License:GPL v2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 */


add_action("admin_notices","ndp_wp_success");

function ndp_wp_success(){
  ?>

  <div class='notice notice-success'>
     <?php echo "Hello From Simple Wp" ?>
  </div>

  <?php
}

add_filter("plugin_row_meta","ndp_wp_add_link_to_plugin" , 10 ,4);

function ndp_wp_add_link_to_plugin($links_array, $plugin_file_name, $plugin_data, $status ){
   if(strpos( $plugin_file_name, basename(__FILE__) )){
      $links_array[]= '<a href="#" >FAQ</a>';  
       
      $links_array[]= '<a href="#">Support</a>';
   }
   return $links_array;
}

register_activation_hook(__FILE__,"swp_activation");

function swp_activation ()
{
  error_log("Plugin Activated");
}

register_deactivation_hook(__FILE__,"swp_deactivation");


function swp_deactivation (){
  error_log("Plugin Deactivate");
}

