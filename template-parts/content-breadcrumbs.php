<?php

/**
 * Template part for displaying breadcrumbs
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 *
 */
?>
<?php get_template_part( 'template-parts/content-feedbackform', 'none' ); ?>
<div class="header-bar">
    <div class="article-header">
        <div id="header-left">
            <!--<a href="#" class="TOCToggle" onclick="toggleHidden(this)"><i class="fas fa-arrow-left"></i></a>-->
            <a class="TOCToggle" onmouseover="" style="cursor: pointer;" onclick="toggleHidden(this)"><i class="fas fa-bars"></i></a>
                <?php
                if (is_search()){
                    echo 'Search Results for:&nbsp;<strong> '.get_search_query().'</strong>';
                }
                else{
                    $bookRoot = getRootForPage($post);
                    $root = get_post($bookRoot);
                    if ( is_page() && ($post != $root)) {
                        //Not root
                        $postParentID = wp_get_post_parent_id($post);
                        $postParent = get_post($postParentID);
                        if ($postParent->post_title != $root->post_title){
                            $subChapterTitle = $postParent->post_title;
                            // $truncatedTitle = wp_trim_words( $subChapterTitle, 5, '...');
                            // echo '<a href='.get_permalink($postParent).'>'.$truncatedTitle.'</a>';
                            echo '<a href='.get_permalink($postParent).'>'.$subChapterTitle.'</a>';
                            echo '<i class="fas fa-chevron-right"></i>';
                        }
                        $chapterTitle = $post->post_title;
                        // $truncatedTitle = wp_trim_words( $chapterTitle, 5, '...');
                        // echo '<a href='.get_permalink($post).'>'.$truncatedTitle.'</a>';
                        echo '<a href='.get_permalink($post).'>'.$chapterTitle.'</a>';
                    }
                }
            ?>
        </div>
        <div id="header-right">
        <?php if (!is_search()){
            // OPTIONS BUTTONS
            ?>
            <div id="header-options">
           <?php
           //COMMENTS
           if ((comments_open() == true) && ($post != $root)){
                $feedbackOn = get_post_meta( $root->ID, 'acceptFeedback', true );
                    if($feedbackOn == true)
                    {
                    echo '<a class="far fa-comment-alt tooltip" onclick="toggleHidden(this);" style="cursor: pointer;" ><span class="tooltiptext">Comment</span></a>';
                    }
            }
            //AMAZON POLLY PODCAST
            if ( in_array( 'amazon-polly/amazonpolly.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {
            $subURL = get_home_url().'/feed/amazon-pollycast/';
            $file = $subURL;
            $file_headers = @get_headers($file);
            if(!$file_headers || $file_headers[0] == 'HTTP/1.1 404 Not Found') {
                $exists = false;
            }
            else {
                $exists = true;
                echo '<a style="cursor: pointer;" class="far fa-podcast tooltip" onclick="copyPodcastURL(`'.$subURL.'`);"><span class="tooltiptext">Subscribe</span></a>';
            }
            }
            //FULL SCREEN
            echo '<a class="fas fa-compress hidden tooltip" onclick="window.toggleFullscreen(this);" style="cursor: pointer;"><span class="tooltiptext">Window</span></a>';
            echo '<a class="fas fa-expand tooltip" onclick="window.toggleFullscreen(this);" style="cursor:pointer;"><span class="tooltiptext">Full Screen</span></a>';
            //PRINT
            echo '<!--<a class="fas fa-print tooltip" href="javascript:window.print()" style="cursor: pointer;><span class="tooltiptext">Print</span></a>-->';
            ?>
            </div>

        <?php
        //GET ALL LINKS IN LEFT HAND MENU
        $bookRoot = getRootForPage($post);//Get the book for the current page
        $isRoot = false;
        $childPages = getKids($bookRoot);
        $allPages = array();
        if (( $childPages) && $isRoot == false){
            foreach ( $childPages as $child ) {
               array_push($allPages,$child);
                $grandChildren = getKids($child->ID);
                foreach ($grandChildren as $grandChild){
                    $postParentID = wp_get_post_parent_id($post);
                    $postParent = get_post($postParentID);
                    if ( ($child->ID == $postParentID) || ($child->ID == $post->ID)){
                        array_push($allPages,$grandChild);
                    }
                    //Check to see if you're at Root of this book
                    $pages = get_pages('child_of='.$post->ID.'&sort_column=post_date&sort_order=asc&parent='.$post->ID);
                    if ( $pages ) {
                        $first_page = current( $pages );
                        if ($first_page == $child){
                            array_push($allPages,$grandChild);
                        }
                    }
                }
            }
        }
        $allPagesCount = count($allPages)-1;
        foreach ($allPages as $key=>$pageLink){
            if ($pageLink == $post){
                if ($key > 0){
                    $prev_link = get_permalink($allPages[$key-1]);
                    echo '<a class="next-prev" href="'.$prev_link.'"><i class="fas fa-chevron-left"></i></a>';
                }
                else{
                    echo '<i class="fas fa-chevron-left disabled-arrow next-prev"></i>';
                }
                if ($key < $allPagesCount){
                    $next_link = get_permalink($allPages[$key+1]);
                    echo '<a class="next-prev" href="'.$next_link.'"><i class="fas fa-chevron-right"></i></a>';
                }
                else{
                    echo '<i class="fas fa-chevron-right disabled-arrow next-prev"></i>';
                }
            }

        }
    }
        ?>
        </div>
    </div>
    <div class="progress-container">
        <div class="progress" id="progress"></div>
    </div>
</div>

