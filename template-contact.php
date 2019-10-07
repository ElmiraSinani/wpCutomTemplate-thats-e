<?php 
/*
Template Name: Contact Page
*/

$args = array( 'category_name' => 'our_contacts','posts_per_page'=>'1' );
// The Query
$the_query = new WP_Query( $args );



//$content = '[contact-form-7 id="3794" title="Contact form 1"]';
$content = '[contact-form-7 id="650" title="Contact Form ThatsE"]';
?>

<div class="contact_page">
<?php
if ( $the_query->have_posts() ) {       
	
	while ( $the_query->have_posts() ) {
                $the_query->the_post();
                $postID = $the_query->post->ID;
        ?>
        <div class="item_contact">
            <div class="contact_item">
                <img src="<?php bloginfo('template_directory');?>/images/location-icon.png" />
            </div>
            <div class='about_title'>
                <?php the_content(); ?>
            </div>
        </div>
        <div class="item_form">
            <div class="contact_item">
                <img src="<?php bloginfo('template_directory');?>/images/mail-icon.png" />
            </div>
            <?php echo do_shortcode( $content ) ?>
        </div>

<?php }
	
} else {
	// no posts found
}

/* Restore original Post Data */
wp_reset_postdata();
?>
</div>