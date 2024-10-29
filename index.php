<?php
/*
Plugin Name: Advert Preloader
Plugin URI: http://ivanmaricic.from.hr/
Description: AdvertLoader is a plugin designed to increase your earnings by using adverts which will be shown to users just before all other content loads. The plugin can also be used to notify users about some important information before showing the page content. 
Version: 0.1
Author: Ivan M
*/

require_once 'includes/functions.php';

/*******************************************************************************
 *  Load jQuery
 *******************************************************************************/
add_action( 'wp_enqueue_script', 'AdvertPreloader_load_jquery' );
function AdvertPreloader_load_jquery() {
    wp_enqueue_script( 'jquery' );
}

/*******************************************************************************
 *  add menu item
 ******************************************************************************/
add_action( 'admin_menu', 'advert_loader_plugin_menu' );
function advert_loader_plugin_menu() {
    add_menu_page(__('Advert Preloader','advert_loader_options'), __('Advert Preloader','advert_loader_options'), 'manage_options', 'advert_loader', 'advert_loader_plugin_options' );
    // include css and javascript only for plugin pages
    if(addslashes($_GET['page']) === "advert_loader"){
        // load bootstrap js and CSS
        wp_register_script( 'BootstrapAdvertPreloader', plugins_url('js/bootstrap.js?v=1', __FILE__) );
        wp_enqueue_script( 'BootstrapAdvertPreloader' );
        
        wp_register_style( 'BootstrapAdvertPreloaderStylesheet', plugins_url('css/bootstrap.css?v=1', __FILE__) );
        wp_enqueue_style( 'BootstrapAdvertPreloaderStylesheet' );
    }
    
}



/*******************************************************************************
* Register with hook 'wp_enqueue_scripts', which can be used for front end CSS and JavaScript
* Enqueue plugin style-file
*******************************************************************************/
add_action( 'wp_enqueue_scripts', 'AdvertPreloader_addcss' );
function AdvertPreloader_addcss() {
    // load custom css on all pages
    wp_register_style( 'AdvertPreloaderStylesheet', plugins_url('css/custom.css?v=1', __FILE__) );
    wp_enqueue_style( 'AdvertPreloaderStylesheet' );
    // load custom javascript on all pages
    wp_register_script( 'AdvertPreloaderJavascriptCustom', plugins_url('js/custom.js?v=1', __FILE__),array( 'jquery' ) );
    wp_enqueue_script( 'AdvertPreloaderJavascriptCustom' );
    
}



/*******************************************************************************
 *  menu page - config
 ******************************************************************************/
function advert_loader_plugin_options(){
    
    // if form submited update options, otherwise show form
    if(isset($_POST['submit_settings'])){
        update_option('advert_preloader_all_options', serialize($_POST));
        ?><meta http-equiv="refresh" content="0; url=?page=advert_loader" /><?php
    } else {
        // show template header
        include("templates/header_tpl.php");

        // show config template
        include("templates/home_tpl.php");

        // show template footer
        include("templates/footer_tpl.php");
    }
}


/*******************************************************************************
 * Add meta box inside Edit and Add new post page 
 ******************************************************************************/

// Fire our meta box setup function on the post editor screen. 
add_action( 'load-post.php', 'preloader_advert_meta_boxes_setup' );
add_action( 'load-post-new.php', 'preloader_advert_meta_boxes_setup' );

// Meta box setup function.
function preloader_advert_meta_boxes_setup() {

    // Add meta boxes on the 'add_meta_boxes' hook. 
    add_action( 'add_meta_boxes', 'preloader_advert_add_post_boxes' );

    // Save post meta on the 'save_post' hook. 
    add_action( 'save_post', 'preloader_advert_save_post_meta', 10, 2 );
}

// Create one or more meta boxes to be displayed on the post editor screen. 
function preloader_advert_add_post_boxes() {
    $screens = array( 'post', 'page' ); // show on posts and pages
    foreach ($screens as $screen) {
        add_meta_box(
            'preloader-advert-option',// Unique ID
            esc_html__( 'AdvertPreloader options', 'preloader_advert_title' ), // Title
            'preloader_advert_meta_box',// Callback function
            $screen,// Admin page (or post type)
            'side',// Context
            'default'// Priority
        );
    }
}

