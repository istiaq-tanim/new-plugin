<?php

/* 
* Plugin Name: New Maintenance Mood
* Plugin URI:  https://portfolio-client-sable.vercel.app/
* Version: 1.0.0
* License:GPL v2 or later
* License URI: https://www.gnu.org/licenses/gpl-2.0.html
* Text Domain: wp-maintenance-mood
*/

class New_Maintenance_Mood
{
  public function __construct()
  {
    add_action("admin_menu", [$this, "wp_maintenance_mood_plugin"]);
    add_action("admin_init", [$this, "wp_maintenance_mood_settings"]);
    $wp_enable = "";
    $wp_enable = get_option("wmm_enable") ? get_option("wmm_enable")  : "";

    if ($wp_enable == 1) {
      add_action("wp", [$this, "font_maintenance_mood"]);
    }

    add_action("admin_enqueue_scripts", [$this, "enqueue_color_picker"]);
  }



  public function font_maintenance_mood()
  {

    $message = get_option("wmm_message") ? get_option("wmm_message")
      : "This Website is Currently Under Maintenance";
    $text_color = get_option("wmm_text_color") ? get_option("wmm_text_color") : "#000000";
    $font_size = get_option("wmm_font_size") ? get_option("wmm_font_size") : "16px";

    $html = '
        <h1>Maintenance Mode</h1>
        <p style="color:' . esc_attr($text_color) . '; font-size:' . esc_attr($font_size) . ';">
            ' . esc_html($message) . '
        </p>
    ';
    wp_die($html);
  }



  // Add to Submenu

  public function wp_maintenance_mood_plugin()
  {
    add_submenu_page(
      "options-general.php",
      "Maintenance Mood",
      "Maintenance Mood",
      "manage_options",
      "wp-maintenance",
      [$this, "init_maintenance_mood"]
    );
  }

  public function init_maintenance_mood()
  {
?>
    <div class="product-sales-count">
      <h2>WP Maintanace Mood</h2>
      <form action="options.php" method="post">
        <div class="main-setting-section-wdp">
          <table class="form-table">
            <tr>
              <th scope="row">Maintenance Mode</th>
              <td>
                <label>
                  <input type="checkbox"
                    id="wmm_enable"
                    name="wmm_enable"
                    value="1"
                    <?php checked(1, get_option('wmm_enable')); ?> />
                  Enable / Disable Maintenance Mode
                </label>
              </td>
            </tr>

            <tr>
              <th scope="row">
                <label for="wmm_message">Maintenance Message</label>
              </th>
              <td>
                <textarea id="wmm_message"
                  name="wmm_message"
                  rows="4"
                  cols="50"><?php
                            echo esc_textarea(
                              get_option(
                                'wmm_message',
                                'This website is under maintenance. Please check back later.'
                              )
                            );
                            ?></textarea>
              </td>
            </tr>

            <tr>
              <th scope="row">
                <label for="wmm_text_color">Text Color</label>
              </th>
              <td>
                <input type="text"
                  id="wmm_text_color"
                  name="wmm_text_color"
                  class="wmm-color-picker"
                  value="<?php echo esc_attr(get_option('wmm_text_color', '#000000')); ?>" />
              </td>
            </tr>

            <tr>
              <th scope="row">
                <label for="wmm_font_size">Font Size</label>
              </th>
              <td>
                <input type="text"
                  id="wmm_font_size"
                  name="wmm_font_size"
                  value="<?php echo esc_attr(get_option('wmm_font_size', '16px')); ?>" />
              </td>
            </tr>
          </table>
        </div>
        <span class="submit-btn">
          <?php _e(get_submit_button('Save Settings', 'button-primary', 'submit', '', ''), 'wdp'); ?>
        </span>
        </p>
        <?php settings_fields('wmm_options'); ?>
      </form>
    </div>

<?php
  }

  public function wp_maintenance_mood_settings()
  {
    register_setting("wmm_options", "wmm_enable", "sanitize_text_field");
    register_setting("wmm_options", "wmm_message", "sanitize_text_field");
    register_setting("wmm_options", "wmm_font_size", "sanitize_text_field");
    register_setting("wmm_options", "wmm_text_color", "sanitize_text_field");
  }

  public function enqueue_color_picker()
  {
    wp_enqueue_style('wp-color-picker');
    wp_enqueue_script('wp-color-picker');
    wp_add_inline_script(
      'wp-color-picker',
      'jQuery(function($){
            $(".wmm-color-picker").wpColorPicker();
        });'
    );
  }
}

new New_Maintenance_Mood();
