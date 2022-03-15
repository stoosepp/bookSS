<?php

  function addPageMetaBox(){
    add_meta_box(//Must be in the below order
  'license_select_box',//ID for the Box
  'Book Attributes',//Title:what will show in the top of the box
  'licenseSelectMetaBoxCreator',//Callback: Method called that contains what's inside the box
  'page',//Screen - post types that this appears on
  'side',//where it appears
  'high',//priority of where the box appears (high or low)
  null//Callback args: provides arguments to callback function
);
}
add_action('add_meta_boxes','addPageMetaBox');

function licenseSelectMetaBoxCreator($post){
?>
  <style type="text/css">
  #pageMetaBox{
    display:flex;
    flex-direction: column;
    align-items: left;
  }

  #pageMetaBox > div{
    padding:5px;
  }

  #pageMetaBox > div > p{
    margin-top:5px;
    margin-bottom:0px;
  }
  #pageMetaBox > div > textarea{
    width:100%;
    height: 100px;
  }
  </style>
  <?php
    wp_nonce_field( 'licenseSelectMetaBox', 'licenseSelectMetaBox-nonce' );
    $allBooks = getTopLevelPages();
    if (in_array($post, $allBooks)) {
        $value = get_post_meta( $post->ID, 'bookLicense', true );
        if ($value == null){
            $value = 'allrightsreserved';
            //consolePrint('License for this book: '.$value);
        }

        //Add metabox for CC Licenses
        $licenseArray = array(
            'allrightsreserved',
            'by',
            'by-sa',
            'by-nc',
            'by-nc-sa',
            'by-nd',
            'by-nc-nd',
            'cc-zero',
        );?>
        <div id="pageMetaBox">
          <div>
            <p>Select a License: </p>
            <!-- <label for="licenseSelector">Select a License for this Book: :</label> -->
            <?php

            echo '<select id="licenseSelector" name="licenseSelector">';
            foreach($licenseArray as $CCLicense){
           // echo '<tr><td>';
            //consolePrint('Checking '.$CCLicense.' and '.$value);

            $isChecked = '';
            if(strcmp($CCLicense, $value) == 0)
            {
                $isChecked = 'selected';
            }
            if ($CCLicense == 'allrightsreserved'){
                echo '<option value="'.$CCLicense.'" '.$isChecked.'>All Rights Reserved</option>';
                //echo '<input type="radio" name="licenseSelector" value="'.$CCLicense.'" '.$isChecked.'>All Rights Reserved</input>';
            }
            else{
                $CCimage = get_template_directory_uri().'/inc/images/'.$CCLicense.'.png';
                $CCDescription = '<a target="_blank" href="https://creativecommons.org/licenses/'.$CCLicense.'/4.0/">CC '.strtoupper ($CCLicense).'</a>';
                $CCImageTag = '<img style="width:30%; height:auto;" src="'.$CCimage.'" />';
                //echo '<input style="position: relative; bottom: 0.5em;" type="radio" name="licenseSelector" value="'.$CCLicense.'" '.$isChecked.'>'.$CCImageTag.' '.$CCDescription;
                echo '<option style="background-image:url('.$CCimage.');" value="'.$CCLicense.'" '.$isChecked.'>'.$CCDescription.'</option>';
            }


        }?>
        </select>
      </div>
        <!--- ALLOW FEEDBACK --->

        <div>
          <?php $feedbackOn = get_post_meta( $post->ID, 'acceptFeedback', true );
            if($feedbackOn == true)
            {
              consolePrint('Feedback Checked');
                $isChecked = 'checked';
            }
            else{
              consolePrint('Feedback NOT Checked');
            }

          echo '<input type="checkbox" id="acceptFeedback" name="acceptFeedback" '.$isChecked.'> Allow Voting';

          ?>
     </div>
        <!-- FOOTER TEXT -->
      <div>
            <p>Custom Footer HTML</p>

          <?php $footerText = get_post_meta( $post->ID, 'footerText', true );
            if($footerText){
              echo '<textarea id="footerText" name="footerText" value="">'.$footerText.'</textarea>';
            }
            else{
              echo '<textarea rows="10"  id="footerText" name="footerText" >Embedded videos, credited images / media are not inclusive of this license, so please check with the original creators if you wish to use them.</textarea>';
            }
          ?>
          <p style="font-size:0.8em;">Default Text: Embedded videos, credited images / media are not inclusive of this license, so please check with the original creators if you wish to use them.</p>
          </div>
        </div>

     <?php
    }
    else{
        echo('This is a subpage of a Book. To select a license go to a top-level page that serves as a book.');
        ?>
    <?php
    }
    // Don't forget about this, otherwise you will mess up with other data on the page
    wp_reset_postdata();
  }

  function saveMeta( $post_id ) {
    // Check if our nonce is set.
    if ( !isset( $_POST['licenseSelectMetaBox-nonce'] ) ) {
            return;
    }
    // Verify that the nonce is valid.
    if ( !wp_verify_nonce( $_POST['licenseSelectMetaBox-nonce'], 'licenseSelectMetaBox' ) ) {
            return;
    }
    // If this is an autosave, our form has not been submitted, so we don't want to do anything.
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
            return;
    }
    // Check the user's permissions.
    if ( !current_user_can( 'edit_post', $post_id ) ) {
            return;
    }
    // Sanitize user input.
    $newLicense = ( isset( $_POST['licenseSelector'] ) ? sanitize_html_class( $_POST['licenseSelector'] ) : '' );
    update_post_meta( $post_id, 'bookLicense', $newLicense );

   // Checks for input and saves
   if( isset( $_POST[ 'acceptFeedback' ]))  {
      update_post_meta( $post_id, 'acceptFeedback', $_POST['acceptFeedback']);
  } else{
    delete_post_meta( $post_id, 'acceptFeedback');
  }


  // Checks for input and saves if needed
  if( isset( $_POST[ 'footerText' ] ) ) {
      update_post_meta( $post_id, 'footerText', $_POST[ 'footerText' ] );
  }

}
add_action( 'save_post', 'saveMeta' );


