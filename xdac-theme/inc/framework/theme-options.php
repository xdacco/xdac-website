<?php
    /**
     * ReduxFramework Sample Config File
     * For full documentation, please visit: http://docs.reduxframework.com/
     */

    if ( ! class_exists( 'Redux' ) ) {
        return;
    }


    // This is your option name where all the Redux data is stored.
    $opt_name = "appai";

    // This line is only for altering the demo. Can be easily removed.
    $opt_name = apply_filters( 'redux_demo/opt_name', $opt_name );

    /*
     *
     * --> Used within different fields. Simply examples. Search for ACTUAL DECLARATION for field examples
     *
     */

    $sampleHTML = '';

    /**
     * ---> SET ARGUMENTS
     * All the possible arguments for Redux.
     * For full documentation on arguments, please refer to: https://github.com/ReduxFramework/ReduxFramework/wiki/Arguments
     * */

    $theme = wp_get_theme(); // For use with some settings. Not necessary.

    $args = array(
        // TYPICAL -> Change these values as you need/desire
        'opt_name'             => $opt_name,
        // This is where your data is stored in the database and also becomes your global variable name.
        'display_name'         => $theme->get( 'Name' ),
        // Name that appears at the top of your panel
        'display_version'      => $theme->get( 'Version' ),
        // Version that appears at the top of your panel
        'menu_type'            => 'menu',
        //Specify if the admin menu should appear or not. Options: menu or submenu (Under appearance only)
        'allow_sub_menu'       => true,
        // Show the sections below the admin menu item or not
        'menu_title'           => __( 'Theme Options', 'appai' ),
        'page_title'           => __( 'Theme Options', 'appai' ),
        // You will need to generate a Google API key to use this feature.
        // Please visit: https://developers.google.com/fonts/docs/developer_api#Auth
        'google_api_key'       => '',
        // Set it you want google fonts to update weekly. A google_api_key value is required.
        'google_update_weekly' => false,
        // Must be defined to add google fonts to the typography module
        'async_typography'     => false,
        // Use a asynchronous font on the front end or font string
        //'disable_google_fonts_link' => true,                    // Disable this in case you want to create your own google fonts loader
        'admin_bar'            => false,
        // Show the panel pages on the admin bar
        'admin_bar_icon'       => 'dashicons-portfolio',
        // Choose an icon for the admin bar menu
        'admin_bar_priority'   => 50,
        // Choose an priority for the admin bar menu
        'global_variable'      => '',
        // Set a different name for your global variable other than the opt_name
        'dev_mode'             => false,
        // Show the time the page took to load, etc
        'update_notice'        => false,
        // If dev_mode is enabled, will notify developer of updated versions available in the GitHub Repo
        'customizer'           => true,
        // Enable basic customizer support
        //'open_expanded'     => true,                    // Allow you to start the panel in an expanded way initially.
        //'disable_save_warn' => true,                    // Disable the save warning when a user changes a field

        // OPTIONAL -> Give you extra features
        'page_priority'        => 2,
        // Order where the menu appears in the admin area. If there is any conflict, something will not show. Warning.
        'page_parent'          => 'themes.php',
        // For a full list of options, visit: http://codex.wordpress.org/Function_Reference/add_submenu_page#Parameters
        'page_permissions'     => 'manage_options',
        // Permissions needed to access the options panel.
        'menu_icon'            => '',
        // Specify a custom URL to an icon
        'last_tab'             => '',
        // Force your panel to always open to a specific tab (by id)
        'page_icon'            => 'icon-themes',
        // Icon displayed in the admin panel next to your menu_title
        'page_slug'            => '',
        // Page slug used to denote the panel, will be based off page title then menu title then opt_name if not provided
        'save_defaults'        => true,
        // On load save the defaults to DB before user clicks save or not
        'default_show'         => false,
        // If true, shows the default value next to each field that is not the default value.
        'default_mark'         => '',
        // What to print by the field's title if the value shown is default. Suggested: *
        'show_import_export'   => true,
        // Shows the Import/Export panel when not used as a field.

        // CAREFUL -> These options are for advanced use only
        'transient_time'       => 60 * MINUTE_IN_SECONDS,
        'output'               => true,
        // Global shut-off for dynamic CSS output by the framework. Will also disable google fonts output
        'output_tag'           => true,
        // Allows dynamic CSS to be generated for customizer and google fonts, but stops the dynamic CSS from going to the head
        // 'footer_credit'     => '',                   // Disable the footer credit of Redux. Please leave if you can help it.

        // FUTURE -> Not in use yet, but reserved or partially implemented. Use at your own risk.
        'database'             => '',
        // possible: options, theme_mods, theme_mods_expanded, transient. Not fully functional, warning!
        'use_cdn'              => true,
        // If you prefer not to use the CDN for Select2, Ace Editor, and others, you may download the Redux Vendor Support plugin yourself and run locally or embed it in your code.

        // HINTS
        'hints'                => array(
            'icon'          => 'el el-question-sign',
            'icon_position' => 'right',
            'icon_color'    => 'lightgray',
            'icon_size'     => 'normal',
            'tip_style'     => array(
                'color'   => 'red',
                'shadow'  => true,
                'rounded' => false,
                'style'   => '',
            ),
            'tip_position'  => array(
                'my' => 'top left',
                'at' => 'bottom right',
            ),
            'tip_effect'    => array(
                'show' => array(
                    'effect'   => 'slide',
                    'duration' => '500',
                    'event'    => 'mouseover',
                ),
                'hide' => array(
                    'effect'   => 'slide',
                    'duration' => '500',
                    'event'    => 'click mouseleave',
                ),
            ),
        )
    );

    // Panel Intro text -> before the form
    if ( ! isset( $args['global_variable'] ) || $args['global_variable'] !== false ) {
        if ( ! empty( $args['global_variable'] ) ) {
            $v = $args['global_variable'];
        } else {
            $v = str_replace( '-', '_', $args['opt_name'] );
        }
        $args['intro_text'] = sprintf( __( '<p>Did you know that Redux sets a global variable for you? To access any of your saved options from within your code you can use your global variable: <strong>$%1$s</strong></p>', 'appai' ), $v );
    } else {
        $args['intro_text'] = __( '<p>This text is displayed above the options panel. It isn\'t required, but more info is always better! The intro_text field accepts all HTML.</p>', 'appai' );
    }

    // Add content after the form.
    $args['footer_text'] = __( '<p>This text is displayed below the options panel. It isn\'t required, but more info is always better! The footer_text field accepts all HTML.</p>', 'appai' );

    Redux::setArgs( $opt_name, $args );

    /*
     * ---> END ARGUMENTS
     */


    /*
     * ---> START HELP TABS
     */

    $tabs = array(
        array(
            'id'      => 'redux-help-tab-1',
            'title'   => esc_html__( 'Theme Information 1', 'appai' ),
            'content' => '<p>' . esc_html__( 'This is the tab content, HTML is allowed.', 'appai' ) . '</p>'
        ),
        array(
            'id'      => 'redux-help-tab-2',
            'title'   => esc_html__( 'Theme Information 2', 'appai' ),
            'content' => '<p>' . esc_html__( 'This is the tab content, HTML is allowed.', 'appai' ) . '</p>'
        )
    );
    Redux::setHelpTab( $opt_name, $tabs );

    // Set the help sidebar
    $content =  '<p>' . esc_html__( 'This is the sidebar content, HTML is allowed.', 'appai' ) . '</p>';
    Redux::setHelpSidebar( $opt_name, $content );


    /*
     * <--- END HELP TABS
     */


    /*
     *
     * ---> START SECTIONS
     *
     */

    /*

        As of Redux 3.5+, there is an extensive API. This API can be used in a mix/match mode allowing for


     */


     Redux::setSection( $opt_name, array(
         'title'            => esc_html__( 'General Settings', 'appai' ),
         'id'               => 'general_settings',
         'class'            => 'option-page-layout',
         'customizer_width' => '450px',
         'fields'           => array(
            array(
                'id'       => 'preloader',
                'type'     => 'switch',
                'title'    => esc_html__( 'Preloader', 'appai' ),
                'subtitle' => esc_html__( 'Show or hide preloader', 'appai' ),
                'default'  => true
            ),
            array(
                'id'       => 'post_social_share_btn_switch',
                'type'     => 'switch',
                'title'    => esc_html__( 'Post Social Share Buttons', 'appai' ),
                'subtitle' => esc_html__( 'Enable Post Social Share Buttons on Post Single page', 'appai' ),
                'default'  => true
            ),
            array(
                'id'       => 'scroll_top',
                'type'     => 'switch',
                'title'    => esc_html__( 'Scroll Top Button', 'appai' ),
                'subtitle' => esc_html__( 'Enable Scroll to top button', 'appai' ),
                'default'  => true
            ),

         )
     ) );


     Redux::setSection( $opt_name, array(
         'title'            => esc_html__( 'Header', 'appai' ),
         'id'               => 'header_settings',
         'class'            => 'option-page-layout',
         'customizer_width' => '450px',
         'fields'           => array(
             array(
                 'id'       => 'transarent_header',
                 'type'     => 'switch',
                 'title'    => esc_html__( 'Transparent Header ', 'appai' ),
                 'subtitle'    => esc_html__( 'Make the header transparent on homepage only.', 'appai' ),
                 'default'     => false,
             ),
             array(
                 'id'       => 'menu_scheme',
                 'type'     => 'select',
                 'title'    => esc_html__( 'Menu Scheme ', 'appai' ),
                 'subtitle'    => esc_html__( 'Select the menu scheme.', 'appai' ),
                 'options'  => array(
                    'turquoise' => ' Turquoise',
                    'red' => ' Red',
                    'black' => 'Black',
                    'white' => 'White'
                ),
                 'default'     => 'turquoise',

             ),
         )
     ) );

    Redux::setSection( $opt_name, array(
        'title'            => __( 'Navigation', 'appai' ),
        'id'               => 'navigation_settings',
        'customizer_width' => '450px',
        'fields'           => array(
            array(
                'id'          => 'navbar_background',
                'type'        => 'color_gradient',
                'title'       => esc_html__('Navbar Background', 'appai'),
                'output'      => array('header .navbar.affix'),
                'units'       =>'px',
                'subtitle'    => esc_html__('Set your preferred gradient backgrond  for Navigation.', 'appai'),
            ),
        )
    ) );



    Redux::setSection( $opt_name, array(
        'title'            => __( 'Logo', 'appai' ),
        'id'               => 'logo_settings',
        'customizer_width' => '450px',
        'fields'           => array(
            array(
                'id'       => 'logo',
                'type'     => 'media',
                'title'    => esc_html__( 'Logo', 'appai' ),
                'subtitle' => esc_html__( 'Choose the site logo', 'appai' ),
                'default'  => array(
                    'url'=>  get_template_directory_uri() . '/assets/img/logo/logo-3.png'
                ),
            ),
        )
    ) );


    Redux::setSection( $opt_name, array(
        'title'            => __( 'Breadcrumbs', 'appai' ),
        'id'               => 'breadcrumb_settings',
        'customizer_width' => '450px',
        'fields'           => array(
            array(
                'id'       => 'breadcrumb_on',
                'type'     => 'switch',
                'title'    => esc_html__( 'Breadcrumb On/Off ', 'appai' ),
                'subtitle'    => esc_html__( 'Show/hide the breadcrumb.', 'appai' ),
                'desc'      => esc_html__( 'This setting can be override by page breatdcrumb settings.', 'appai' ),
                'default'     => true,
            ),
            array(
                'id'       => 'breadcrumb_sep',
                'type'     => 'text',
                'title'    => __( 'Breadcrumb Seperator ', 'appai' ),
                'default'     => '-',
                'required'   => array( 'breadcrumb_on', 'equals', true ),
            ),
            array(
                'id'        => 'breadcrumb_background',
                'type'      => 'background',
                'output'    => array('.breadcrumb-area'),
                'title'     => esc_html__( 'Breadcrumb Background', 'appai' ),
                'subtitle'  => esc_html__( 'Customize your breadcrumb background area.', 'appai' ),
                'desc'      => esc_html__( 'This setting can be override by page breatdcrumb settings.', 'appai' ),
                'required'   => array( 'breadcrumb_on', 'equals', true ),
                'default'   => array(
                    'background-color' => '#EFEFEE',
                )
            ),
            array(
                'id'                => 'breadcrumb_spacing',
                'type'              => 'spacing',
                'output'            => array('.breadcrumb-area'),
                'mode'              => 'padding',
                'units'             => array('em', 'px'),
                'units_extended'    => 'false',
                'title'             => esc_html__('Breadcrumb Area Padding', 'appai'),
                'subtitle'          => esc_html__('Please specify breadcrumb area padding.', 'appai'),
                'required'          => array( 'breadcrumb_on', 'equals', true ),
            ),
            array(
                'id'          => 'breadcrumb_typography',
                'type'        => 'typography',
                'title'       => esc_html__('Breadcrumb Typography', 'appai'),
                'google'      => true,
                'font-backup' => true,
                'letter-spacing' => true,
                'output'      => array('.breadcrumb-content .page-cat'),
                'units'       =>'px',
                'subtitle'    => esc_html__('Set typography for breadcrumb title', 'appai'),
                'required'   => array( 'breadcrumb_on', 'equals', true ),

            ),
        )
    ) );




    Redux::setSection( $opt_name, array(
        'title'            => esc_html__( 'Typography', 'appai' ),
        'id'               => 'body_typography',
        'customizer_width' => '450px',
        'fields'           => array(
             array(
                 'id'       => 'bdy_typography',
                'type'        => 'typography',
                'title'       => esc_html__('Body Typography', 'appai'),
                'google'      => true,
                'font-backup' => true,
                'line-height' => true,
                'letter-spacing' => true,
                'output'      => array('body'),
                'units'       =>'px',
                'subtitle'    => esc_html__('Set typography for body', 'appai'),

            ),
             array(
                 'id'       => 'h1_typography',
                'type'        => 'typography',
                'title'       => esc_html__('H1 Typography', 'appai'),
                'google'      => true,
                'font-backup' => true,
                'line-height' => true,
                'letter-spacing' => true,
                'output'      => array('h1, .h1'),
                'units'       =>'px',
                'subtitle'    => esc_html__('Set typography for header h1', 'appai'),

            ),
             array(
                 'id'       => 'h2_typography',
                'type'        => 'typography',
                'title'       => esc_html__('H2 Typography', 'appai'),
                'google'      => true,
                'font-backup' => true,
                'line-height' => true,
                'letter-spacing' => true,
                'output'      => array('h2, .h2'),
                'units'       =>'px',
                'subtitle'    => esc_html__('Set typography for header h2', 'appai'),

            ),
             array(
                 'id'       => 'h3_typography',
                'type'        => 'typography',
                'title'       => esc_html__('H3 Typography', 'appai'),
                'google'      => true,
                'font-backup' => true,
                'line-height' => true,
                'letter-spacing' => true,
                'output'      => array('h3, .h3'),
                'units'       =>'px',
                'subtitle'    => esc_html__('Set typography for header h3', 'appai'),

            ),
             array(
                 'id'       => 'h4_typography',
                'type'        => 'typography',
                'title'       => esc_html__('H4 Typography', 'appai'),
                'google'      => true,
                'font-backup' => true,
                'line-height' => true,
                'letter-spacing' => true,
                'output'      => array('h4, .h4'),
                'units'       =>'px',
                'subtitle'    => esc_html__('Set typography for header h4', 'appai'),

            ),
             array(
                 'id'       => 'h5_typography',
                'type'        => 'typography',
                'title'       => esc_html__('H5 Typography', 'appai'),
                'google'      => true,
                'line-height' => true,
                'font-backup' => true,
                'letter-spacing' => true,
                'output'      => array('h5, .h5'),
                'units'       =>'px',
                'subtitle'    => esc_html__('Set typography for header h5', 'appai'),

            ),
             array(
                 'id'       => 'h6_typography',
                'type'        => 'typography',
                'title'       => esc_html__('H6 Typography', 'appai'),
                'google'      => true,
                'font-backup' => true,
                'line-height' => true,
                'letter-spacing' => true,
                'output'      => array('h6, .h6'),
                'units'       =>'px',
                'subtitle'    => esc_html__('Set typography for header h6', 'appai'),

            )
        )
    ) );





    Redux::setSection( $opt_name, array(
        'title'            => esc_html__( 'Page Settings', 'appai' ),
        'id'               => 'page_setings',
        'desc'             => esc_html__( 'Appai blog settings!', 'appai' ),
        'customizer_width' => '400px',
        'icon'             => 'el el-home',
    ) );


    Redux::setSection( $opt_name, array(
        'title'            => __( 'Shop Page', 'appai' ),
        'id'               => 'shop_page_settings',
        'customizer_width' => '450px',
        'subsection'       => true,
        'fields'           => array(
            array(
                'id'       => 'shop_p_title',
                'type'     => 'text',
                'title'    => esc_html__( 'Shop Page Title', 'appai' ),
                'subtitle'    => esc_html__( 'Give any breadcrumb page title for your shop page.', 'appai' ),
                'default'     => esc_html__('Products', 'appai'),
            ),
            array(
                'id'       => 'product_title',
                'type'     => 'text',
                'title'    => esc_html__( 'Product Page Title', 'appai' ),
                'subtitle'    => esc_html__( 'Give any breadcrumb page title for your single product page.', 'appai' ),
                'default'     => esc_html__('Product Details', 'appai'),
            ),
            array(
                'id'       => 'shop_page_layout',
                'type'     => 'image_select',
                'title'    => esc_html__( 'Shop Page Layout', 'appai' ),
                'subtitle' => esc_html__( 'Choose the layout you want in shop/products pages', 'appai' ),
                'options'          => array(
                    'fullpage' => get_template_directory_uri() .'/assets/img/layouts/fullpage.png"',
                    'left-sidebar' => get_template_directory_uri() .'/assets/img/layouts/left-sidebar.jpg"',
                    'right-sidebar' => get_template_directory_uri() .'/assets/img/layouts/right-sidebar.jpg"',
                ),
                'default'  => 'left-sidebar',
            ),
        )
    ) );

    Redux::setSection( $opt_name, array(
        'title'            => esc_html__( 'Blog Page', 'appai' ),
        'id'               => 'blog_settings',
        'customizer_width' => '450px',
        'subsection'       => true,
        'fields'           => array(
            array(
                'id'       => 'bp_title',
                'type'     => 'text',
                'title'    => esc_html__( 'Blog Page Title', 'appai' ),
                'subtitle'    => esc_html__( 'Give any breadcrumb page title for the blog page.', 'appai' ),
                'default'     => esc_html__('Read the latest <br> from our blog', 'appai'),
            ),
            array(
                'id'       => 'blog_layout',
                'type'     => 'image_select',
                'title'    => esc_html__( 'Blog Layout', 'appai' ),
                'subtitle' => esc_html__( 'Choose the layout you want in blog pages', 'appai' ),
                'options'          => array(
                    'fullpage' => get_template_directory_uri() .'/assets/img/layouts/fullpage.png"',
                    'left-sidebar' => get_template_directory_uri() .'/assets/img/layouts/left-sidebar.jpg"',
                    'right-sidebar' => get_template_directory_uri() .'/assets/img/layouts/right-sidebar.jpg"',
                ),
                'default'  => 'right-sidebar',
            ),
            array(
                'id'       => 'blog_grid',
                'type'     => 'image_select',
                'title'    => esc_html__( 'Blog Grid', 'appai' ),
                'subtitle' => esc_html__( 'Choose the number of column you want in blog pages', 'appai' ),
                'options'          => array(
                    'one-column' => get_template_directory_uri() .'/assets/img/layouts/1-col.png"',
                    'two-column' => get_template_directory_uri() .'/assets/img/layouts/2-col.png"',
                    'three-column' => get_template_directory_uri() .'/assets/img/layouts/3-col.png"',
                ),
                'default'  => 'one-column',
            ),
        )
    ) );


    Redux::setSection( $opt_name, array(
        'title'            => __( 'Single Page', 'appai' ),
        'id'               => 'single_page_settings',
        'customizer_width' => '450px',
        'subsection'       => true,
        'fields'           => array(
            array(
                'id'       => 'sp_title',
                'type'     => 'text',
                'title'    => esc_html__( 'Single Page Title', 'appai' ),
                'subtitle'    => esc_html__( 'Give any breadcrumb page title for the single article page.', 'appai' ),
                'default'     => 'Blog Details',
            ),
            array(
                'id'       => 'single_page_layout',
                'type'     => 'image_select',
                'title'    => esc_html__( 'Single Page Layout', 'appai' ),
                'subtitle' => esc_html__( 'Choose the layout you want in single pages', 'appai' ),
                'options'          => array(
                    'fullpage' => get_template_directory_uri() .'/assets/img/layouts/fullpage.png"',
                    'left-sidebar' => get_template_directory_uri() .'/assets/img/layouts/left-sidebar.jpg"',
                    'right-sidebar' => get_template_directory_uri() .'/assets/img/layouts/right-sidebar.jpg"',
                ),
                'default'  => 'fullpage',
            ),
        )
    ) );


    Redux::setSection( $opt_name, array(
        'title'            => esc_html__( 'Google Apps', 'appai' ),
        'id'               => 'google_apps',
        'desc'             => esc_html__( 'Google Apps', 'appai' ),
        'customizer_width' => '400px',
        'icon'             => 'el el-home'
    ) );


    Redux::setSection( $opt_name, array(
        'title'            => esc_html__( 'Google Api', 'appai' ),
        'id'               => 'google_api',
        'subsection'       => true,
        'customizer_width' => '450px',
        'fields'           => array(
            array(
                'id'       => 'google-api-key',
                'type'     => 'text',
                'title'    => esc_html__( 'Google JavaScript API key', 'appai' ),
                'description' => esc_html__('Add your google javascript api key. Read the theme documentation to learn more about google javascript api key.', 'appai'),
            ),
        )
    ) );



     Redux::setSection( $opt_name, array(
         'title'            => esc_html__( 'Coming Soon', 'appai' ),
         'id'               => 'coming_soon_settings',
         'class'            => 'option-page-layout',
         'customizer_width' => '450px',
         'fields'           => array(
            array(
                'id'       => 'coming_soon_mode',
                'type'     => 'switch',
                'title'    => esc_html__( 'Coming Soon Mode', 'appai' ),
                'subtitle' => esc_html__( 'Enable or Disable Coming Soon Mode', 'appai' ),
                'default'  => false
            ),
            array(
                'id'       => 'csm_title',
                'type'     => 'text',
                'title'    => esc_html__( 'Coming Soon Mode Title', 'appai' ),
                'subtitle' => esc_html__( 'Give the coming soon mode page title', 'appai' ),
                'default'  => 'WE ARE <span>COMING SOON</span>',
                'required'   => array( 'coming_soon_mode', 'equals', true ),
            ),
            array(
                'id'       => 'csm_description',
                'type'     => 'editor',
                'title'    => esc_html__( 'Coming Soon Mode Description', 'appai' ),
                'subtitle' => esc_html__( 'Give the coming soon mode page description', 'appai' ),
                'default'  => '',
                'required'   => array( 'coming_soon_mode', 'equals', true ),
            ),
            array(
                'id'       => 'csm_date',
                'type'     => 'text',
                'title'    => esc_html__( 'Date of lunch', 'appai' ),
                'subtitle' => esc_html__( 'Enter the lunch date of your site', 'appai' ),
                'description' => esc_html__( 'Set your date in this format', 'appai' ),
                'default'  => '2018/11/01',
                'required'   => array( 'coming_soon_mode', 'equals', true ),
            ),

            array(
                'id'       => 'csm_footer_copyright',
                'type'     => 'text',
                'title'    => esc_html__( 'Footer Copyright', 'appai' ),
                'description' => esc_html__('Add Copyright text', 'appai'),
                'default'     => esc_html__('Copyright @ 2017. All right reserved.', 'appai'),
                'required'   => array( 'coming_soon_mode', 'equals', true ),
            ),
            array(
                'id'       => 'csm_footer_shortcode',
                'type'     => 'textarea',
                'title'    => esc_html__( 'Footer Shortcode', 'appai' ),
                'description' => esc_html__('Add any shortcodes like social shortcodes etc', 'appai'),
                'default'     => __('[appai_social_list icon="icofont icofont-social-facebook" link="#"]', 'appai'),
                'required'   => array( 'coming_soon_mode', 'equals', true ),
            ),
         )
     ) );



    Redux::setSection( $opt_name, array(
        'title'            => esc_html__( 'Footer', 'appai' ),
        'id'               => 'footer',
        'desc'             => esc_html__( 'Appai footer settings!', 'appai' ),
        'customizer_width' => '400px',
        'icon'             => 'el el-home',
        'fields'           => array(
            array(
                'id'       => 'footer_copyright',
                'type'     => 'text',
                'title'    => esc_html__( 'Footer Copyright', 'appai' ),
                'description' => esc_html__('Add Copyright text', 'appai'),
                'default'     => esc_html__('Copyright @ 2017. All right reserved.', 'appai'),
            ),

            array(
                'id'       => 'footer_shortcode',
                'type'     => 'textarea',
                'title'    => esc_html__( 'Footer Shortcode', 'appai' ),
                'description' => esc_html__('Add any shortcodes like social shortcodes etc', 'appai'),
                'default'     => __('[appai_social_list icon="icofont icofont-social-facebook" link="#"]', 'appai'),
            ),
        )
    ) );




    /*
     * <--- END SECTIONS
     */


    /*
     *
     * YOU MUST PREFIX THE FUNCTIONS BELOW AND ACTION FUNCTION CALLS OR ANY OTHER CONFIG MAY OVERRIDE YOUR CODE.
     *
     */


    /**
     * This is a test function that will let you see when the compiler hook occurs.
     * It only runs if a field    set with compiler=>true is changed.
     * */
    if ( ! function_exists( 'compiler_action' ) ) {
        function compiler_action( $options, $css, $changed_values ) {
            echo '<h1>The compiler hook has run!</h1>';
            echo "<pre>";
            print_r( $changed_values ); // Values that have changed since the last save
            echo "</pre>";
        }
    }

    /**
     * Custom function for the callback validation referenced above
     * */
    if ( ! function_exists( 'redux_validate_callback_function' ) ) {
        function redux_validate_callback_function( $field, $value, $existing_value ) {
            $error   = false;
            $warning = false;

            //do your validation
            if ( $value == 1 ) {
                $error = true;
                $value = $existing_value;
            } elseif ( $value == 2 ) {
                $warning = true;
                $value   = $existing_value;
            }

            $return['value'] = $value;

            if ( $error == true ) {
                $field['msg']    = 'your custom error message';
                $return['error'] = $field;
            }

            if ( $warning == true ) {
                $field['msg']      = 'your custom warning message';
                $return['warning'] = $field;
            }

            return $return;
        }
    }

    /**
     * Custom function for the callback referenced above
     */
    if ( ! function_exists( 'redux_my_custom_field' ) ) {
        function redux_my_custom_field( $field, $value ) {
            print_r( $field );
            echo '<br/>';
            print_r( $value );
        }
    }

    /**
     * Custom function for filtering the sections array. Good for child themes to override or add to the sections.
     * Simply include this function in the child themes functions.php file.
     * NOTE: the defined constants for URLs, and directories will NOT be available at this point in a child theme,
     * so you must use get_template_directory_uri() if you want to use any of the built in icons
     * */
    if ( ! function_exists( 'dynamic_section' ) ) {
        function dynamic_section( $sections ) {
            //$sections = array();
            $sections[] = array(
                'title'  => __( 'Section via hook', 'appai' ),
                'desc'   => __( '<p class="description">This is a section created by adding a filter to the sections array. Can be used by child themes to add/remove sections from the options.</p>', 'appai' ),
                'icon'   => 'el el-paper-clip',
                // Leave this as a blank section, no options just some intro text set above.
                'fields' => array()
            );

            return $sections;
        }
    }

    /**
     * Filter hook for filtering the args. Good for child themes to override or add to the args array. Can also be used in other functions.
     * */
    if ( ! function_exists( 'change_arguments' ) ) {
        function change_arguments( $args ) {
            //$args['dev_mode'] = true;

            return $args;
        }
    }

    /**
     * Filter hook for filtering the default value of any given field. Very useful in development mode.
     * */
    if ( ! function_exists( 'change_defaults' ) ) {
        function change_defaults( $defaults ) {
            $defaults['str_replace'] = 'Testing filter hook!';

            return $defaults;
        }
    }

    /**
     * Removes the demo link and the notice of integrated demo from the redux-framework plugin
     */
    if ( ! function_exists( 'remove_demo' ) ) {
        function remove_demo() {
            // Used to hide the demo mode link from the plugin page. Only used when Redux is a plugin.
            if ( class_exists( 'ReduxFrameworkPlugin' ) ) {
                remove_filter( 'plugin_row_meta', array(
                    ReduxFrameworkPlugin::instance(),
                    'plugin_metalinks'
                ), null, 2 );

                // Used to hide the activation notice informing users of the demo panel. Only used when Redux is a plugin.
                remove_action( 'admin_notices', array( ReduxFrameworkPlugin::instance(), 'admin_notices' ) );
            }
        }
    }
