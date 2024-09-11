<?php 


add_action('wp_enqueue_scripts', 'enqueue_parent_styles');

function enqueue_parent_styles(){
    // Enqueue parent theme stylesheet
    wp_enqueue_style('parent-style', get_template_directory_uri() . '/style.css');
    wp_enqueue_style('child-style', get_stylesheet_directory_uri() . '/style.css', array('parent-style'));
// Enqueue child theme JavaScript file
   // Enqueue child theme JavaScript file without jQuery as a dependency
   wp_enqueue_script('child-script', get_stylesheet_directory_uri() . '/script.js', array(), null, true);
};


 // Add shortcode for ACF field
 function display_acf_field1($atts) {
    // Define default attributes (arguments)
    $atts = shortcode_atts(
        array(
            'post_type'=>'post',
            'field' => '',  // Default value for 'field'
            'post_title' => '',  // Default value for 'post_title'
        ),
        $atts,  // Attributes passed by the user
        'acf_field_total_enrollment' // The shortcode tag
    );
    
    // Fetch the post by title
    $post = get_page_by_title($atts['post_title'], OBJECT, $atts['post_type']); // You can specify the post type if needed
    
    // Check if post is found
    if ($post) {
        $post_id = $post->ID; // Get the post ID
        
        // Fetch the ACF field value using the provided field name and post ID
        $field_value = get_field($atts['field'], $post_id);
        
        // Return the field value or a default message if the field is empty
        return !empty($field_value) ? $field_value : 'Field not found or empty';
    } else {
        return 'Post not found';
    }
}
//add_shortcode('acf_field_total_enrollment_2024-2025', 'display_acf_field1');

 // Add shortcode for ACF field
 function display_acf_field($atts) {

   
    // Define default attributes (arguments)
    $atts = shortcode_atts(
        array(
            'post_type'=>'',
            'field' => '',  // Default value for 'field'
            'post_title' => '',  // Default value for 'post_title'
        ),
        $atts,  // Attributes passed by the user
        'acf_field_total_enrollment' // The shortcode tag
    );
    
    // Fetch the post by title
    $post = get_page_by_title($atts['post_title'], OBJECT, $atts['post_type']); // You can specify the post type if needed
    
    // Check if post is found
    if ($post) {
        $post_id = $post->ID; // Get the post ID
        
        // Fetch the ACF field value using the provided field name and post ID
        $field_value = get_field($atts['field'], $post_id);
        
        // Return the field value or a default message if the field is empty
        return !empty($field_value) ? $field_value : 'Field not found or empty';
    } else {
        return 'Post not found';
    }
}

add_shortcode('acf_field_total_enrollment', 'display_acf_field');


function hide_permalink_for_specific_post_type_css() {
    global $post;
    if($post == null){
        return "";
    }
    $user_grade_level = get_user_meta( get_current_user_id(), 'grade_level', true );
    // Check if the post type is your specific post type 
    if( $user_grade_level !== 'Administrative Officer' && !current_user_can('administrator') ){
        add_filter('screen_options_show_screen', '__return_false');
        echo '
        <style>
            
         
       
        
               
            /* This ensures that the styles work across all screen sizes */
        @media screen and (max-width: 10000px) {
            .wrap .page-title-action, #edit-slug-box, #delete-action, .row-actions,
            #wp-admin-bar-new-content, #wp-admin-bar-comments, #misc-publishing-actions {
                display: none !important;
            }
             
        }
        </style>';

        echo '
        <script type="text/javascript">
        jQuery(document).ready(function($) {
            $("#title").attr("readonly", "readonly");  // Make title read-only
        });
        </script>';
    } 
}
 
 
function restrict_add_new_post() {
    // Check if the user is not an administrator
    if (current_user_can('administrator')) {
        add_action('admin_head', 'hide_permalink_for_specific_post_type_css');// development mode only
    }else{
        add_action('admin_head', 'hide_permalink_for_specific_post_type_css');
    }
}
add_action('admin_menu', 'restrict_add_new_post');

