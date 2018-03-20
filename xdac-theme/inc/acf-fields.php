<?php

// Disable the Menu  Page Item


if(function_exists("register_field_group"))
{
	register_field_group(array (
		'id' => 'acf_blog-options',
		'title' => 'Blog Options',
		'fields' => array (
			array (
				'key' => 'field_59bd1051e822e',
				'label' => 'Blog sidebar',
				'name' => 'page_blog_sidebar',
				'type' => 'radio',
				'instructions' => 'Select the blog sidebar layout',
				'choices' => array (
					'left-sidebar' => 'Left sidebar <img src="'. get_template_directory_uri() .'/assets/img/layouts/left-sidebar.jpg" alt="Left sidebar" class="radio-img">',
					'right-sidebar' => 'Right sidebar <img src="'. get_template_directory_uri() .'/assets/img/layouts/right-sidebar.jpg" alt="Right sidebar" class="radio-img">',
					'fullpage' => 'Full page<img src="'. get_template_directory_uri() .'/assets/img/layouts/fullpage.png" alt="Full page" class="radio-img">',
				),
				'other_choice' => 0,
				'save_other_choice' => 0,
				'default_value' => '',
				'layout' => 'vertical',
			),
		),
		'location' => array (
			array (
				array (
					'param' => 'post_type',
					'operator' => '==',
					'value' => 'post',
					'order_no' => 0,
					'group_no' => 0,
				),
			),
		),
		'options' => array (
			'position' => 'side',
			'layout' => 'default',
			'hide_on_screen' => array (
			),
		),
		'menu_order' => 0,
	));
	register_field_group(array (
		'id' => 'acf_page-options',
		'title' => 'Page Options',
		'fields' => array (
			array (
				'key' => 'field_599a7a6bf92ac',
				'label' => 'Header Layout',
				'name' => '',
				'type' => 'tab',
			),
			array (
				'key' => 'field_599d1fdcda31b',
				'label' => 'Standard Menu Scheme',
				'name' => 'menu_scheme',
				'type' => 'select',
				'instructions' => 'Select the menu color scheme for this specific page',
				'choices' => array (
					'inherit' => 'Use from theme options',
					'turquoise' => 'Turquoise',
					'red' => 'Red',
					'black' => 'Black',
					'white' => 'White',
					'transparent' => 'Transparent',
				),
				'default_value' => '',
				'allow_null' => 0,
				'multiple' => 0,
			),
			array (
				'key' => 'field_59b780241fe89',
				'label' => 'Sticky Menu Scheme',
				'name' => 'sticky_menu_scheme',
				'type' => 'select',
				'instructions' => 'Select the menu color scheme for this specific page.
	This menu color scheme is for sticky menu, when user scrolls down it sticks on top.',
				'choices' => array (
					'inherit' => 'Use from theme options',
					'turquoise' => 'Turquoise',
					'red' => 'Red',
					'black' => 'Black',
					'white' => 'White',
				),
				'default_value' => 'inherit',
				'allow_null' => 0,
				'multiple' => 0,
			),
			array (
				'key' => 'field_59b798de74442',
				'label' => 'Custom Logo',
				'name' => '',
				'type' => 'tab',
			),
			array (
				'key' => 'field_59b7988874440',
				'label' => 'Select Logo',
				'name' => 'page_custom_logo',
				'type' => 'image',
				'instructions' => 'Select and upload the custom logo for this specific page. It will override the default site logo.',
				'save_format' => 'url',
				'preview_size' => 'full',
				'library' => 'all',
			),
			array (
				'key' => 'field_599abe9d4f320',
				'label' => 'Header Position',
				'name' => '',
				'type' => 'tab',
			),
			array (
				'key' => 'field_599abeb04f321',
				'label' => 'Choose Header Position',
				'name' => 'choose_header_position',
				'type' => 'select',
				'instructions' => 'Please select header position',
				'choices' => array (
					'inherit' => 'Use from Theme Options',
					'static' => 'Content Below (Static)',
					'absolute' => 'Over the Content (Absolute)',
				),
				'default_value' => 'inherit',
				'allow_null' => 0,
				'multiple' => 0,
			),
			array (
				'key' => 'field_599bae2295215',
				'label' => 'Header Visibility',
				'name' => '',
				'type' => 'tab',
			),
			array (
				'key' => 'field_599bae3195216',
				'label' => 'Choose Header Visibility',
				'name' => 'choose_header_visibility',
				'type' => 'select',
				'instructions' => 'Select header visibility for only this page',
				'choices' => array (
					'inherit' => 'Use from theme options',
					'visible' => 'Visible',
					'hidden' => 'Hidden',
				),
				'default_value' => 'inherit',
				'allow_null' => 0,
				'multiple' => 0,
			),
			array (
				'key' => 'field_59a3060c1539f',
				'label' => 'Footer',
				'name' => '',
				'type' => 'tab',
			),
			array (
				'key' => 'field_59a3063162797',
				'label' => 'Footer Color Scheme',
				'name' => 'footer_color_scheme',
				'type' => 'select',
				'instructions' => 'Choose footer color scheme for this specific page',
				'choices' => array (
					'inherit' => 'Use from theme options',
					'black' => 'Black',
					'white' => 'White',
				),
				'default_value' => 'inherit',
				'allow_null' => 0,
				'multiple' => 0,
			),
			array (
				'key' => 'field_59b81b4df1ab1',
				'label' => 'Footer Top Padding',
				'name' => 'footer_top_padding',
				'type' => 'number',
				'instructions' => 'Give footer top padding in px value.',
				'default_value' => 0,
				'placeholder' => '',
				'prepend' => '',
				'append' => '',
				'min' => '',
				'max' => '',
				'step' => '',
			),
			array (
				'key' => 'field_59b81e9aacab4',
				'label' => 'Footer Bottom Padding',
				'name' => 'footer_bottom_padding',
				'type' => 'number',
				'instructions' => 'Give footer top padding in px value.',
				'default_value' => 0,
				'placeholder' => '',
				'prepend' => '',
				'append' => '',
				'min' => '',
				'max' => '',
				'step' => '',
			),
			array (
				'key' => 'field_59a3068462798',
				'label' => 'Hide footer top',
				'name' => 'hide_footer_top',
				'type' => 'true_false',
				'instructions' => 'Check to hide footer top',
				'message' => 'Hide',
				'default_value' => 0,
			),
			array (
				'key' => 'field_59a3069a62799',
				'label' => 'Hide footer bottom',
				'name' => 'hide_footer_bottom',
				'type' => 'true_false',
				'instructions' => 'Check to hide footer bottom',
				'message' => 'Hide',
				'default_value' => 0,
			),
		),
		'location' => array (
			array (
				array (
					'param' => 'post_type',
					'operator' => '==',
					'value' => 'page',
					'order_no' => 0,
					'group_no' => 0,
				),
			),
		),
		'options' => array (
			'position' => 'normal',
			'layout' => 'default',
			'hide_on_screen' => array (
			),
		),
		'menu_order' => 0,
	));
}