/* --------------- ADD CUSTOM COLUMNS TO PARTS --------------- */
add_filter( 'manage_page_posts_columns', 'addColumnsToParts' );
function addColumnsToParts($columns) {
    //unset( $columns['author'] );//Gets rid of this Column! YIKES!
    unset( $columns['comments'] );//Gets rid of this Column! YIKES!
    $new = array();
  foreach($columns as $key => $title) {
    if ($key=='date') {// Put the Thumbnail column before the Author column
      $new['order'] = 'Order';
      $new['votes'] = 'Votes';
      $new['license'] = 'License';
    }
    $new[$key] = $title;
  }
  return $new;
}

// Add the data to the custom columns for book type:
  add_action( 'manage_page_posts_custom_column' , 'custom_page_column', 10, 2 );
  function custom_page_column( $column, $post_id ) {
      $allBooks = getTopLevelPages();
      $thePage = get_post($post_id);
          switch ( $column ) {
              case 'order' :
                $thisOrder = get_post($post_id);
                echo $thisOrder->menu_order;
                break;
              case 'votes' :
                $thisPost = get_post($post_id);
                $bookRoot = getRootForPage($thisPost);
	              $root = get_post($bookRoot);
                $feedbackOn = get_post_meta( $root->ID, 'acceptFeedback', true );
                if($feedbackOn == true){
                  $voteData = getVoteData($post_id);
                  if ($voteData){
                    $fontAwesome = get_template_directory_uri().'/css/all.css';

      ?>
                  <link rel="stylesheet" href="<?php echo $fontAwesome; ?>">
                  <?php
                  //consolePrint('Up: '.$voteData[0].' Down: '.$voteData[1]);
                  $totalCount = $voteData[0] + $voteData[1];
                  $percentage = $voteData[0]/$totalCount;
                  $percentageValue =  round($percentage,2)*100;
                   echo '<p style="text-align:center;  margin-bottom:2px;">'.$voteData[0].' <i class="far fa-thumbs-up"></i> - '.$voteData[1].' <i class="far fa-thumbs-down"></i> - ('.$totalCount.')';

                    echo '<div id="vote-chart" style="padding-left:10px; width:100%; height:10px; border-radius:20px;background: rgb(220,112,108);
                    background: linear-gradient(90deg, rgba(103,216,173,1)'.$percentageValue.'%,rgba(220,112,108,1)'.$percentageValue.'%);">';
                   ?>
                        </div>
                          <?php
                  }
                  else{
                    echo 'No vote data yet.';
                  }
                }
                else{
                  echo 'Voting not enabled. Edit main book page to enable.';
                }

                break;
              case 'license' :
                  if (in_array($thePage, $allBooks)) {
                      $CCLicense = get_post_meta( $post_id, 'bookLicense', true );
                      if ($CCLicense == 'allrightsreserved'){
                          echo 'All Rights Reserved &copy; ';
                          echo the_modified_time('Y').'</p>';
                      }
                      else if (!$CCLicense){
                        echo 'No License Chosen';
                      }
                      else{
                          $CCLink = 'https://creativecommons.org/licenses/'.$CCLicense.'/4.0/';
                          $CCimage = '/inc/images/'.$CCLicense.'.png';
                          echo '<a target="_blank" href="'.$CCLink.'"><img style="height:30px; width:auto; padding-top:5px;" src="'.get_template_directory_uri().$CCimage.'"/></a>';
                      }

                  }
                  break;


          }
  }

