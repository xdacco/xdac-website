<?php
/*
Plugin Name: Profile Builder
Plugin URI: https://www.cozmoslabs.com/wordpress-profile-builder/
Description: Login, registration and edit profile shortcodes for the front-end. Also you can chose what fields should be displayed or add new (custom) ones both in the front-end and in the dashboard.
Version: 2.7.8
Author: Cozmoslabs, Madalin Ungureanu, Antohe Cristian, Barina Gabriel, Mihai Iova
Author URI: https://www.cozmoslabs.com/
Text Domain: profile-builder
Domain Path: /translation
License: GPL2

== Copyright ==
Copyright 2014 Cozmoslabs (www.cozmoslabs.com)

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 2 of the License, or
(at your option) any later version.
This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU General Public License for more details.
You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA 02110-1301 USA
*/

/* Check if another version of Profile Builder is activated, to prevent fatal errors*/
function wppb_free_plugin_init() {
    if (function_exists('wppb_return_bytes')) {
        function wppb_admin_notice()
        {
            ?>
            <div class="error">
                <p><?php printf( __( '%s is also activated. You need to deactivate it before activating this version of the plugin.', 'profile-builder'), PROFILE_BUILDER ); ?></p>
            </div>
        <?php
        }
        function wppb_plugin_deactivate() {
            deactivate_plugins( plugin_basename( __FILE__ ) );
            unset($_GET['activate']);
        }

        add_action('admin_notices', 'wppb_admin_notice');
        add_action( 'admin_init', 'wppb_plugin_deactivate' );
    } else {

        /**
         * Convert memory value from ini file to a readable form
         *
         * @since v.1.0
         *
         * @return integer
         */
        function wppb_return_bytes($val)
        {
            $val = trim($val);

            switch (strtolower($val[strlen($val) - 1])) {
                // The 'G' modifier is available since PHP 5.1.0
                case 'g':
                    $val = intval($val) * 1024;
                case 'm':
                    $val = intval($val) * 1024;
                case 'k':
                    $val = intval($val) * 1024;
            }

            return $val;
        }

        /**
         * Definitions
         *
         *
         */
        define('PROFILE_BUILDER_VERSION', '2.7.8' );
        define('WPPB_PLUGIN_DIR', plugin_dir_path(__FILE__));
        define('WPPB_PLUGIN_URL', plugin_dir_url(__FILE__));
        define('WPPB_SERVER_MAX_UPLOAD_SIZE_BYTE', apply_filters('wppb_server_max_upload_size_byte_constant', wppb_return_bytes(ini_get('upload_max_filesize'))));
        define('WPPB_SERVER_MAX_UPLOAD_SIZE_MEGA', apply_filters('wppb_server_max_upload_size_mega_constant', ini_get('upload_max_filesize')));
        define('WPPB_SERVER_MAX_POST_SIZE_BYTE', apply_filters('wppb_server_max_post_size_byte_constant', wppb_return_bytes(ini_get('post_max_size'))));
        define('WPPB_SERVER_MAX_POST_SIZE_MEGA', apply_filters('wppb_server_max_post_size_mega_constant', ini_get('post_max_size')));
        define('WPPB_TRANSLATE_DIR', WPPB_PLUGIN_DIR . '/translation');
        define('WPPB_TRANSLATE_DOMAIN', 'profile-builder');

        /* include notices class */
        if (file_exists(WPPB_PLUGIN_DIR . '/assets/lib/class_notices.php'))
            include_once(WPPB_PLUGIN_DIR . '/assets/lib/class_notices.php');

        if (file_exists(WPPB_PLUGIN_DIR . '/modules/modules.php'))
            define('PROFILE_BUILDER', 'Profile Builder Pro');
        elseif (file_exists(WPPB_PLUGIN_DIR . '/front-end/extra-fields/extra-fields.php'))
            define('PROFILE_BUILDER', 'Profile Builder Hobbyist');
        else
            define('PROFILE_BUILDER', 'Profile Builder Free');

        /**
         * Initialize the translation for the Plugin.
         *
         * @since v.1.0
         *
         * @return null
         */
        function wppb_init_translation()
        {
            $current_theme = wp_get_theme();
            if( !empty( $current_theme->stylesheet ) && file_exists( get_theme_root().'/'. $current_theme->stylesheet .'/local_pb_lang' ) )
                load_plugin_textdomain( 'profile-builder', false, basename( dirname( __FILE__ ) ).'/../../themes/'.$current_theme->stylesheet.'/local_pb_lang' );
            else
                load_plugin_textdomain( 'profile-builder', false, basename(dirname(__FILE__)) . '/translation/' );
        }

        add_action('init', 'wppb_init_translation', 8);


        /**
         * Required files
         *
         *
         */
        include_once(WPPB_PLUGIN_DIR . '/assets/lib/wck-api/wordpress-creation-kit.php');
        include_once(WPPB_PLUGIN_DIR . '/features/upgrades/upgrades.php');
        include_once(WPPB_PLUGIN_DIR . '/features/functions.php');
        include_once(WPPB_PLUGIN_DIR . '/admin/admin-functions.php');
        include_once(WPPB_PLUGIN_DIR . '/admin/basic-info.php');
        include_once(WPPB_PLUGIN_DIR . '/admin/general-settings.php');
        include_once(WPPB_PLUGIN_DIR . '/admin/admin-bar.php');
        include_once(WPPB_PLUGIN_DIR . '/admin/manage-fields.php');
        include_once(WPPB_PLUGIN_DIR . '/admin/pms-cross-promotion.php');
        include_once(WPPB_PLUGIN_DIR . '/features/email-confirmation/email-confirmation.php');
        include_once(WPPB_PLUGIN_DIR . '/features/email-confirmation/class-email-confirmation.php');
        if (file_exists(WPPB_PLUGIN_DIR . '/features/admin-approval/admin-approval.php')) {
            include_once(WPPB_PLUGIN_DIR . '/features/admin-approval/admin-approval.php');
            include_once(WPPB_PLUGIN_DIR . '/features/admin-approval/class-admin-approval.php');
        }
        if (file_exists(WPPB_PLUGIN_DIR . '/features/conditional-fields/conditional-fields.php')) {
            include_once(WPPB_PLUGIN_DIR . '/features/conditional-fields/conditional-fields.php');
        }
        include_once(WPPB_PLUGIN_DIR . '/features/login-widget/login-widget.php');
        include_once(WPPB_PLUGIN_DIR . '/features/roles-editor/roles-editor.php');
        include_once(WPPB_PLUGIN_DIR . '/features/content-restriction/content-restriction.php');

        if (file_exists(WPPB_PLUGIN_DIR . '/update/update-checker.php')) {
            include_once(WPPB_PLUGIN_DIR . '/update/update-checker.php');
            include_once(WPPB_PLUGIN_DIR . '/admin/register-version.php');
        }

        if (file_exists(WPPB_PLUGIN_DIR . '/modules/modules.php')) {
            include_once(WPPB_PLUGIN_DIR . '/modules/modules.php');
            include_once(WPPB_PLUGIN_DIR . '/modules/repeater-field/repeater-module.php');
            include_once(WPPB_PLUGIN_DIR . '/modules/custom-redirects/custom-redirects.php');
            include_once(WPPB_PLUGIN_DIR . '/modules/email-customizer/email-customizer.php');
            include_once(WPPB_PLUGIN_DIR . '/modules/multiple-forms/multiple-forms.php');
            include_once(WPPB_PLUGIN_DIR . '/modules/user-listing/userlisting.php');

            $wppb_module_settings = get_option('wppb_module_settings');
            if (isset($wppb_module_settings['wppb_userListing']) && ($wppb_module_settings['wppb_userListing'] == 'show')) {
                add_shortcode('wppb-list-users', 'wppb_user_listing_shortcode');
            } else
            add_shortcode('wppb-list-users', 'wppb_list_all_users_display_error');

            if (isset($wppb_module_settings['wppb_emailCustomizerAdmin']) && ($wppb_module_settings['wppb_emailCustomizerAdmin'] == 'show'))
            include_once(WPPB_PLUGIN_DIR . '/modules/email-customizer/admin-email-customizer.php');

            if (isset($wppb_module_settings['wppb_emailCustomizer']) && ($wppb_module_settings['wppb_emailCustomizer'] == 'show'))
            include_once(WPPB_PLUGIN_DIR . '/modules/email-customizer/user-email-customizer.php');
        }

        include_once(WPPB_PLUGIN_DIR . '/admin/add-ons.php');
        include_once(WPPB_PLUGIN_DIR . '/assets/misc/plugin-compatibilities.php');

        /* added recaptcha and user role field since version 2.6.2 */
        include_once(WPPB_PLUGIN_DIR . '/front-end/default-fields/recaptcha/recaptcha.php'); //need to load this here for displaying reCAPTCHA on Login and Recover Password forms


        /**
         * Check for updates
         *
         *
         */
        if (file_exists(WPPB_PLUGIN_DIR . '/update/update-checker.php')) {
            if (file_exists(WPPB_PLUGIN_DIR . '/modules/modules.php')) {
                $localSerial = get_option('wppb_profile_builder_pro_serial');
                $wppb_update = new wppb_PluginUpdateChecker('http://updatemetadata.cozmoslabs.com/?localSerialNumber=' . $localSerial . '&uniqueproduct=CLPBP', __FILE__, 'profile-builder-pro-update');

            } else {
                $localSerial = get_option('wppb_profile_builder_hobbyist_serial');
                $wppb_update = new wppb_PluginUpdateChecker('http://updatemetadata.cozmoslabs.com/?localSerialNumber=' . $localSerial . '&uniqueproduct=CLPBH', __FILE__, 'profile-builder-hobbyist-update');
            }
        }


// these settings are important, so besides running them on page load, we also need to do a check on plugin activation
        add_action('init', 'wppb_generate_default_settings_defaults');    //prepoulate general settings
        add_action('init', 'wppb_prepopulate_fields');                    //prepopulate manage fields list

    }
} //end wppb_free_plugin_init
add_action( 'plugins_loaded', 'wppb_free_plugin_init' );

if (file_exists( plugin_dir_path(__FILE__) . '/front-end/extra-fields/upload/upload_helper_functions.php'))
    include_once( plugin_dir_path(__FILE__) . '/front-end/extra-fields/upload/upload_helper_functions.php');