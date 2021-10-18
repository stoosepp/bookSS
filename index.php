<?php
/**
 * The main template file
 *
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 * E.g., it puts together the home page when no home.php file exists.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package _s
 */

get_header();?>
<div id="masthead" class="site-header">
		<div class="site-branding">
			<?php
			the_custom_logo();
			if ( is_front_page() && is_home() ) :
				?>
				<h1 class="site-title"><!--<a href="<?php /*echo esc_url( home_url( '/' ) );*/ ?>" rel="home">--><?php bloginfo( 'name' ); ?></a></title>
				<?php
			else :
				?>
				<p class="site-title"><!--<a href="<?php /*echo esc_url( home_url( '/' ) );*/ ?>" rel="home">--><?php bloginfo( 'name' ); ?></a></p>
				<?php
			endif;
			/*$_s_description = get_bloginfo( 'description', 'display' );*/
			if ( $_s_description || is_customize_preview() ) :
				?>
				<p class="site-description"><?php echo $_s_description; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></p>
			<?php endif; ?>
		</div><!-- .site-branding -->
	</div><!-- #masthead -->
	<?php
$topLevelPages = getTopLevelPages();

if ($topLevelPages){?>
	<form class="search-form home-search" action="/" method="get">
			<!-- <label for="search">Search in <?php echo home_url( '/' ); ?></label> -->
			<i class="far fa-search"></i>
			<input type="text" name="s" id="search" placeholder="Search" value="<?php the_search_query(); ?>" />
			</form><?php
	echo '<p class="content-type-header">BOOKS</p>';
	echo '<ul class="book-list">';

	foreach($topLevelPages as $key=>$topLevelPage){

		echo '<a href="'.get_permalink($topLevelPage).'">';
		echo '<li><div class="book-cover"><div class="book-gradient"></div>';
		echo '<div class="book-title"><h2>'.$topLevelPage->post_title.'</h2>';

		$post_author_id = get_post_field( 'post_author', $topLevelPage);
		echo '<div class="book-authorexcerpt"><h3>'.get_the_author_meta('display_name', $post_author_id).'</h3>';
		echo '<h4>'.$topLevelPage->post_excerpt.'</h4></div>';
		$featured_img_url = get_the_post_thumbnail_url($topLevelPage);
		if ($featured_img_url){
			echo '<img id="cover-image"src="'.esc_url($featured_img_url).'" rel="lightbox">';
		}
		else{
				$hueRotate = $key/count($topLevelPages);
				echo '<img id="cover-image" style="filter:hue-rotate('.$hueRotate.'turn);" src="'.get_template_directory_uri().'/images/book-cover.jpg" rel="lightbox">';
		}
		echo '</div></li>';
		echo '</a>';
	}
	echo '</ul>';
}

//Get Categories


    $args = array(
        'post_type' => 'menu-item'
    );

    $categories = get_categories( $args );
	$categories = removeHidden($categories);

	if ($categories){
		echo '<p class="content-type-header">DOCUMENTS</p>';
		echo '<ul class="cat-list">';
    foreach ( $categories as $category ) {
		$parentCatName = get_cat_name($category->parent);
			echo '<li>';
			echo '<h2>' . $category->name . '</h2>';

			$post_list = get_posts( array(
				'orderby'    => 'date',
				'sort_order' => 'desc',
				'category' => $category->term_id
			) );
			?>

			<ul class="doc-list">
				<?php foreach($post_list as $thisPost) { ?>
					<a href="<?php echo get_permalink($thisPost); ?>"><li>
						<?php echo $thisPost->post_title; ?>
					</li></a>
				<?php  } ?>
			</ul>
			</li>
			<?php
 		}
echo '</ul>';
	}

?>


	</main><!-- #main -->

<?php
get_footer();
