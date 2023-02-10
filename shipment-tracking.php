<?php
/*
Plugin Name: Shipment Tracking
Plugin URI: https://damilolasteven.com
Description: A plugin that allows customers to track their courier packages. Using the shortcode [wp_courier_tracker_form]
Version: 1.0
Requires at least: 5.2
Requires PHP:      7.2
Author: Damilola Ajila
Author URI:https://damilolasteven.com
*/

if (isset($_POST['courier']) && isset($_POST['tracking-number'])) {
    $courier = $_POST['courier'];
    $tracking_number = $_POST['tracking-number'];

    switch ($courier) {
        case 'fedex':
            $tracking_url = 'https://www.fedex.com/apps/fedextrack/?tracknumbers=' . $tracking_number;
            break;
        case 'dhl':
            $tracking_url = 'https://www.dhl.com/en/express/tracking.html?brand=DHL&AWB=' . $tracking_number;
            break;
        case 'ups':
            $tracking_url = 'https://www.ups.com/track?loc=en_US&tracknum=' . $tracking_number;
            break;
        case 'aramex':
            $tracking_url = 'https://www.aramex.com/us/en/track/results?ShipmentNumber=' . $tracking_number;
            break;
        case 'redstarexpress':
            $tracking_url = 'https://redstarplc.com/tracker/' . $tracking_number;
            break;
        // Add more cases for other carriers if needed
        default:
            $tracking_url = '';
            break;
    }

    if (!empty($tracking_url)) {
        header('Location: ' . $tracking_url);
        exit;
    }
}

function wp_courier_tracker_shortcode() {
    ob_start();
    ?>
    <form action="" method="post">
        <label for="courier">Select Carrier:</label>
        <select id="courier" name="courier" required>
        <option value="select">Select</option>
            <option value="fedex">FedEx</option>
            <option value="dhl">DHL</option>
            <option value="ups">UPS</option>
            <option value="aramex">Aramex</option>
            <option value="redstarexpress">Red Star Express</option>
            <!-- Add more carriers if needed -->
        </select>

        <label for="tracking-number">Enter Tracking Number:</label>
        <input type="text" id="tracking-number" name="tracking-number" required>

        <input type="submit" value="Submit">
    </form>
    <?php
    return ob_get_clean();
}
add_shortcode('wp_courier_tracker_form', 'wp_courier_tracker_shortcode');


function wp_courier_tracker_settings_page() {
    ?>
    <div class="wrap">
        <h1>Courier Tracker Settings</h1>
        <form action="options.php" method="post">
            <?php
            settings_fields('wp_courier_tracker_settings_group');
            do_settings_sections('wp_courier_tracker_settings_page');
            submit_button();
            ?>
        </form>
    </div>
    <?php
}

function wp_courier_tracker_register_settings() {
    // Register the setting
    register_setting('wp_courier_tracker_settings_group', 'wp_courier_tracker_settings');

    // Add a section for the settings
    add_settings_section(
        'wp_courier_tracker_settings_section',
        'Courier Tracker Settings',
        'wp_courier_tracker_settings_section_cb',
        'wp_courier_tracker_settings_page'
    );

    // Add fields for the settings
    add_settings_field(
        'fedex_tracking_url',
        'FedEx Tracking URL',
        'wp_courier_tracker_field_cb',
        'wp_courier_tracker_settings_page',
        'wp_courier_tracker_settings_section',
        ['label_for' => 'fedex_tracking_url', 'class' => 'wp_courier_tracker_row', 'wp_courier_tracker_setting' => 'fedex_tracking_url']
    );

    add_settings_field(
        'dhl_tracking_url',
        'DHL Tracking URL',
        'wp_courier_tracker_field_cb',
        'wp_courier_tracker_settings_page',
        'wp_courier_tracker_settings_section',
        ['label_for' => 'dhl_tracking_url', 'class' => 'wp_courier_tracker_row', 'wp_courier_tracker_setting' => 'dhl_tracking_url']
    );

    add_settings_field(
        'ups_tracking_url',
        'UPS Tracking URL',
        'wp_courier_tracker_field_cb',
        'wp_courier_tracker_settings_page',
        'wp_courier_tracker_settings_section',
        ['label_for' => 'ups_tracking_url', 'class' => 'wp_courier_tracker_row', 'wp_courier_tracker_setting' => 'ups_tracking_url']
    );

    add_settings_field(
        'aramex_tracking_url',
        'Aramex Tracking URL',
        'wp_courier_tracker_field_cb',
        'wp_courier_tracker_settings_page',
        'wp_courier_tracker_settings_section',
        ['label_for' => 'aramex_tracking_url', 'class' => 'wp_courier_tracker_row', 'wp_courier_tracker_setting' => 'aramex_tracking_url']
    );

    add_settings_field(
        'redstarexpress_tracking_url',
        'Red Star Express Tracking URL',
        'wp_courier_tracker_field_cb',
        'wp_courier_tracker_settings_page',
        'wp_courier_tracker_settings_section',
        ['label_for' => 'redstarexpress_tracking_url', 'class' => 'wp_courier_tracker_row', 'wp_courier_tracker_setting' => 'redstarexpress_tracking_url']
    );
}
add_action('admin_init', 'wp_courier_tracker_register_settings');

function wp_courier_tracker_settings_section_cb() {
    echo 'Enter the tracking URL for each carrier below:';
}

    function wp_courier_tracker_field_cb($args) {
        $options = get_option('wp_courier_tracker_settings');
        $value = isset($options[$args['wp_courier_tracker_setting']]) ? $options[$args['wp_courier_tracker_setting']] : '';
        ?>
        <input type="text" class="regular-text" name="wp_courier_tracker_settings[<?php echo esc_attr($args['wp_courier_tracker_setting']); ?>]" id="<?php echo esc_attr($args['label_for']); ?>" value="<?php echo esc_attr($value); ?>">
        <?php
    }

        function wp_courier_tracker_add_menu_page() {
            add_options_page(
            'Courier Tracker Settings',
            'Courier Tracker',
            'manage_options',
            'wp_courier_tracker_settings_page',
            'wp_courier_tracker_settings_page'
            );
        }
add_action('admin_menu', 'wp_courier_tracker_add_menu_page');


function courier_tracking_load_css() {
    wp_enqueue_style('courier-tracking', plugin_dir_url(__FILE__) . 'courier-tracking.css');
  }
  add_action('wp_enqueue_scripts', 'courier_tracking_load_css');
  
