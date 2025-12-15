<?php

/**
 * Plugin Name: Book Shop
 * Description: Book Fancy Shop Plugin
 * Version: 1.0.0
 * Author: Kazi Istiaq Mahamud
 * Author URI: https://portfolio-client-sable.vercel.app/
 * Text Domain: bookShop
 * Domain Path: /languages
 * License:GPL v2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * 
 *  */

if (!defined("ABSPATH")) {
  die();
}


class BookShop
{
  public static $instance;

  public static function get_instance()
  {
    if (!isset(self::$instance)) {
      self::$instance = new self();
    }

    return self::$instance;
  }

  public function __construct()
  {
    $this->init_hooks();
  }

  private function init_hooks()
  {
    add_action("init", [$this, "register_bookshop"]);
    add_filter("single_template",[$this,"add_book_single_template"]);
    add_action("wp_enqueue_scripts",[$this,"enqueue_style"]);
  }

  public function register_bookshop()
  {
    register_post_type("book", [
      "labels" => [
        "name" => __("Book", "bookShop"),
        "singular_name" => __("Book", "bookShop"),
        "add_new" => __("Add Book", "bookShop"),
        "add_new_item" => __("Add New Book", "bookShop"),
        "new_item" => __("New Book", "bookShop"),
        "set_featured_image" => __("Set Book Image", "bookShop"),
      ],
      "public" => true,
      // "has_archive" => true,
      "supports" => ["title", "editor", "thumbnail", 'revisions',"custom-fields"],
      "menu_position" => 10,
      "show_in_nav_menus" => true,
      "show_in_menu" => true,
      "menu_icon"=> "dashicons-book-alt"
    ]);
  }

  public function add_book_single_template($template){
     if(is_singular("book")){
       $single_template = plugin_dir_path(__FILE__) . "/templates/book-single.php";

       if(file_exists($single_template)){
          return $single_template;
       }

     }
     return $template;
  }

  public function enqueue_style(){
    wp_enqueue_style("bookShop-style",plugin_dir_url(__FILE__) . "assests/css/bookShop.css" ,[] ,  '1.0.0', "all");
  }
}

BookShop::get_instance();
