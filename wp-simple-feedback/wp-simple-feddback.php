<?php

/**
 * Plugin Name:wp-simple-feedback
 * Version: 1.0.0
 * Author: KAzi Istiaq Mahamud
 * Description: It is a simple feedback plugin.
 * Author URI: https://portfolio-client-sable.vercel.app/
 * Text Domain: wp-simple-feedback
 * Domain Path: /languages
 * License:GPL v2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 */


if (!defined("ABSPATH")) {
  die();
}

class Wp_Simple_Plugin
{
  public function __construct()
  {
    add_action('init', [$this, 'register_shortcode']);
    add_action('wp_enqueue_scripts', [$this, 'enqueue_scripts']);
    add_action("wp_ajax_submit_feedback", [$this, "submit_form_handler"]);
    add_action("wp_ajax_nopriv_submit_feedback", [$this, "submit_form_handler"]);
    add_action("admin_menu", [$this, "add_admin_page"]);
  }

  /* ACTIVATION METHOD MUST BE STATIC */
  public static function activate()
  {
    global $wpdb;

    $table_name = $wpdb->prefix . 'feedback';
    $charset_collate = $wpdb->get_charset_collate();

    $sql = "CREATE TABLE {$table_name} (
            id mediumint(9) NOT NULL AUTO_INCREMENT,
            name varchar(255) NOT NULL,
            email varchar(255) NOT NULL,
            feedback text NOT NULL,
            submit_time datetime DEFAULT CURRENT_TIMESTAMP,
            PRIMARY KEY (id)
        ) {$charset_collate};";

    require_once ABSPATH . 'wp-admin/includes/upgrade.php';
    dbDelta($sql);
  }

  public function register_shortcode()
  {
    add_shortcode('feedback_form', [$this, 'render_form']);
  }

  public function render_form($atts, $content = null)
  {
    ob_start();
?>
    <div class="feedback-form-container">
      <form id='feedback-form' class="feedback-form" method="post">
        <div class="form-group">
          <label>Name</label>
          <input type="text" name="name" id='name' required>
        </div>

        <div class="form-group">
          <label>Email</label>
          <input type="email" name="email" id='email' required>
        </div>

        <div class="form-group">
          <label>Feedback</label>
          <textarea name="feedback" id='feedback' required></textarea>
        </div>

        <button type="submit">Submit</button>
      </form>
      <div id="response"></div>
    </div>
  <?php
    return ob_get_clean();
  }

  public function enqueue_scripts()
  {
    wp_enqueue_style(
      'feedback-style',
      plugin_dir_url(__FILE__) . 'assets/css/feedback.css'
    );
    wp_enqueue_script('feedback_script', plugin_dir_url(__FILE__) . 'assets/js/feedback.js', ["jquery"], "1.0.0", true);
    wp_localize_script("feedback_script", "feedbackForm", [
      'ajax_url' => admin_url("admin-ajax.php"),
      'nonce'    => wp_create_nonce('feedback_form_nonce')
    ]);
  }

  public function submit_form_handler()
  {
    if (!check_ajax_referer("feedback_form_nonce", "nonce", false)) {
      wp_send_json_error("Security Failed");
      exit;
    }
    global $wpdb;
    $table_name = $wpdb->prefix . 'feedback';

    $name = sanitize_text_field($_POST["name"]);
    $email = sanitize_email($_POST["email"]);
    $feedBack = sanitize_textarea_field($_POST["feedback"]);

    if (empty($name) || empty($email) || empty($feedBack)) {
      wp_send_json_error("Fields are Required");
      exit;
    }
    $result = $wpdb->insert(
      $table_name,
      [
        "name" => $name,
        "email" => $email,
        "feedback" => $feedBack
      ],
      ["%s", "%s", "%s"]
    );

    if ($result) {
      wp_send_json_success("Feedback Submitted Successfully");
    } else {
      wp_send_json_error("Feedback has Error");
    }
  }

  public function add_admin_page()
  {
    add_menu_page("Feedback Entries", "Feedback", 
    "manage_options", "feedback_entries", [$this, "display_feedback"], "dashicons-feedback", 80);
  }

  public function display_feedback()
  {
    global $wpdb;
    $table_name = $wpdb->prefix . "feedback";

    $results = $wpdb->get_results("SELECT * FROM  $table_name ORDER BY submit_time DESC");
  ?>

    <div class="wrap">
      <h1>Feedback Entries</h1>

      <?php if (!empty($results)): ?>
        <table class="widefat fixed striped">
          <thead>
            <tr>
              <th>ID</th>
              <th>Name</th>
              <th>Email</th>
              <th>Feedback</th>
              <th>Submitted At</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($results as $row): ?>
              <tr>
                <td><?php echo esc_html($row->id); ?></td>
                <td><?php echo esc_html($row->name); ?></td>
                <td><?php echo esc_html($row->email); ?></td>
                <td><?php echo esc_html($row->feedback); ?></td>
                <td><?php echo esc_html($row->submit_time); ?></td>
              </tr>

            <?php endforeach; ?>
          </tbody>
        </table>
      <?php else: ?>
        <p>No feedback submitted yet.</p>
      <?php endif; ?>
    </div>

<?php
  }
}


new Wp_Simple_Plugin();