/* --------------- ADD FILTER TO PAGES --------------- */

add_action( 'restrict_manage_posts', 'filterPageList' );

function filterPageList($post_type){
    $type = 'page';
    if (isset($_GET['post_type'])) {
        $type = $_GET['post_type'];
    }

    //only add filter to post type you want
    if ('page' == $post_type){
        //get all the books
        $allBooks = get_posts([
          'post_type' => 'books',
          'post_status' => array('publish', 'pending', 'draft', 'auto-draft', 'future'),
          'numberposts' => -1,
          'order'    => 'ASC'
        ]);

        $allBooks = getTopLevelPages()
        ?>
        <select name="bookSelector">
        <option value=""><?php _e('All Books', 'wose45436'); ?></option>
        <?php
            $currentBook = isset($_GET['bookSelector'])? $_GET['bookSelector']:'';
            foreach ($allBooks as $thisBook) {
              $bookTitle = get_the_title($thisBook);
              $thisBookID = $thisBook->ID;
                printf
                    (
                        '<option value="%s"%s>%s</option>',
                        $thisBookID,
                        $thisBookID == $currentBook? ' selected="selected"':'',
                        $bookTitle
                    );
                }
        ?>
        </select>
        <?php
    }
}

function find_descendants($post_id) {
  $descendant_ids = array();
  array_push($descendant_ids, $post_id);//Add the main book to show that
  $args = array(
        'post_status' => array('draft', 'publish','future'),
        'child_of' => $post_id,
        'posts_per_page' => -1,
      );
  $pages = get_pages($args);

  foreach ($pages as $page) {
    consolePrint('Adding to array: '.$page->post_title);
    array_push($descendant_ids, $page->ID); }
  return $descendant_ids;
}

function SearchFilter($query) {
  global $pagenow;
  $type = 'page';
  if (isset($_GET['post_type'])) {
      $type = $_GET['post_type'];
  }
  if ( 'page' == $type && is_admin()
      && $pagenow=='edit.php'
      && isset($_GET['bookSelector'])
      && $_GET['bookSelector'] != ''
      && $query->is_main_query()
      ) {
  if ($query->is_search) {
    $selectedBook = $_GET['bookSelector'];
    //consolePrint('The book:'.$selectedBook);
      $query->set ( 'post__in', find_descendants($selectedBook) );
  }
  }
  return $query;
}
add_filter('pre_get_posts','SearchFilter');
//Adds text above title
add_action( 'load-edit.php', function(){
    $screen = get_current_screen();
     // Only edit post screen:
    if( 'edit-page' === $screen->id )
    {
         // Before:
         add_action( 'all_admin_notices', function(){
             echo '<p>Recommended Plugins to make life easier: <a href="https://wordpress.org/plugins/simple-page-ordering/" target="_blank">Simple Page Ordering</a>  |  <a href="https://wordpress.org/plugins/broken-link-checker/" target="_blank">Broken Link Checker</a></p>';
         });

         // After:
        //  add_action( 'in_admin_footer', function(){
        //      echo '<p>Goodbye from <strong>in_admin_footer</strong>!</p>';
        //  });
     }
 });
?>