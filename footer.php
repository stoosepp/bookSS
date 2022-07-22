<?php


?>

	<footer id="colophon" class="site-footer">

		<div class="site-info"><p>
			<?php if ( is_page()  || is_single()) { ?>

			Last Modified on <?php
			$u_time = get_the_time('U');
			$u_modified_time = get_the_modified_time('U');
			//if ($u_modified_time >= $u_time + 86400) {
			the_modified_time('F jS, Y');
			echo " at ";
			the_modified_time();
				echo ' by ';
			$author_id = get_the_author_meta( 'ID' );
			echo get_the_author_meta('display_name', $author_id);
			}
			$theopenbookTheme = wp_get_theme();
			$themeName = esc_html( $theopenbookTheme->get( 'Name' ));
			$themeURI = esc_html( $theopenbookTheme->get( 'ThemeURI' ));
			$authorURI =  esc_html( $theopenbookTheme->get( 'AuthorURI' ));

			if (is_front_page() || is_search()){
				//Don't show license stuff on home page
				echo '<a href="'.$themeURI.'" target="_blank">'.$themeName.' Theme</a>, 2021. </p>';
			}
			else{
				get_template_part( 'template-parts/content-licenseinfo', get_post_type() );
			}?>
		</div><!-- .site-info -->

	</footer><!-- #colophon -->
</div><!-- #page -->

<?php wp_footer(); ?>
</body>
</html>
