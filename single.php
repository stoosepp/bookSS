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
?>
<?php get_header(); ?>
<main id="primary" class="site-main">
	<!--SIDEBAR -->
	 <nav class="left-toc">
		 <?php

		echo '<div id="toc-list">';
		echo '<a id="home-icon" href="'.get_home_url().'"><i class="fas fa-home"></i></a>';
		echo '<div class="book-image">';
		echo '<img src="'.esc_url(get_template_directory_uri()).'/images/paper.jpg" rel="lightbox">';
		echo '</div>';
		//CATEGORY TITLE
		$category = get_the_category()[0];
		echo '<h1>'.$category->name.'</h1>';
		?>
		<form class="search-form" action="/" method="get">
		<i class="far fa-search"></i>
		<input type="text" name="s" id="search" placeholder="Search" value="<?php ?>" />
		</form><?php
		get_template_part( 'template-parts/content-posttoc', get_post_type() );
		get_template_part( 'template-parts/content-switches', get_post_type() );

		 ?>
		 <!-- Rounded switch -->

	</nav>
	 <!--ARTICLE -->
	 <div class="article">

	<!--ARTICLE TITLE / HEADER -->
	<?php get_template_part( 'template-parts/content-breadcrumbs', get_post_type() );?>
	<!--ARTICLE BODY -->
	<div class="article-body">

		<?php
			if ( have_posts() ) :
				if ( is_home() && ! is_front_page() ) :
					?>
					<?php
				endif;
				/* Start the Loop */
				while ( have_posts() ) :
					?><a class="new-window-link" href="<?php echo the_permalink(); ?>" target="_blank">Open in New Window</a><?php

					the_post();

					get_template_part( 'template-parts/content', get_post_type() );

				endwhile;
				the_posts_navigation();
			else :
				get_template_part( 'template-parts/content', 'none' );

			endif;

			get_footer();
			?>
		</div>
		<!--HEADING TOC AND SIDENOTES -->
		<?php get_template_part( 'template-parts/content-headernav', get_post_type() ); ?>

	</div>
</main><!-- #main -->


