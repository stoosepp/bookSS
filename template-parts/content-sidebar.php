<nav class="left-toc">
		 <?php
		 if ( is_page() ) {
			echo '<div id="toc-list">';
			echo '<a id="home-icon" href="'.get_home_url().'"><i class="fas fa-home"></i></a>';

			$bookRoot = getRootForPage($post);
			//PUT PHOTO HERE
			$featured_img_url = get_the_post_thumbnail_url($bookRoot);
			echo '<div class="book-image">';
			if ($featured_img_url){
				echo '<img  src="'.esc_url($featured_img_url).'" rel="lightbox">';
			}
			else{
				$topLevelPages = getTopLevelPages();
				consolePrint('There are '.count($topLevelPages).' Books total');
				$order = $post->menu_order;
				consolePrint('This book is at index '.$order);
				$hueRotate = $order/count($topLevelPages);
				echo '<img  style="filter:hue-rotate('.$hueRotate.'turn);" src="'.esc_url(get_template_directory_uri()).'/images/book-cover.jpg" rel="lightbox">';
			}
			echo '</div>';
			//BOOK TITLE
			echo '<h1> <a href='.get_permalink($bookRoot).'>'.get_the_title($bookRoot).'</a></h1>';//Book Title
			?>
			<form class="search-form" action="<?php bloginfo( 'url' ); ?>/" method="get">
			<i class="far fa-search"></i>
			<input type="text" name="s" id="search" placeholder="Search" value="<?php the_search_query(); ?>" />
			</form><?php
			get_template_part( 'template-parts/content-pagetoc', get_post_type() );
			get_template_part( 'template-parts/content-switches', get_post_type() );
		}
		 ?>
		 <!-- Rounded switch -->
	</nav>