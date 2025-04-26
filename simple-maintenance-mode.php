<?php
/*
Plugin Name: Simple Maintenance Mode
Description: Enables a maintenance mode with customizable logo, headline, and message. Displays admin notice when active.
Version: 1.1
Author: Mark Bridgeman
*/

if (!defined('ABSPATH')) {
    exit;
}

class Simple_Maintenance_Mode {
    public function __construct() {
        add_action('admin_menu', [$this, 'add_settings_page']);
        add_action('admin_init', [$this, 'register_settings']);
        add_action('template_redirect', [$this, 'maybe_display_maintenance_page']);
        add_action('admin_notices', [$this, 'admin_notice']);
        add_action('admin_head', [$this, 'admin_header_style']);
    }

    public function add_settings_page() {
        add_options_page('Maintenance Mode', 'Maintenance Mode', 'manage_options', 'maintenance-mode', [$this, 'render_settings_page']);
    }

    public function register_settings() {
        register_setting('maintenance_mode_settings', 'mm_enabled');
        register_setting('maintenance_mode_settings', 'mm_logo_id');
        register_setting('maintenance_mode_settings', 'mm_headline');
        register_setting('maintenance_mode_settings', 'mm_message');
    }

    public function render_settings_page() {
        $logo_id = get_option('mm_logo_id');
        $logo_url = $logo_id ? wp_get_attachment_url($logo_id) : '';
?>
        <div class="wrap">
            <h1>Maintenance Mode Settings</h1>
            <form method="post" action="options.php">
                <?php submit_button(); ?>
                <?php settings_fields('maintenance_mode_settings'); ?>
                <?php do_settings_sections('maintenance_mode_settings'); ?>
                <table class="form-table">
                    <tr valign="top">
                        <th scope="row">Enable Maintenance Mode</th>
                        <td>
                            <label class="switch">
                                <input type="checkbox" name="mm_enabled" value="1" <?php checked(1, get_option('mm_enabled'), true); ?> />
                                <span class="slider round"></span>
                            </label>
                        </td>
                    </tr>
                    <tr valign="top">
                        <th scope="row">Logo Upload</th>
                        <td>
                            <div id="mm-logo-preview">
                                <?php if ($logo_url): ?>
                                    <img src="<?php echo esc_url($logo_url); ?>" style="max-width: 200px;" />
                                <?php endif; ?>
                            </div>
                            <input type="hidden" name="mm_logo_id" id="mm_logo_id" value="<?php echo esc_attr($logo_id); ?>" />
                            <button type="button" class="button" id="mm-upload-logo">Upload Logo</button>
                        </td>
                    </tr>
                    <tr valign="top">
                        <th scope="row">Headline</th>
                        <td><input type="text" name="mm_headline" value="<?php echo esc_attr(get_option('mm_headline')); ?>" size="50" /></td>
                    </tr>
                    <tr valign="top">
                        <th scope="row">Message</th>
                        <td><?php
                            $content = get_option('mm_message');
                            $editor_id = 'mm_message';
                            wp_editor($content, $editor_id);
                        ?></td>
                    </tr>
                </table>
                <?php submit_button(); ?>
            </form>
        </div>
        <style>
            .switch {
                position: relative;
                display: inline-block;
                width: 50px;
                height: 24px;
            }
            .switch input { opacity: 0; width: 0; height: 0; }
            .slider {
                position: absolute; cursor: pointer;
                top: 0; left: 0; right: 0; bottom: 0;
                background-color: #ccc;
                transition: .4s;
                border-radius: 24px;
            }
            .slider:before {
                position: absolute;
                content: "";
                height: 18px; width: 18px;
                left: 3px; bottom: 3px;
                background-color: white;
                transition: .4s;
                border-radius: 50%;
            }
            input:checked + .slider {
                background-color: #007cba;
            }
            input:checked + .slider:before {
                transform: translateX(26px);
            }
        </style>
        <script>
            jQuery(document).ready(function($) {
                $('#mm-upload-logo').on('click', function(e) {
                    e.preventDefault();
                    const frame = wp.media({
                        title: 'Select or Upload Logo',
                        button: { text: 'Use this logo' },
                        multiple: false
                    });
                    frame.on('select', function() {
                        const attachment = frame.state().get('selection').first().toJSON();
                        $('#mm_logo_id').val(attachment.id);
                        $('#mm-logo-preview').html('<img src="' + attachment.url + '" style="max-width:200px;" />');
                    });
                    frame.open();
                });
            });
        </script>
<?php
    }

    public function maybe_display_maintenance_page() {
        if (!current_user_can('manage_options') && get_option('mm_enabled')) {
            status_header(503);
            echo '<!DOCTYPE html><html><head><meta charset="UTF-8"><title>Maintenance</title></head><body style="text-align:center;padding:50px;font-family:sans-serif;">';
            $logo_id = get_option('mm_logo_id');
            $logo_url = $logo_id ? wp_get_attachment_url($logo_id) : '';
            if ($logo_url) {
                echo '<img src="' . esc_url($logo_url) . '" alt="Logo" style="max-width:200px;margin-bottom:20px;"><br>';
            }
            echo '<h1>' . esc_html(get_option('mm_headline')) . '</h1>';
            echo wp_kses_post(wpautop(get_option('mm_message')));
            echo '</body></html>';
            exit;
        }
    }

    public function admin_notice() {
        if (get_option('mm_enabled')) {
            echo '<div class="notice notice-error" style="background:#ffdddd;border-left-color:red;"><p><strong>Maintenance Mode is Active</strong></p></div>';
        }
    }

    public function admin_header_style() {
        if (get_option('mm_enabled')) {
            echo '<style>#wpadminbar { background: red !important; }</style>';
        }
    }
}

new Simple_Maintenance_Mode();
?>