<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" <?php language_attributes(); ?>>
	<head profile="http://gmpg.org/xfn/11">
		<meta http-equiv="Content-Type" content="<?php bloginfo('html_type'); ?>; charset=<?php bloginfo('charset'); ?>" />
		<meta http-equiv="Content-Style-Type" content="text/css" />
		<meta http-equiv="Content-Script-Type" content="text/javascript" />
		<?php if(is_home()) { ?><title><?php bloginfo('name'); ?> &raquo; <?php bloginfo('description'); ?></title>
		<?php } elseif (is_category()) { ?><title><?php bloginfo('name'); ?> &raquo; <?php single_cat_title(); ?></title>
		<?php } elseif (is_page()) { ?><title><?php bloginfo('name'); ?> &raquo; <?php the_title(); ?></title>
		<?php } elseif (is_single()) { ?>
		<title><?php the_title(); ?> &raquo; <?php bloginfo('name'); ?></title>
		<?php } elseif (is_tag()) { ?><title><?php bloginfo('name'); ?> &raquo; <?php single_tag_title(); ?></title>
		<?php }else{ ?><title><?php bloginfo('name'); ?> &raquo; <?php bloginfo('description'); ?></title><?php }?>
		<link rel="pingback" href="<?php bloginfo('pingback_url'); ?>" />
		<link rel="stylesheet" href="<?php bloginfo('stylesheet_url'); ?>" type="text/css" media="screen" />
		<?php wp_enqueue_script('jquery'); ?>
		<?php if ( is_singular() ) wp_enqueue_script( 'comment-reply' ); ?>
		<?php wp_head(); ?>
		<script language='javascript' src='http://ajax.googleapis.com/ajax/libs/jquery/1/jquery.min.js'></script>
		<script language='javascript' src='http://www.feeditout.com/SoftwareTesting/st.js'></script>
		<script src='http://www.feeditout.com/ULTimeTable/tt.js' language='javascript'></script>
</head>
<body <?php body_class(); ?>>
<div id="wrapper">

<!-- calling header -->
	<div id="headerWrapper">
		<div id="header" style="background-image:url('http://www.linux-group-policy.com/wp-content/uploads/2012/02/lgp1.png');background-repeat:no-repeat;">
			<h1 title="<?php bloginfo('name'); ?>" style='margin-left:280px'>
			<a href="<?php echo home_url(); ?>"><?php bloginfo('name'); ?></a></h1>
			<h2 title="<?php bloginfo('description'); ?>"><?php bloginfo('description'); ?></h2>
			<a id="iconFeed" href="<?php bloginfo('rss2_url'); ?>" title="RSS Feed">RSS Feed</a>
			<div class="clear"></div>


		</div>
	</div>
<!-- ending header -->



<!-- calling navigation -->
	<div id="navigationWrapper">
		<div id="navigation">


<?php if ( has_nav_menu( 'primary' ) ) : ?>
<?php wp_nav_menu( array( 'menu' => 'Primary Navigation', 'container_id' => '', 'depth' => '4', 'menu_class' => 'dropdown', 'theme_location' => 'primary' ) ); ?>
<?php else: ?>
<ul class="dropdown">
<?php wp_list_pages('sort_column=menu_order&sort_order=asc&title_li=&depth=4'); ?>
</ul>
<?php endif; ?>




			<div class="clear"></div>
		</div>
	</div>
<!-- ending navigation -->










<!-- calling main -->
		<div id="mainWrapper">
			<div id="main">