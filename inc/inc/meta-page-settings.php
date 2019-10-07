<?php

/**
 * Adds a box to the main column on the Page edit screens for Page settings.
 */
function lis_add_meta_box() {

	$screens = array( 'page' );
	foreach ( $screens as $screen ) {
            add_meta_box(
                    'lis_sectionid',
                    __( 'Page Options', 'lis_textdomain' ),
                    'lis_meta_box_callback',
                    $screen
            );
	}
}
add_action( 'add_meta_boxes', 'lis_add_meta_box' );

/**
 * Prints the box content.
 * 
 * @param WP_Post $post The object for the current post/page.
 */
function lis_meta_box_callback( $post ) {

	// Add an nonce field so we can check for it later.
	wp_nonce_field( 'lis_meta_box', 'lis_meta_box_nonce' );

	/*
	 * Use get_post_meta() to retrieve an existing value
	 * from the database and use the value for the form.
	 */
	
        $showPage = get_post_meta( $post->ID, '_lis_meta_show_page', true );
        $showTitle = get_post_meta( $post->ID, '_lis_meta_show_title', true );
	
       ?>
       <div class="meta_item">
        <label for="lis_show_page">
            <input type="checkbox" name="lis-show-page" id="lis_show_page" value="yes" <?php if ( isset ( $showPage ) ) checked($showPage, 'yes' ); ?> />
            <strong>   <?php _e( 'Show page on site', 'lis_textdomain' )?></strong>
        </label>
       </div>
       <div class="meta_item">
        <label for="lis_show_title">
            <input type="checkbox" name="lis-show-title" id="lis_show_title" value="yes" <?php if ( isset ( $showTitle ) ) checked( $showTitle, 'yes' ); ?> />
            <strong><?php _e( 'Show page title', 'lis_textdomain' )?></strong>
        </label>
       </div>
        <?php
}

/**
 * When the post is saved, saves our custom data.
 *
 * @param int $post_id The ID of the post being saved.
 */
function lis_save_meta_box_data( $post_id ) {

	/*
	 * We need to verify this came from our screen and with proper authorization,
	 * because the save_post action can be triggered at other times.
	 */
	// Check if our nonce is set.
	if ( ! isset( $_POST['lis_meta_box_nonce'] ) ) {
            return;
	}

	// Verify that the nonce is valid.
	if ( ! wp_verify_nonce( $_POST['lis_meta_box_nonce'], 'lis_meta_box' ) ) {
            return;
	}

	// If this is an autosave, our form has not been submitted, so we don't want to do anything.
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
            return;
	}

	// Check the user's permissions.
	if ( isset( $_POST['post_type'] ) && 'page' == $_POST['post_type'] ) {
            if ( ! current_user_can( 'edit_page', $post_id ) ) {
                    return;
            }
	} else {
            if ( ! current_user_can( 'edit_post', $post_id ) ) {
                    return;
            }
	}

	/* OK, it's safe for us to save the data now. */	

	// Checks for input and saves
        if( isset( $_POST[ 'lis-show-page' ] ) ) {
            update_post_meta( $post_id, '_lis_meta_show_page', 'yes' );
        } else {
            update_post_meta( $post_id, '_lis_meta_show_page', '' );
        }

        // Checks for input and saves
        if( isset( $_POST[ 'lis-show-title' ] ) ) {
            update_post_meta( $post_id, '_lis_meta_show_title', 'yes' );
        } else {
            update_post_meta( $post_id, '_lis_meta_show_title', '' );
        }
}
add_action( 'save_post', 'lis_save_meta_box_data' );