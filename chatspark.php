<?php
/*
 * Plugin Name: ChatSpark
 * Plugin URI: https://wordpress.org/plugins/chatspark/
 * Description: Embed ChatSpark chatbot on your WordPress site.
 * Version: 1.0.0
 * Author: ChatSpark.io
 * Author URI: https://www.chatspark.io/
 * License: GPLv2 or later
 * License URI: http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 * Text Domain: chatspark
 * Domain Path: /languages
 * Requires at least: 6.3
 * Requires PHP: 8.0
*/

// Admin Menu Creation
function cspark_create_menu() {
    add_menu_page('ChatSpark Settings', 'ChatSpark', 'manage_options', 'cspark_settings', 'cspark_settings_page', 'none', null);
    add_action('admin_head', 'cspark_enqueue_admin_styles');
}
add_action('admin_menu', 'cspark_create_menu');

function cspark_enqueue_admin_styles() {
    echo '<style>
        #adminmenu .toplevel_page_cspark_settings div.wp-menu-image {
            background: url("data:image/svg+xml;base64,' . esc_attr( base64_encode('<svg width="36" height="36" viewBox="0 0 36 36" fill="none" xmlns="http://www.w3.org/2000/svg"><g><polygon fill="#a7aaad" points="18.4,6.3 13.7,20 7.9,20"/><g><path fill="#a7aaad" d="M29,15.3L29,15.3c-0.4-0.7-1.1-1.2-2-1.2h-4.5v3.6h1.9l-7.2,10.6v-5.9h-3.6v10.4c0,1,0.6,1.8,1.6,2.1c0.2,0.1,0.4,0.1,0.6,0.1c0.7,0,1.4-0.4,1.8-1l11.1-16.5C29.3,16.8,29.4,16,29,15.3z"/><path fill="#a7aaad" d="M21.3,19.3V2.9c0-0.8-0.5-1.6-1.3-1.8s-1.6,0.1-2.1,0.7L6.8,18.3c-0.4,0.6-0.4,1.3-0.1,1.9c0.3,0.6,1,1,1.7,1h11.1C20.5,21.2,21.3,20.4,21.3,19.3z M18.4,6.3v12h-8.1L18.4,6.3z"/></g></g></svg>') ) . '") no-repeat center;
            background-size: 20px 20px;
        }
    </style>';
}

// Settings Registration
add_action('admin_init', 'cspark_register_settings');

function cspark_register_settings() {
    register_setting('cspark_settings_group', 'cspark_chatbot_id');
    add_settings_section('cspark_settings_section', 'ChatSpark Settings', 'cspark_settings_section_callback', 'cspark_settings');
    add_settings_field('cspark_chatbot_id', 'Chatbot ID', 'cspark_chatbot_id_callback', 'cspark_settings', 'cspark_settings_section');
}

function cspark_settings_section_callback() {
    echo '<p>Enter your Chatbot ID to embed the ChatSpark chatbot on your site.</p>';
}

function cspark_chatbot_id_callback() {
    $chatbot_id = get_option('cspark_chatbot_id');
    echo '<input type="text" name="cspark_chatbot_id" value="' . esc_attr($chatbot_id) . '" />';
    echo '<p class="description">You can find your chatbot ID by logging into <a href="https://chatspark.io/login" target="_blank">ChatSpark.io</a>, going to My AI Chatbots > find your Chatbot > copy and paste the ID into the field above.</p>';
}

function cspark_settings_page() {
    ?>
    <div class="wrap">
        <form method="post" action="options.php">
            <?php
            settings_fields('cspark_settings_group');
            do_settings_sections('cspark_settings');
            submit_button('Save Settings');
            ?>
        </form>
    </div>
    <?php
}

// Embed Chatbot Script on the Frontend
function cspark_embed_chatbot() {
    $chatbot_id = get_option('cspark_chatbot_id');
    if ($chatbot_id) {
        echo '<script src="https://chat.chatspark.io/loader.js"></script>';
        echo "<script type=\"text/javascript\">
                window.openChatSparkBot(chatbotId = \"" . esc_js($chatbot_id) . "\", isOpen = false, fullScreen = false, render = 'body');
              </script>";
    }
}
add_action('wp_footer', 'cspark_embed_chatbot');

?>