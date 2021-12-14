<?php
/**
 * Template part for displaying footer info
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 *
 */

//LOAD THIS FROM BOOK META


$bookSSTheme = wp_get_theme();
$themeName = esc_html( $bookSSTheme->get( 'Name' ));
$themeURI = esc_html( $bookSSTheme->get( 'ThemeURI' ));
$authorURI =  esc_html( $bookSSTheme->get( 'AuthorURI' ));

echo '  |  <a href="'.$themeURI.'" target="_blank">'.$themeName.' Theme</a>, 2021. </p>';
if (is_page()){

    $bookRoot = getRootForPage($post);
    $root = get_post($bookRoot);
    $CCLicense = get_post_meta( $root->ID, 'bookLicense', true );
    //consolePrint('License for '.$root->post_title.' is '.$CCLicense);
    echo '<div class="license-info">';
    if (($CCLicense == 'allrightsreserved') || ($CCLicense == null)){?>
       <p>All original content in this book is All Rights Reserved &copy;<?php the_modified_time('Y'); ?></p></div>
    <?php
    }
    else{
        $CCimage = '/inc/images/'.$CCLicense.'.png';
        $CCDescription = '<a href="https://creativecommons.org/licenses/'.$CCLicense.'/4.0/">CC '.strtoupper ($CCLicense).' 4.0 License</a>';
        ?>
        <p><img src="<?php echo get_template_directory_uri().$CCimage;?>"></p><p>All original content in this book is licenced under the <?php echo $CCDescription ?> unless otherwise noted. </p></div>
        <?php
    }
   $footerText = get_post_meta( $root->ID, 'footerText', true );
        echo '<p>'.$footerText.'</p>';

}?>