<?php

/**
  Page for one page Layouts
 * */


$pages = get_pages( array('sort_order' => 'ASC', 'sort_column' => 'menu_order') );


global $post;

if ($pages) {
    echo '<div class="container">';

    foreach ($pages as $page_data) {

        $page = lis_get_page_options($page_data->ID);
        //print_r($page);
        $pageTemplate = $page['template'];
        $showPage = get_post_meta($page_data->ID, '_lis_show_page', true);
        $showTitle = get_post_meta($page_data->ID, '_lis_show_title', true);
        $pageBg = get_post_meta($page_data->ID, '_lis_page_bg', true);
        $isFullBg = get_post_meta($page_data->ID, '_lis_isfull_bg', true);
        
        $imgClass = ($isFullBg == 'yes') ? ' full' : '';
        
        if (isset($showPage) && $showPage != "") {
            echo "<div id='".$page['post_name'] ."' class='page-item " . $page['class'] .$imgClass. "' style='background:url(".$pageBg.")'>";
           // echo "<img src='".$pageBg."' border='0'  class='".$imgClass."' >";
            if($pageTemplate && $pageTemplate != "" ){
                if (get_edit_post_link( $page_data->ID )){
                    echo '<a href="' . get_edit_post_link( $page_data->ID ) . '" class="quickEdit" target="_blank"> Edit</a>';
                }
                echo "<div class='page-content' >";
                    if( isset($showTitle) && $showTitle != "" ){
                        echo '<h1 class="page-title title_'.$page['post_name'].'"><span>' . $page['page_title'] . '</span></h1>';
                    }
                    echo '<div class="'.$page['post_name'].'">'. $page['page_content']. '</div>';
                    include_once($pageTemplate);
                echo "</div>";
                
            }else{  
                if (get_edit_post_link( $page_data->ID )){
                    echo '<a href="' . get_edit_post_link( $page_data->ID ) . '" class="quickEdit" target="_blank"> Edit</a>';
                }
                echo "<div class='page-content' >";
                if( isset($showTitle) && $showTitle != "" ){
                    echo '<h1 class="page-title title_'.$page['post_name'].'"><span>' . $page['page_title'] . '</span></h1>';
                }
                echo '<div class="'.$page['post_name'].'">'. $page['page_content']. '</div>';
                
                include("template-default.php");
                echo "</div>";
               
            }
            echo "</div>";
        }
        
    }

    echo '</div>';
}


