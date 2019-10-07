<?php

/**
 * Adds a box to the main column on Page edit screens for Page settings.
 */
function lis_add_meta_box() {

    $screens = array('page');

    foreach ($screens as $screen) {
        add_meta_box(
                'lis_sectionid', __('Page Settings', 'lis_textdomain'), 
                'lis_meta_box_callback', 
                $screen
        );
    }
}

add_action('add_meta_boxes', 'lis_add_meta_box');

/**
 * Prints the box content.
 * 
 * @param WP_Post $post The object for the current post/page.
 */
function lis_meta_box_callback($post) {

    // Add an nonce field so we can check for it later.
    wp_nonce_field('lis_meta_box', 'lis_meta_box_nonce');

    /*
     * Use get_post_meta() to retrieve an existing value
     * from the database and use the value for the form.
     */
    $showPage = get_post_meta($post->ID, '_lis_show_page', true);
    $showTiele = get_post_meta($post->ID, '_lis_show_title', true);
    $pageBg = get_post_meta($post->ID, '_lis_page_bg', true); 
    $isfullBg = get_post_meta($post->ID, '_lis_isfull_bg', true); 
    
    $images = array('jpg', 'png', 'gif');   
    $filetype = wp_check_filetype($pageBg);
    $ext = $filetype['ext'];
    
    ?>
    <script>
        jQuery(document).ready(function($) {
            var custom_uploader;
            $('#lis_bg_button').click(function(e) {
                e.preventDefault();
                //If the uploader object has already been created, reopen the dialog
                if (custom_uploader) {
                    custom_uploader.open();
                    return;
                }
                //Extend the wp.media object
                custom_uploader = wp.media.frames.file_frame = wp.media({
                    title: 'Choose File',
                    button: {
                        text: 'Choose File'
                    },
                    multiple: false
                });
                //When a file is selected, grab the URL and set it as the text field's value
                custom_uploader.on('select', function() {
                    attachment = custom_uploader.state().get('selection').first().toJSON();
                    $('#page_bg_filed').val(attachment.url);
                });
                //Open the uploader dialog
                custom_uploader.open();
            });
        });
    </script>

    <div class="meta_item">
        <label for="lis-show-page">
            <input type="checkbox" name="lis-show-page" id="lis-show-page" value="yes" <?php if ( isset ( $showPage ) ) checked( $showPage, 'yes' ); ?> />
            <?php _e( 'Show page on site', 'lis_textdomain' )?>
        </label>
    </div>
    <div class="meta_item">
        <label for="lis-show-title">
            <input type="checkbox" name="lis-show-title" id="lis-show-title" value="yes" <?php if ( isset ( $showTiele ) ) checked( $showTiele, 'yes' ); ?> />
            <?php _e( 'Show page title', 'lis_textdomain' )?>
        </label>
    </div>
    <div class="meta_item">
        <label for="lis-isfull-bg">
            <input type="checkbox" name="lis-isfull-bg" id="lis-isfull-bg" value="yes" <?php if ( isset ( $isfullBg ) ) checked( $isfullBg, 'yes' ); ?> />
            <?php _e( 'Is Full Bg', 'lis_textdomain' )?>
        </label>
    </div>
    <div class="meta_item">
        <label for="page_bg_filed"> Background Image </label>
        <input type="button" value="Upload Background" name="lis_page_bg" id="lis_bg_button" />
        <input type="hidden" id="page_bg_filed" name="page_bg_filed" value="<?php echo esc_attr($pageBg); ?>"  />
        <?php if (in_array($ext, $images)) {
            $imageContent = (!isset($pageBg) || $pageBg == '') ? "" : '<img class="padd_top" src=' . $pageBg . ' width="60"></div>';
            echo $imageContent;
        } ?>
    </div>
    

    <?php
}

/**
 * When the post is saved, saves our custom data.
 *
 * @param int $post_id The ID of the post being saved.
 */
function lis_save_meta_box_data($post_id) {

    /*
     * We need to verify this came from our screen and with proper authorization,
     * because the save_post action can be triggered at other times.
     */

    // Check if our nonce is set.
    if (!isset($_POST['lis_meta_box_nonce'])) {
        return;
    }

    // Verify that the nonce is valid.
    if (!wp_verify_nonce($_POST['lis_meta_box_nonce'], 'lis_meta_box')) {
        return;
    }

    // If this is an autosave, our form has not been submitted, so we don't want to do anything.
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }

    // Check the user's permissions.
    if (isset($_POST['post_type']) && 'page' == $_POST['post_type']) {

        if (!current_user_can('edit_page', $post_id)) {
            return;
        }
    } else {

        if (!current_user_can('edit_post', $post_id)) {
            return;
        }
    }

    /* OK, it's safe for us to save the data now. */

    // Checks for input and saves
    if( isset( $_POST[ 'lis-show-page' ] ) ) {
        update_post_meta( $post_id, '_lis_show_page', 'yes' );
    } else {
        update_post_meta( $post_id, '_lis_show_page', '' );
    }

    // Checks for input and saves
    if( isset( $_POST[ 'lis-show-title' ] ) ) {
        update_post_meta( $post_id, '_lis_show_title', 'yes' );
    } else {
        update_post_meta( $post_id, '_lis_show_title', '' );
    }
    if( isset( $_POST[ 'lis-isfull-bg' ] ) ) {
        update_post_meta( $post_id, '_lis_isfull_bg', 'yes' );
    } else {
        update_post_meta( $post_id, '_lis_isfull_bg', '' );
    }
    
     $bg_field = $_POST['page_bg_filed'];
     add_post_meta($post_id, '_lis_page_bg', $bg_field, true) or update_post_meta($post_id, '_lis_page_bg', $bg_field);
}

add_action('save_post', 'lis_save_meta_box_data');

function update_edit_form() {
    echo ' enctype="multipart/form-data"';
}

