<header class="<?php echo implode(' ', appai_set_header_class() ); ?>">
    <nav class="navbar"  data-spy="affix" data-offset-top="1">
        <div class="container">
            <div class="navbar-header">

                <button type="button" class="navbar-toggle main-header-navbar-toggle collapsed" data-toggle="collapse" data-target="#navigation" aria-expanded="false">
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>

                <a href="<?php echo esc_html(home_url('/')); ?>"  class="navbar-brand">
                    <?php echo appai_get_site_logo(); ?>
                </a>

            </div>
            <div class="collapse navbar-collapse" id="navigation">
              <?php
                  if( function_exists('wp_nav_menu') ) {
                      wp_nav_menu(array(
                          'theme_location' => 'primary-menu',
                          'menu_class' => 'nav navbar-nav navbar-right',
                          'menu_id' => 'mainmenu',
                          'fallback_cb'  => 'appai_link_to_menu_editor',
                          'walker'       =>  new Appai_Nav_Menu_Walker,

                      ));
                  }
              ?>
            </div>
        </div>
    </nav>
</header>