function hide_all_submenus_with_js() {
    ?>
    <script type="text/javascript">
        jQuery(document).ready(function($) {
            // Hide the "Add New" submenu
            $('a[href^="post-new.php?post_type="]').parent().remove();
            
        
        });
    </script>
    <?php
}
add_action('admin_footer', 'hide_all_submenus_with_js');


function hide_posts_for_non_admins( $query ) {

        // Check if the current user is not an administrator
        if ( is_admin() && $query->is_main_query() && !current_user_can('administrator') ) {

            $user_grade_level = get_user_meta( get_current_user_id(), 'grade_level', true );
          
            if ( $user_grade_level == "None" ) {
                $query->set('post__in', array(0)); // If no posts match, show none
                return false; // Stops further execution
            }
            
            if ( $user_grade_level == "Administrative Officer" ) {
              
                return false; // Stops further execution
            }
            $display_posts = get_posts(array(
                'post_type'   => get_post_types(),
                'fields'      => 'ids',
                's'           => $user_grade_level, // Search by title
                'posts_per_page' => -1 // Get all matching posts
            ));

            // Modify the query to exclude those posts
            $query->set('post__in', $display_posts);

            
        }
  
}
add_action( 'pre_get_posts', 'hide_posts_for_non_admins' );





function hide_menus_for_non_admin_officers() {
    // Get the current user's grade level
    $user_grade_level = get_user_meta( get_current_user_id(), 'grade_level', true );

    // Check if the user is not an 'Administrative Officer' and not an administrator
    if ( $user_grade_level !== 'Administrative Officer' && !current_user_can('administrator') ) {

        // Remove the dashboard menu items
        remove_menu_page( 'index.php' );                  // Dashboard
        remove_menu_page( 'edit.php' );                   // Posts
        remove_menu_page( 'upload.php' );                 // Media
        remove_menu_page( 'edit.php?post_type=page' );    // Pages
        remove_menu_page( 'edit-comments.php' );          // Comments
        remove_menu_page( 'themes.php' );                 // Appearance
        remove_menu_page( 'plugins.php' );                // Plugins
        remove_menu_page( 'users.php' );                  // Users
        remove_menu_page( 'tools.php' );                  // Tools
        remove_menu_page( 'options-general.php' );        // Settings
        remove_menu_page( 'templates.php' ); 
        remove_menu_page( 'profile.php' ); 
        // If you have other custom post types, remove them as well
        // remove_menu_page( 'edit.php?post_type=your_custom_post_type' );

        // Keep the menu for '2024-2025' post type
        // Make sure 'edit.php?post_type=2024-2025' is not removed

        remove_menu_page( 'elementor' );                   // Elementor Dashboard
        remove_submenu_page( 'elementor', 'elementor_templates' ); // Elementor Templates submenu
        
        // Remove Elementor's template library option under the "Templates" menu
        remove_menu_page( 'edit.php?post_type=elementor_library' ); // Elementor Templates
    }
}
add_action( 'admin_menu', 'hide_menus_for_non_admin_officers', 999 );


// Create a shortcode to display the post type name
function display_post_type_name() {
    global $post;

    // Check if post exists
    if (!$post) {
        return '';
    }

    // Get the current post type
    $post_type = get_post_type($post->ID);

    // Get the post type object
    $post_type_object = get_post_type_object($post_type);

    // Check if post type object exists
    if ($post_type_object) {
        // Return the post type's singular name
        return esc_html($post_type_object->labels->singular_name);
    }

    return '';
}

// Register the shortcode
add_shortcode('post_type_name', 'display_post_type_name');
 

function expose_custom_fields_to_rest() {
    // Define your custom post type (sy2024-2026)
    $post_type = 'sy2024-2026'; 

    // Add support for custom fields in the REST API for the custom post type
    register_rest_field(
        $post_type, // Your custom post type
        'custom_fields', // The key you want to return in the REST API
        array(
            'get_callback'    => 'get_custom_fields_for_api', // Function to get custom field value
            'update_callback' => null,
            'schema'          => null,
        )
    );
}

add_action('rest_api_init', 'expose_custom_fields_to_rest');

// Callback function to get custom field values
function get_custom_fields_for_api($object) {
    $post_id = $object['id'];
    // Retrieve all custom fields for the post
    return get_post_meta($post_id);
}
