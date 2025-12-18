<?php

/*
* Plugin Name:simple plugin
* Version: 1.0.0
* Description: Simple Plugin for new Comer.
* Author: Kazi Istiaq Mahmaud
* Author URI: https://portfolio-client-sable.vercel.app/
* License:GPL v2 or later
* License URI: https://www.gnu.org/licenses/gpl-2.0.html
* Text Domain: simple-plugin
*/


define("PLUGIN_DIR_PATH", plugin_dir_path(__FILE__));
define("PLUGIN_DIR_URL", plugin_dir_url(__FILE__));
define("PLUGIN_VERSION", "1.0.0");


//OOP 

class Simple_plugin
{
  public function __construct()
  {
    add_action("admin_menu", [$this, "sp_register_menu"]);
    add_action("admin_enqueue_scripts", [$this, "load_plugin_scripts"]);
    add_action("add_meta_boxes", [$this, "meta_box"]);
    add_action("save_post", [$this, "save_meta_box"]);
    add_filter("the_title", [$this, "update_title"]);
  }

  public function sp_register_menu()
  {
    add_menu_page(
      "Custom Menu",
      "Custom Menu",
      "manage_options",
      "custom-menu",
      [$this, "custom_menu_func"],
      "dashicons-welcome-widgets-menus",
      21
    );
    add_submenu_page(
      "custom-menu",
      "Custom Submenu",
      "Custom Submenu",
      "manage_options",
      "custom-submenu",
      [$this, "custom_submenu_func"]
    );
    add_submenu_page(
      "custom_menu",
      "Custom Submenu 2",
      "Custom Submenu 2",
      "manage_options",
      "custom-submenu2",
      [$this, "custom_submenu_func2"]
    );
  }

  public function custom_menu_func()
  {
    echo "Hello from custom menu bar";
  }

  public function custom_submenu_func()
  {
    include_once PLUGIN_DIR_PATH . "/views/submenu.php";
  }

  public function custom_submenu_func2()
  {
    echo "Hello from custom submenu 2";
  }

  public function load_plugin_scripts()
  {
    wp_enqueue_style(
      "custom-style",
      PLUGIN_DIR_URL . "assets/style.css",
      array(),
      PLUGIN_VERSION
    );
    wp_enqueue_script(
      "custom-script",
      PLUGIN_DIR_URL . "assets/script.js",
      array("jquery"),
      PLUGIN_VERSION,
      true
    );
  }

  public function meta_box()
  {
    add_meta_box("meta-box-id", "Custom Meta Box", "meta_box_func", "post", "side", "high");
  }

  public function meta_box_func($post)
  {
    $wp_meta_val = get_post_meta($post->ID, "user_name", true);
?>
    <div>
      <label for='user_name'>Name</label>
      <input type='text' name='user_name' value='<?php echo $wp_meta_val ?>' />
    </div>
  <?php
  }

  public function  save_meta_box($post_id)
  {
    if (isset($_POST["user_name"])) {
      update_post_meta($post_id, "user_name", $_POST["user_name"]);
    }
  }

  public function update_title($title)
  {
    $meta_value = get_post_meta(get_the_ID(), "user_name", true);

    return $title . " " . $meta_value;
  }
}

new Simple_plugin();


// Add Menu Item In Admin Sidebar

// add_action("admin_menu", "sp_register_menu");


// function sp_register_menu()
// {
//   add_menu_page(
//     "Custom Menu",
//     "Custom Menu",
//     "manage_options",
//     "custom-menu",
//     "custom_menu_func",
//     "dashicons-welcome-widgets-menus",
//     21
//   );
//   add_submenu_page(
//     "custom-menu",
//     "Custom Submenu",
//     "Custom Submenu",
//     "manage_options",
//     "custom-submenu",
//     "custom_submenu_func"
//   );
//   add_submenu_page(
//     "options-general.php",
//     "Custom Submenu 2",
//     "Custom Submenu 2",
//     "manage_options",
//     "custom-submenu2",
//     "custom_submenu_func2"
//   );
// }

// function custom_menu_func()
// {
//   echo "Hello from custom menu bar";
// }



//Add Custom Style File and Script File, when first time load

// add_action("admin_enqueue_scripts", "load_plugin_scripts");