// Display the post meta box. 
function preloader_advert_meta_box( $object ) { 
    
    wp_nonce_field( basename( __FILE__ ), 'preloader_advert_option_nonce' );
    $show_options = GetPreloaderOptions("preloader_show_on");
    if($show_options === 'selected_posts'){
        
        $selected_value = esc_attr( get_post_meta( $object->ID, 'preloader_advert_option', true ));
        ?>
        <?php _e( 'Show AdvertPreloader for this post?', 'AdvertLoader' ); ?>  <br>
        <input type="radio" <?php if($selected_value === 'yes'){echo "checked";} ?> name="preloader-advert-option" value="yes"> <?php _e( 'Yes', 'AdvertLoader' ); ?> 
        <input type="radio" <?php if($selected_value !== 'yes'){echo "checked";} ?> name="preloader-advert-option" value="no"> <?php _e( 'No', 'AdvertLoader' ); ?>
        <?php
    } else {
        _e( __('You have to select to show loader on %s to change this option.', 'AdvertLoader'), '<a target="_blank" href="admin.php?page=advert_loader">' . __( 'only on selected posts and pages', 'AdvertLoader' ) . '</a>' );
    }

}

// Save the meta box's post metadata.
function preloader_advert_save_post_meta( $post_id ) {

    // Verify the nonce before proceeding. 
    if ( !isset( $_POST['preloader_advert_option_nonce'] ) || !wp_verify_nonce( $_POST['preloader_advert_option_nonce'], basename( __FILE__ ) ) ){
        return $post_id;
    }
    
    // Get the post type object. 
    $post_type = get_post_type_object( $post->post_type );

    /*
    // Check if the current user has permission to edit the post. 
    if ( !current_user_can( $post_type->cap->edit_post, $post_id ) ){
        var_dump("permission problem");
        return $post_id;
    }
    */
    // Return if it's a post revision
    if ( false !== wp_is_post_revision( $post_id ) ){
        return $post_id;
    }
    
    // Get the posted data and sanitize it for use as an HTML class. 
    $new_meta_value = ( isset( $_POST['preloader-advert-option'] ) ? sanitize_html_class( $_POST['preloader-advert-option'] ) : '' );

    // Get the meta key. 
    $meta_key = 'preloader_advert_option';

    // Get the meta value of the custom field key. 
    $meta_value = get_post_meta( $post_id, $meta_key, true );

    // If a new meta value was added and there was no previous value, add it. 
    if ( $new_meta_value && '' == $meta_value ){
        add_post_meta( $post_id, $meta_key, $new_meta_value, true );
    }
    // If the new meta value does not match the old value, update it. 
    else if ( $new_meta_value && $new_meta_value != $meta_value ){
        update_post_meta( $post_id, $meta_key, $new_meta_value );
    }
    // If there is no new meta value but an old value exists, delete it. 
    else if ( '' == $new_meta_value && $meta_value ){
        delete_post_meta( $post_id, $meta_key, $meta_value );
    }
}

// add the_content filter to replace content with preloader
add_filter('the_content', 'preloader_advert_add_to_content');
function preloader_advert_add_to_content($content = ''){
    
	// get current post ID
        $post_id = get_the_ID();
        if ( !empty( $post_id ) ) {
            
            // first get options from meta and preloader options
            $show_preloader_for_specific_post = get_post_meta( $post_id, 'preloader_advert_option', true );
            $show_preloader_basic_option = GetPreloaderOptions("preloader_show_on");
            
            // Where to show preloader? 
            if((is_single($post_id) || is_page($post_id)) && GetPreloaderOptions("preloader_show_on_page_type")==='only_full_content'){

                $preloader_HTML = generate_advert_preloader_HTML();
                
                if($show_preloader_basic_option === 'selected_posts'){
                    if($show_preloader_for_specific_post === 'yes'){
                        // add preloader here
                        $content = $preloader_HTML.'<div class="AdvertPreloaderHide" style="display: none;">'.$content.'</div>';
                    }
                } else if ($show_preloader_basic_option === 'all_posts_and_pages'){
                    // add preloader here
                    $content = $preloader_HTML.'<div class="AdvertPreloaderHide" style="display: none;">'.$content.'</div>';
                } else if($show_preloader_basic_option === 'all_posts' && get_post_type( $post_id ) === 'post'){
                    // add preloader here
                    $content = $preloader_HTML.'<div class="AdvertPreloaderHide" style="display: none;">'.$content.'</div>';
                } else if($show_preloader_basic_option === 'all_pages' && get_post_type( $post_id ) === 'page'){
                    // add preloader here
                    $content = $preloader_HTML.'<div class="AdvertPreloaderHide" style="display: none;">'.$content.'</div>';
                } else {
                    // do nothing, content stay as it is
                }
                
            } else if(GetPreloaderOptions("preloader_show_on_page_type")==='full_content_and_homepage'){
                
                $preloader_HTML = generate_advert_preloader_HTML();


                if($show_preloader_basic_option === 'selected_posts'){
                    if($show_preloader_for_specific_post === 'yes'){
                        // add preloader here
                        $content = $preloader_HTML.'<div class="AdvertPreloaderHide" style="display: none;">'.$content.'</div>';
                    }
                } else if ($show_preloader_basic_option === 'all_posts_and_pages'){
                    // add preloader here
                    $content = $preloader_HTML.'<div class="AdvertPreloaderHide" style="display: none;">'.$content.'</div>';
                } else if($show_preloader_basic_option === 'all_posts' && get_post_type( $post_id ) === 'post'){
                    // add preloader here
                    $content = $preloader_HTML.'<div class="AdvertPreloaderHide" style="display: none;">'.$content.'</div>';
                } else if($show_preloader_basic_option === 'all_pages' && get_post_type( $post_id ) === 'page'){
                    // add preloader here
                    $content = $preloader_HTML.'<div class="AdvertPreloaderHide" style="display: none;">'.$content.'</div>';
                } else {
                    // do nothing, content stay as it is
                }
                
            } else if(GetPreloaderOptions("preloader_show_on_page_type")==='disable_advert_preloader'){
                // do nothing for now
            } else {
                // do nothing for now
            }
            
            
        }
        
        
        
        return $content;

}


