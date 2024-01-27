<?php
// add_filter( 'body_class', 'wpl_custom_class' );
// function wpl_custom_class( $classes ) {
//     $classes[] = 'wpl_location_filter';
// 	return $classes;
// }


function shpost_show_custom_post_for_users( $query ) {
    // Get the current user ID
    $user_id = get_current_user_id();
    // If the user is not logged in, return
    if ( ! $user_id ) {
        return $query;
    }

    $settings = get_option( 'shpost_settings' );

    $post_types = isset($settings['shpost_post_type_option']) ? $settings['shpost_post_type_option'] : 'property';

    $post_type_arr = explode(',', $post_types);

    $assigned = false;
    foreach($post_type_arr as $post_type){
        if($query->get( 'post_type' ) == trim($post_type)){
            $assigned = true;
        }
    }

    if ( $assigned == true ) {

        // If the user is not an administrator, modify the query
        if ( ! current_user_can( 'manage_options' ) ) {
            $meta_query = array(
                array(
                    'key'     => 'visible_for_users',
                    'value'   => $user_id,
                    'compare' => 'LIKE',
                ),
            );
            $query->set( 'meta_query', $meta_query );
        }

    }
	
// 	update_option('test_debug', $assigned);

    return $query;
}
add_action( 'pre_get_posts', 'shpost_show_custom_post_for_users' );

// add_action( 'wp_footer', 'test_debug_footer' );
// function test_debug_footer(){
// 	$test_debug = get_option('test_debug');
	
// 	echo '<pre>';
// 	var_dump($test_debug);
// 	echo '</pre>';
// }




