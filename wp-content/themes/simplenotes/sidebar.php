<!-- calling sidebar -->
<div id="sidebar">

<!-- calling search bar -->
	<div id="searchWrapper">
			<div id="search">
				<?php get_search_form(); ?>
					<div class="clear"></div>
			</div>
	</div>
<!-- ending search bar -->




<?php if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar("Sidebar") ) : ?>
<!-- calling random posts -->
		<div class="sidebarWrapper">
		        <div class="sidebarHeader"></div>
			<h4 title="Random Posts">Random Posts</h4>
		<div class="sidebar">
			<?php simplenotes_randomPosts(); ?>
		</div>
		        <div class="sidebarFooter"></div>
		</div>
<!-- ending random posts -->


<!-- calling categories -->
		<div class="sidebarWrapper">
		        <div class="sidebarHeader"></div>
			<h4 title="Categories">Categories</h4>
		<div class="sidebar">
			<ul>
				<?php wp_list_categories('orderby=name&show_count=1&title_li=&depth=3'); ?>
			</ul>
		</div>
		        <div class="sidebarFooter"></div>
		</div>
<!-- ending categories -->


<!-- calling archives -->
		<div class="sidebarWrapper">
		        <div class="sidebarHeader"></div>
			<h4 title="Archives">Archives</h4>
		<div class="sidebar">

<select name="archive-dropdown" onchange="document.location.href=this.options[this.selectedIndex].value;">
  <option value=""><?php echo esc_attr( __( 'Select Month' ) ); ?></option> 
  <?php wp_get_archives( 'type=monthly&format=option&show_post_count=1' ); ?>
</select>

		</div>
		        <div class="sidebarFooter"></div>
		</div>
<!-- ending archives -->







<!-- calling calendar -->
		<div class="sidebarWrapper">
		        <div class="sidebarHeader"></div>
			<h4 title="Calendar">Calendar</h4>
		<div class="sidebar">
			<?php get_calendar(); ?>
		</div>
		        <div class="sidebarFooter"></div>
		</div>
<!-- ending calendar -->


<!-- calling tags -->
		<div class="sidebarWrapper">
		        <div class="sidebarHeader"></div>
			<h4 title="Tags">Tags</h4>
		<div class="sidebar">
			<?php wp_tag_cloud('smallest=8&largest=26&orderby=name&order=ASC'); ?>
		</div>
		        <div class="sidebarFooter"></div>
		</div>
<!-- ending tags -->









<?php endif; ?>
</div>

<!-- ending sidebar -->