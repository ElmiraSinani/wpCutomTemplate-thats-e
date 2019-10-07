<?php /* Template Name: Portfolio  */ ?>


<!--container start-->
<div class="container">
    <!--portfolio start-->
    <?php $terms = get_terms('filter'); ?>
    <div class="gallery-container">
        <ul id="filters" class="list-unstyled">
            <li><a class="active" href="#" data-filter="*"> All</a></li>
            <?php foreach( $terms as $k=>$v ){ ?>
                <li><a href="#" data-filter=".<?php echo $v->slug; ?>"><?php echo $v->name; ?></a></li>               
            <?php } ?>
        </ul>

        <div id="gallery" class="col-4">
            <?php 
                query_posts( array ( 'post_type' => 'portfolio', 'order' => 'ASC', 'posts_per_page' => 3 ) );
                if ( have_posts() ) : while ( have_posts() ) : the_post();
                $image = wp_get_attachment_url( get_post_thumbnail_id(get_the_ID()) );
                $terms = wp_get_post_terms( get_the_ID(), 'filter' ); 
                $filter = "";
                foreach ($terms as $key => $value) {
                    $filter .= $value->slug." ";
                }
            ?>
            <div class="portfolio_item">
                <div class="element item view view-tenth <?php echo $filter; ?>" data-zlname="reverse-effect">
                    <img src="<?php echo $image; ?>" alt="">
                    <div class="mask">
                        <a data-zl-popup="link" href="javascript:;">
                            <i class="fa fa-link"></i>
                        </a>
                        <a data-zl-popup="link2" class="fancybox" rel="group" href="<?php echo $image; ?>">
                            <i class="fa fa-search"></i>
                        </a>
                    </div>    
                    <div class="portfolio_title"><?php the_title(); ?></div>
                    <div class="portfolio_txt"><?php the_content(); ?></div>
                </div>
            </div>
            
            <?php endwhile; else: ?>
            <p><?php _e('Sorry, no posts matched your criteria.'); ?></p>
            <?php endif; ?>
            <?php wp_reset_query(); ?>

        </div>
    </div>
    <!--portfolio end-->
</div>
<!--container end-->