// generate preloader HTML
function generate_advert_preloader_HTML(){
    
    $plugin_dir = plugin_dir_url( __FILE__ );
    
    // prepare loading message
    if(GetPreloaderOptions("preloader_title")){
        $loader_title = GetPreloaderOptions("preloader_title");
    } else {
        $loader_title = 'Loading, please wait...';
    }
    
    // prepare loading completed message
    if(GetPreloaderOptions("preloader_title_completed")){
        $loader_title_completed = GetPreloaderOptions("preloader_title_completed");
    } else {
        $loader_title_completed = 'Loading Completed';
    }
    
    // prepare loader advert code
    if(GetPreloaderOptions("preloader_banner_code")){
        $loader_advert_code = GetPreloaderOptions("preloader_banner_code");
    } else {
        $loader_advert_code = '<img src="http://placehold.it/300x250">';
    }
    
    // prepare loader button text
    if(GetPreloaderOptions("preloader_button")){
        $loader_button_text = GetPreloaderOptions("preloader_button");
    } else {
        $loader_button_text = 'Show content';
    }
    
    // prepare progress image
    if(GetPreloaderOptions("preloader_progress_bar_image")){
        $loader_progress_bare_image= GetPreloaderOptions("preloader_progress_bar_image");
    } else {
        $loader_progress_bare_image = '7.GIF';
    }
    
    // prepare timer value
    if(GetPreloaderOptions("preloader_timer")){
        $loader_timeout= GetPreloaderOptions("preloader_timer");
    } else {
        $loader_timeout = 10;
    }
    
    // show content automatically
    if(GetPreloaderOptions("preloader_show_automatically")){
        $loader_auto_show= GetPreloaderOptions("preloader_show_automatically");
    } else {
        $loader_auto_show = "no";
    }

    $ready_html = '
    <div class="AdvertPreloaderMain">
        <div class="AdvertPreloaderTitle"><h2>'.$loader_title.'</h2></div>
        <div class="AdvertPreloaderTitleSuccess" style="display: none;"><h2>'.$loader_title_completed.'</h2></div>
        <div class="AdvertPreloaderLoaderImage">
            <img src="'.$plugin_dir.'/loaders/'.$loader_progress_bare_image.'">
        </div>
        <div class="AdvertPreloaderAds">
        '.$loader_advert_code.'
        </div>
        <div class="AdvertPreloaderShowButton">
            <button class="AdvertPreloaderShowButtonStyle" style="display: none;">'.$loader_button_text.'</button>
        </div>
        <input type="hidden" name="AdvertPreloaderButtonDelay" id="AdvertPreloaderButtonDelay" value="'.$loader_timeout.'">
        <input type="hidden" name="AdvertPreloaderAutoShow" id="AdvertPreloaderAutoShow" value="'.$loader_auto_show.'">
    </div>';
    
    return $ready_html;
}

// after activation hook,
register_activation_hook(__FILE__, 'preloader_advert_activation');
function preloader_advert_activation(){
    // update options only after first activation
    if(!get_option('advert_preloader_all_options')){
        $all_options = array(
            "preloader_timer"=>10, 
            "preloader_show_automatically"=>"no",
            "preloader_show_on_page_type" => "only_full_content",
            "preloader_title"=>"Loading content, please wait!",
            "preloader_title_completed"=>"Loading Completed!",
            "preloader_button"=>"SHOW CONTENT",
            "preloader_progress_bar_image"=>"8.GIF",
            "preloader_show_on"=>"selected_posts",
        );


        update_option('advert_preloader_all_options', serialize($all_options));
    }
}