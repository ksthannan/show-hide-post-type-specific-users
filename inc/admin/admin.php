<?php 
add_action('admin_menu', 'shpost_admin_menu');
function shpost_admin_menu(){
	$parent_slug = 'options-general.php';
    $capability = 'manage_options';
//     add_menu_page( __('Show Hide Post Type Specific Users Settings', 'shpost'), esc_html('SHPost Settings', 'shpost'), $capability, 'show-hide-post-type-specific-users', 'shpost_plugin_settings');
    add_submenu_page( $parent_slug, __('SHPost Settings', 'shpost'), esc_html('SHPost Settings', 'shpost'), $capability, 'show-hide-post-type-specific-users', 'shpost_plugin_settings');
    
}

function shpost_plugin_settings()
{
?>
<form method="POST" action="options.php">
<?php
    settings_fields('shpost_plugin_opt');
    do_settings_sections('shpost_plugin_opt');

    submit_button();
    ?>
</form>

<div class="documentations">
	<div class="doc_content">
		<p>The users can be assigned from any post edit page's meta box option.</p>
		<a href="<?php echo SHPOST_ASSETS . '/img/documentation_screenshot.png';?>"><img src="<?php echo SHPOST_ASSETS . '/img/documentation_screenshot.png';?>" width="" height="250" alt=""></a>
	</div>
</div>

<?php 
}

/***  */
add_action( 'admin_init', 'shpost_settings_init' );
function shpost_settings_init(  ) { 
    register_setting( 'shpost_plugin_opt', 'shpost_settings' );

    add_settings_section(
        'shpost_plugin_opt_section', 
        __( 'Show Hide Post Type Specific Users Settings', 'shpost' ), 
        'shpost_settings_section_callback', 
        'shpost_plugin_opt'
    );
	
	add_settings_field( 
		'shpost_post_type_option', 
		__( 'Custom post types', 'cmd' ), 
		'shpost_post_type_option_render', 
		'shpost_plugin_opt', 
		'shpost_plugin_opt_section'
	);
	
}

function shpost_settings_section_callback(){
	return;
}

function shpost_post_type_option_render(){
	$options = get_option( 'shpost_settings' );
	?>
<p>
	post_type1, post_type2, post_type3... <br> (Default: property)
</p><br>
	<input type="text" name="shpost_settings[shpost_post_type_option]" id="shpost_post_type_option" class="regular-text" placeholder="post_type1, post_type2, post_type3..." value="<?php echo isset($options['shpost_post_type_option']) ? $options['shpost_post_type_option'] : '';?>">



	<?php
}


