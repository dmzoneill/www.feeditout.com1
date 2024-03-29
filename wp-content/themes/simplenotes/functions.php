<?php
function simplenotes_load_scripts() {
	if ( !is_admin() ) {  
		wp_register_script('simplenotes_custom_script', get_template_directory_uri() . '/js/scroll.js', array('jquery') );
		wp_enqueue_script('simplenotes_custom_script');
		wp_register_script('simplenotes_custom_script2', get_template_directory_uri() . '/js/custom.js', array('jquery') );
		wp_enqueue_script('simplenotes_custom_script2');
	}
}
add_action('init', 'simplenotes_load_scripts');
?>
<?php
add_theme_support('post-thumbnails');
add_theme_support('automatic-feed-links');
if ( ! isset( $content_width ) ) $content_width = 645;
// ********************************* CALLING MN *********************************
add_action( 'init', 'simplenotes_register_menus' );
function simplenotes_register_menus() {
register_nav_menus( array(
'primary' => __( 'Primary Navigation', 'Simplenotes' ),
) );
}
// ********************************* CALLING RP *********************************
function simplenotes_randomPosts($numPosts = 5){
	query_posts(array('orderby' => 'rand', 'showposts' => $numPosts));
	?> <ul> <?php
	if (have_posts()) : while (have_posts()) : the_post();
	?>
			<li><a title="Permanent Link to <?php the_title(); ?>" href="<?php the_permalink(); ?>"><?php the_title(); ?></a></li>
		<?php
	endwhile;
	?> </ul> <?php
	endif;
	wp_reset_query();
}
// ********************************* CALLING PG *********************************
function simplenotes_pagination($pages = '', $range = 4){
     $showitems = ($range * 2)+1;  
     global $paged;
     if(empty($paged)) $paged = 1;
     if($pages == '')
     {global $wp_query;
         $pages = $wp_query->max_num_pages;
         if(!$pages)
         {$pages = 1;
         }
     }   
     if(1 != $pages)
     {
         echo "<span>Page ".$paged." of ".$pages."</span>";
         if($paged > 2 && $paged > $range+1 && $showitems < $pages) echo "<a href='".get_pagenum_link(1)."'>&laquo; First</a>";
         if($paged > 1 && $showitems < $pages) echo "<a href='".get_pagenum_link($paged - 1)."'>&lsaquo; Previous</a>";
         for ($i=1; $i <= $pages; $i++)
         {
             if (1 != $pages &&( !($i >= $paged+$range+1 || $i <= $paged-$range-1) || $pages <= $showitems ))
             {
                 echo ($paged == $i)? "<span class=\"current\">".$i."</span>":"<a href='".get_pagenum_link($i)."' class=\"inactive\">".$i."</a>";
             }
         }
         if ($paged < $pages && $showitems < $pages) echo "<a href=\"".get_pagenum_link($paged + 1)."\">Next &rsaquo;</a>";
         if ($paged < $pages-1 &&  $paged+$range-1 < $pages && $showitems < $pages) echo "<a href='".get_pagenum_link($pages)."'>Last &raquo;</a>";
         echo "\n";
     }
}
// ********************************* CALLING WG *********************************
register_sidebar(array(
'name' => 'Sidebar',
'before_widget' => '<div class="sidebarWrapper"><div class="sidebarHeader"></div>',
'after_widget' => '</div><div class="sidebarFooter"></div></div>',
'before_title' => '<h4>',
'after_title' => '</h4><div class="sidebar">',
));
?>
<?php
// ********************************* CALLING TC *********************************
if ( ! function_exists( 'simplenotes_comment' ) ) :
function simplenotes_comment( $comment, $args, $depth ) {
	$GLOBALS['comment'] = $comment;
	switch ( $comment->comment_type ) :
		case '' :
	?>
	<li <?php comment_class(); ?> id="li-comment-<?php comment_ID(); ?>">
		<div class="comments" id="comment-<?php comment_ID(); ?>">
		<div class="commentsData <?php if (1 == $comment->user_id) $oddcomment = "authcomment"; echo $oddcomment; ?>">
			<div class="gravatar"><?php echo get_avatar( $comment, 30 ); ?></a></div>
		<div class="author">
				<div class="authorName"><?php comment_author_link() ?></div>
				<div class="authordate"><a href="<?php echo esc_url( get_comment_link( $comment->comment_ID ) ); ?>"><?php printf( __( '%1$s at %2$s', 'Simplenotes' ), get_comment_date(),  get_comment_time() ); ?></a> <?php edit_comment_link( __( '(Edit)', 'Simplenotes' ), ' ' ); ?></div>
	</div>
<div class="clear"></div>
		</div>
		<div class="commentBody"><?php comment_text(); ?>
		<?php if ( $comment->comment_approved == '0' ) : ?>
			<em>Your comment is awaiting moderation.</em>
			<br />
		<?php endif; ?>
		</div>
		<div class="commentReply"><?php comment_reply_link( array_merge( $args, array( 'depth' => $depth, 'max_depth' => $args['max_depth'] ) ) ); ?></div>
<div class="clear"></div>
	</div>
	<?php
			break;
		case 'pingback'  :
		case 'trackback' :
	?>
	<li class="post pingback">
		<p>Pingback: <?php comment_author_link(); ?><?php edit_comment_link( __('(Edit)', 'Simplenotes'), ' ' ); ?></p>
	<?php
			break;
	endswitch;
}
endif;
// ********************************* CALLING BC *********************************
function simplenotes_get_breadcrumbs()
{
	global $wp_query; 
	if ( !is_home() ){
		// Start the UL
		echo '<ul>';
		// Add the Home link
		echo '<li><a href="'. home_url() .'">'. get_bloginfo('name') .'</a></li>';
		if ( is_category() ) 
		{
			$catTitle = single_cat_title( "", false );
			$cat = get_cat_ID( $catTitle );
			echo "<li> &nbsp; &raquo; ". get_category_parents( $cat, TRUE, " &raquo; " ) ."</li>";
		}
		elseif ( is_archive() && !is_category() ) 
		{
			echo "<li> &raquo; Archives</li>";
		}
		elseif ( is_attachment() ) {
			echo "<li> &raquo; Media Attachment</li>";
		}
		elseif ( is_search() ) {
			echo "<li> &nbsp; &raquo; Search Results</li>";
		}
		elseif ( is_404() ) 
		{
			echo "<li> &nbsp; &raquo; 404 Not Found</li>";
		}
		elseif ( is_single() ) 
		{
			$category = get_the_category();
			
			if( $category )
			{			
				$category_id = get_cat_ID( $category[0]->cat_name );
				
				if( $category_id )
				{				
					try 
					{
						echo '<li> &nbsp; &raquo; '. get_category_parents( $category_id, TRUE, "</li><li> &nbsp; &raquo; " );
					}
					catch (Exception $e) { }
				}
				
				echo the_title('','', FALSE) ."</li>";
			}
		}
		elseif ( is_page() ) 
		{
			$post = $wp_query->get_queried_object(); 
			if ( $post->post_parent == 0 ){ 
				echo "<li>&nbsp; &raquo; ".the_title('','', FALSE)."</li>";
			} else {
				$title = the_title('','', FALSE);
				$ancestors = array_reverse( get_post_ancestors( $post->ID ) );
				array_push($ancestors, $post->ID);
 
				foreach ( $ancestors as $ancestor ){
					if( $ancestor != end($ancestors) ){
						echo '<li>&nbsp; &raquo; <a href="'. get_permalink($ancestor) .'">'. strip_tags( apply_filters( 'single_post_title', get_the_title( $ancestor ) ) ) .'</a></li>';
					} else {
						echo '<li>&nbsp; &raquo; '. strip_tags( apply_filters( 'single_post_title', get_the_title( $ancestor ) ) ) .'</li>';
					}
				}
			}
		}
		// End the UL
		echo "</ul>";
	}
}
?>