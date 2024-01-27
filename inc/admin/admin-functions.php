<?php


####################################################
## Flyer date metabox
####################################################

add_action('add_meta_boxes', 'shpost_meta_boxes');
function shpost_meta_boxes()
{

    $settings = get_option( 'shpost_settings' );

    $post_types = isset($settings['shpost_post_type_option']) ? $settings['shpost_post_type_option'] : 'property';

    $post_type_arr = explode(',', $post_types);

    $post_type_new_arr = array();

    foreach($post_type_arr as $post_type){
        array_push($post_type_new_arr, trim($post_type));
    }

    add_meta_box(
        'shpost_meta_box', 
        'SHPost Options', 
        'shpost_meta_box_calback',
        $post_type_new_arr, 
        'normal', 
        'high' 
    );
    
    function shpost_meta_box_calback()
    {
        

?>
	<div class="form_row"> 
		<label for="visible_for"><?php _e('Visible this item for', 'shpost');?></label>
        <!-- <select name="visible_for_role" id="visible_for_role">
            <option value="">Select role</option>
            <?php
            global $wp_roles;
            foreach($wp_roles->roles as $role_key => $role_value){
                if($role_key == 'subscriber'){
                    echo '<option value="'.$role_key.'" selected>' . $role_value["name"] . '</option>';
                }else{
                    echo '<option value="'.$role_key.'">' . $role_value["name"] . '</option>';
                }
                
            }
            
            ?>
        </select> -->

        <div class="selected_users">
            <div class="user_items">

            <?php 
$users = get_post_meta(get_the_ID(), 'visible_for_users', true);

wp_localize_script( 'shpost-admin-script', 'shpost_script_object', array( 
    'assigned_users' => $users, 
));

// var_dump($users);

$itemhtml = '';
if($users){
    foreach($users as $user_id){
        $user = get_user_by( 'ID', $user_id );
        $itemhtml .= '<span class="single_user_items" data-userid="'.$user_id.'" data-username="'.$user->user_login.'">' . $user->user_login . '<span class="item_remove">x</span></span>';
    }
}


if($itemhtml){
    echo $itemhtml;
}else{
    echo ' <span class="single_user_items no_item_notice" data-userid="" data-username="">No users selected</span>';
}
            ?>


            </div>
        </div>

		<select name="visible_for" id="visible_for" class="regular-text">
            <option value="">Select user</option>
            <?php 
                $users = get_users();
                foreach($users as $user){
                    echo '<option value="'.$user->ID.'">' . esc_html( $user->display_name ) . '</option>';;
                }

            ?>
        </select>
        <input type="hidden" name="shpost_id" id="post_id" value="<?php echo get_the_ID();?>">
        <input type="hidden" id="visible_for_users" name="visible_for_users" value="">
        <button id="assign_user" class="button">Add</button>
    </div>

        <?php
    }
}

// add_action('save_post', 'shpost_meta_box_save');
// function shpost_meta_box_save($post_id)
// {

// 	// make sure the field is set.
// 	if ( ! isset( $_POST['visible_for_users'] ) )
// 		return;

// 	// if this is autosave do nothing
// 	if ( defined('DOING_AUTOSAVE') && DOING_AUTOSAVE ) 
// 		return;

// 	// check user permission
// 	if( ! current_user_can( 'edit_post', $post_id ) )
// 		return;
// 	update_post_meta($post_id, 'visible_for_users', $_REQUEST['visible_for_users']);
    
// }




// add_action( 'wp_ajax_nopriv_shpost_user_selection', 'shpost_user_selection' );
add_action( 'wp_ajax_shpost_user_selection', 'shpost_user_selection' );
function shpost_user_selection(){

    $users = isset($_POST['users']) ? $_POST['users'] : [];
    $post_id = isset($_POST['post_id']) ? $_POST['post_id'] : '';

    update_post_meta($post_id, 'visible_for_users', $users);

    $users = get_post_meta($post_id, 'visible_for_users', true);

    $itemhtml = '';

    if($users){
        foreach($users as $user_id){
            $user = get_user_by( 'ID', $user_id );
            $itemhtml .= '<span class="single_user_items" data-userid="'.$user_id.'" data-username="'.$user->user_login.'">' . $user->user_login . '<span class="item_remove">x</span></span>';
        }
    }else{
        $itemhtml = '<span class="single_user_items no_item_notice" data-userid="" data-username="">No users selected</span>';
    }
    

    echo $itemhtml;

    // Terminate the request
    wp_die();

}