// function load_plugin_scripts()
// {
//   wp_enqueue_style(
//     "custom-style",
//     PLUGIN_DIR_URL . "assets/style.css",
//     array(),
//     PLUGIN_VERSION
//   );
//   wp_enqueue_script(
//     "custom-script",
//     PLUGIN_DIR_URL . "assets/script.js",
//     array("jquery"),
//     PLUGIN_VERSION,
//     true
//   );
// }

// Admin Bar a menu Added


add_action("admin_bar_menu", "admin_bar_func");

function admin_bar_func($wp_admin_bar)
{
  $arg = array(
    "id" => "smart-coder",
    "title" => "Smart Coder",
    "href" => "https://portfolio-client-sable.vercel.app/",
    "meta" => array(
      "class" => "smart-coder",
      "target" => "_blank"
    )
  );
  $wp_admin_bar->add_node($arg);

  $submenu = array(
    "id" => "smart-coder-youtube",
    "title" => "Youtube",
    "href" => "https://www.youtube.com/watch?v=hXaG9w_wXwc&list=PL-OcaXZwrwuIXqm9giNO4mELsn4icxT6U&index=20",
    "parent" => "smart-coder",
    "meta" => array(
      "class" => "smart-coder",
      "target" => "_blank"
    )
  );
  $wp_admin_bar->add_node($submenu);

  $submenu1 = array(
    "id" => "smart-coder-youtube",
    "title" => "",
    "href" => "https://www.youtube.com/watch?v=hXaG9w_wXwc&list=PL-OcaXZwrwuIXqm9giNO4mELsn4icxT6U&index=20",
    "parent" => "smart-coder",
    "meta" => array(
      "class" => "smart-coder",
      "target" => "_blank"
    )
  );
  $wp_admin_bar->add_node($submenu1);
}

// Add Notice

add_action("admin_notices", "add_notices");

function add_notices()
{
  ?>
  <div class='notice notice-success is-dismissible'>
    <p>Add Custom Notice</p>
  </div>
<?php
}


// Add Meta Box

// add_action("add_meta_boxes", "meta_box");

// function meta_box()
// {
//   add_meta_box("meta-box-id", "Custom Meta Box", "meta_box_func", "post", "side", "high");
// }


function meta_box_func($post)
{
  $wp_meta_val = get_post_meta($post->ID, "user_name", true);
?>
  <div>
    <label for='user_name'>Name</label>
    <input type='text' name='user_name' value='<?php echo $wp_meta_val ?>' />
  </div>
<?php
}

//save meta value with save post hook

// add_action("save_post", "save_meta_box");

// function save_meta_box($post_id)
// {
//   if (isset($_POST["user_name"])) {
//     update_post_meta($post_id, "user_name", $_POST["user_name"]);
//   }
// }


// add_filter("the_title", "update_title");

// function update_title($title)
// {
//   $meta_value = get_post_meta(get_the_ID(), "user_name", true);

//   return $title . " " . $meta_value;
// }

//Register Post Type

add_action("init", "custom_post_type");

function custom_post_type()
{
  $args = array(
    "label" => "Books",
    "public" => true
  );
  register_post_type("book", $args);
}

// Show Item in CUstom Column

add_filter("manage_book_posts_columns", "add_custom_columns");

function add_custom_columns($columns)
{
  return array(
    'cb' => '<input type="checkbox" />',
    'title' => __('Title'),
    'content' => __('Content'),
    'date' => __('Date'),
    'publisher' => __('Publisher'),
    'book_author' => __('Book Author')
  );
}

// Show Data in CUstom Column

add_action('manage_book_posts_custom_column', 'product_custom_column_values', 10, 2);


function product_custom_column_values($column, $post_id)
{

  switch ($column) {
    case "publisher":
      echo "Nisa Publication";
      break;
    case "content":
      echo get_the_content();
      break;
    case "book_author":
      echo "Fahmida Hasan";
      break;
  }
}


// How to Add Short code

add_shortcode("new-sortCode", "sp_sortCode");

function sp_sortCode($atts)
{
  $atts = shortcode_atts(array(
    "gender" => "Male",
    "name" => "kazi Istiaq Mahamud"
  ), $atts, "bartag");

  ob_start();
?>
  <h2>Gender: <?php echo $atts["gender"] ?></h2>
  <h2>Name: <?php echo $atts["name"] ?></h2>

<?php

  return ob_get_clean();
}
