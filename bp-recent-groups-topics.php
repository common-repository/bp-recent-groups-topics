<?php

	// Start class bprft_widget //
	
	class bprft_widget extends WP_Widget {

	// Constructor //

		function bprft_widget() {
			$widget_ops = array( 'classname' => 'bprft_widget', 'description' => 'Displays recent buddypress forum topics' ); // Widget Settings
			$control_ops = array( 'id_base' => 'bprft_widget' ); // Widget Control Settings
			$this->WP_Widget( 'bprft_widget', 'BP recent forum topic', $widget_ops, $control_ops ); // Create the widget
		}

	// Extract Args //

		function widget($args, $instance) {
			global $bp;
			extract( $args );
			$title 				= apply_filters('widget_title', $instance['title']); // the widget title
			$topicsnumber 		= $instance['topics_number']; // the number of topics to show
			$topics_type		= $instance['topics_type']; // lenegth of title in chars
			$showexcerpt 		= isset($instance['show_excerpt']) ? $instance['show_excerpt'] : false ; // show excerpt
			$showposter 		= isset($instance['show_poster']) ? $instance['show_poster'] : false ; // show poster
			$showposteravatar 	= isset($instance['show_poster_avatar']) ? $instance['show_poster_avatar'] : false ; // show poster avatar
			$posteravatarsize	= $instance['poster_avatar_size']; // poster avatar size
			$showgroup 			= isset($instance['show_group']) ? $instance['show_group'] : false ; // show excerpt
			$showgroupavatar 	= isset($instance['show_group_avatar']) ? $instance['show_group_avatar'] : false ; // show group avatar
			$groupavatarsize	= $instance['group_avatar_size']; // group avatar size			
			$showfreshness		= isset($instance['show_freshness']) ? $instance['show_freshness'] : false ; // show freshness author
			$showsincedate		= isset($instance['show_sincedate']) ? $instance['show_sincedate'] : false ; // show since date
			$showfreshavatar 	= isset($instance['show_fresh_avatar']) ? $instance['show_fresh_avatar'] : false ; // show freshness author avatar
			$freshavatarsize	= $instance['fresh_avatar_size']; // freshness author avatar size	
			$groups				= $instance['groups']; // which groups
			$showcredit			= $instance['show_credit']; // show author credit
			$users				= $instance['users']; // which users
			$showtotalposts		= isset($instance['show_total_posts']) ? $instance['show_total_posts'] : false ; // show total posts

	// Before widget //

			echo $before_widget;

	// Title of widget //

			if ( $title ) { echo $before_title . $title . $after_title; }

	// Widget output //
		$slug_array = explode(',', $groups);$forumid='';$c='';$d='';
		foreach ($slug_array as $slug) {
		   if (empty($forumid)) $d=''; else $d=','; $c = BP_Groups_Group::group_exists( utf8_uri_encode($slug )); 
		   if (!empty($c)) $forumid .= $d.$c;
		}
		if (!empty($forumid)) $forumid='&forum_id='.$forumid;
		unset($slug_array);
		$slug_array = explode(',', $users);$usersid='';$c='';$d='';
		foreach ($slug_array as $slug) {
		   if (empty($usersid)) $d=''; else $d=','; $c = bp_core_get_userid( utf8_uri_encode($slug )); 
		   if (!empty($c)) $usersid .= $d.$c;
		}
		if (!empty($usersid)) $usersid='&user_id='.$usersid;		

		?>
		<div id="bprft"><ul>
		<?php if ( bp_has_forum_topics( 'page=false&max='.$topicsnumber.'&type='.$topics_type.$forumid.$usersid) ) : ?>
		<?php while ( bp_forum_topics() ) : bp_the_forum_topic(); ?>
		<li>
		<a class="topic-title" href="<?php bp_the_topic_permalink() ?>" title="<?php bp_the_topic_title() ?>">
		<?php echo bp_create_excerpt( bp_get_the_topic_title()) ?></a>
		<?php if ($showexcerpt == TRUE) { ?><br /><span class="topic-excerpt"><?php echo (strip_tags(bp_get_the_topic_latest_post_excerpt())) ?></span><?php } ?>
		</li>
		<div id="bprft-extrainfo">
		<?php if ($showposter) { ?>
			<span class="topic-by">
				<?php if ($showposteravatar == TRUE) /* translators: "started by [poster] in [forum]" */ printf(__('Started by %1$s', 'buddypress'), bp_get_the_topic_poster_avatar('height='.$posteravatarsize.'&width='.$posteravatarsize) . bp_get_the_topic_poster_name()); else printf(__('Started by %1$s', 'buddypress'), bp_get_the_topic_poster_name());  ?>
			</span>
			<?php } ?>
		<?php if ($showgroup == TRUE && !bp_is_group_forum())  { ?>
			<span class="topic-in">
			<?php
			if ($showgroupavatar == TRUE)
				$topic_in = '<a href="' . bp_get_the_topic_object_permalink() . '">' . bp_get_the_topic_object_avatar('type=thumb&width='.$groupavatarsize.'&height='.$groupavatarsize) . '</a>' .
													'<a href="' . bp_get_the_topic_object_permalink() . '" title="' . bp_get_the_topic_object_name() . '">' . bp_get_the_topic_object_name() .'</a>';
			else 
				$topic_in = '<a href="' . bp_get_the_topic_object_permalink() . '" title="' . bp_get_the_topic_object_name() . '">' . bp_get_the_topic_object_name() .'</a>';
									/* translators: "started by [poster] in [forum]" */
			printf(__('in %1$s', 'buddypress'), $topic_in);
			?>
			</span>
		<?php } ?>
		<?php if ($showfreshness == TRUE)  { ?>
			<span class="freshness-author">
			<?php _e('freshness author', 'bprft'); ?>
			<?php if ($showfreshavatar == TRUE) { ?>
							<a href="<?php bp_the_topic_permalink(); ?>"><?php bp_the_topic_last_poster_avatar('type=thumb&width='.$freshavatarsize.'&height='.$freshavatarsize); ?></a>
							<?php bp_the_topic_last_poster_name(); ?>
			<?php }else{ ?>
							<?php bp_the_topic_last_poster_name(); ?>
				<?php } ?>
			</span>
		<?php } ?>
		<?php if ($showsincedate == TRUE) { ?>
			<span class="time-since"><?php bp_the_topic_time_since_last_post(); ?></span>
		<?php } ?>
		<?php if ($showtotalposts == TRUE) { ?>
			<span class="post-count"><?php _e('total posts: ', 'bprft'); bp_the_topic_total_posts(); ?></span>
		<?php } ?>	
		</div>		

		<?php endwhile; ?>

		<?php else: ?>
		<div>
			<p><?php _e( 'Sorry, there were no forum topics found.', 'buddypress' ) ?></p>
		</div>
		<?php endif;	?>
		</ul>
		<?php if ($showcredit == TRUE) { ?>
			<p class="authorcredit"><a href="http://www.bgextensions.bgvhod.com">&copy; BgExtensions 2012</a></p>
		<?php } ?>
		</div>
		<?php

	// After widget //

			echo $after_widget;
		}

	// Update Settings //

		function update($new_instance, $old_instance) {
			$instance['title'] = strip_tags($new_instance['title']);
			$instance['topics_number'] = strip_tags($new_instance['topics_number']);
			$instance['topics_type'] = strip_tags($new_instance['topics_type']);
			$instance['show_excerpt'] = $new_instance['show_excerpt'];
			$instance['show_poster'] = $new_instance['show_poster'];
			$instance['show_poster_avatar'] = $new_instance['show_poster_avatar'];
			$instance['poster_avatar_size'] = strip_tags($new_instance['poster_avatar_size']);
			$instance['show_group'] = $new_instance['show_group'];
			$instance['show_group_avatar'] = $new_instance['show_group_avatar'];
			$instance['group_avatar_size'] = strip_tags($new_instance['group_avatar_size']);			
			$instance['show_freshness'] = $new_instance['show_freshness'];
			$instance['show_sincedate'] = $new_instance['show_sincedate'];
			$instance['show_fresh_avatar'] = $new_instance['show_fresh_avatar'];
			$instance['fresh_avatar_size'] = strip_tags($new_instance['fresh_avatar_size']);	
			$instance['groups'] = strip_tags($new_instance['groups']);
			$instance['show_credit'] = $new_instance['show_credit'];
			$instance['users'] = strip_tags($new_instance['users']);
			$instance['show_total_posts'] = $new_instance['show_total_posts'];
			return $instance;
		}

	// Main bp_the_topic_latest_post_excerpt()
		
		function bp_get_the_topic_latest_post_excerpt( $len ) {
			global $forum_template;
 
//			$defaults = array(
//				'length' => 225
//			);
  
//			$r = wp_parse_args( $args, $defaults );
//			extract( $r, EXTR_SKIP );
	
			$post = bp_forums_get_post( $forum_template->topic->topic_last_post_id );
			$post = bp_create_excerpt( strip_tags($post->post_text), $len );
   
			return apply_filters( 'bp_get_the_topic_latest_post_excerpt', $post, $len );
		}
		
		
	// Widget Control Panel //

		function form($instance) {

		$defaults = array( 'title' => 'Recent topics', 'topics_number' => 3, 'title_length' => '0', 'topics_type' => 'newest', 'show_credit' => 'on', 'poster_avatar_size' => 20, 'group_avatar_size' => 20, 'fresh_avatar_size' => 20 );
		$instance = wp_parse_args( (array) $instance, $defaults ); ?>

		<p>
			<label for="<?php echo $this->get_field_id('title'); ?>">Title:</label>
			<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>'" type="text" value="<?php echo $instance['title']; ?>" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('topics_number'); ?>"><?php _e('Number of topics to display','bprft'); ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id('topics_number'); ?>" name="<?php echo $this->get_field_name('topics_number'); ?>" type="text" value="<?php echo $instance['topics_number']; ?>" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('topics_type'); ?>">Topics type:</label>
			<select id="<?php echo $this->get_field_id('topics_type'); ?>" name="<?php echo $this->get_field_name('topics_type'); ?>" class="widefat" style="width:100%;">
				<option value="newest" <?php selected('newest', $instance['topics_type']); ?>>Recent topics</option>
				<option value="popular" <?php selected('popular', $instance['topics_type']); ?>>Popular topics</option>
				<option value="unreplied" <?php selected('unreplied', $instance['topics_type']); ?>>Unreplied topics</option>
			</select>
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('show_excert'); ?>"><?php _e('Show excerpt?','bprft'); ?></label>
			<input type="checkbox" class="checkbox" <?php checked( $instance['show_excerpt'], 'on' ); ?> id="<?php echo $this->get_field_id('show_excerpt'); ?>" name="<?php echo $this->get_field_name('show_excerpt'); ?>" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('show_poster'); ?>"><?php _e('Show poster?','bprft'); ?></label>
			<input type="checkbox" class="checkbox" <?php checked( $instance['show_poster'], 'on' ); ?> id="<?php echo $this->get_field_id('show_poster'); ?>" name="<?php echo $this->get_field_name('show_poster'); ?>" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('show_poster_avatar'); ?>"><?php _e('Show poster avatar?','bprft'); ?></label>
			<input type="checkbox" class="checkbox" <?php checked( $instance['show_poster_avatar'], 'on' ); ?> id="<?php echo $this->get_field_id('show_poster_avatar'); ?>" name="<?php echo $this->get_field_name('show_poster_avatar'); ?>" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('poster_avatar_size'); ?>"><?php _e('Poster avatar size in px','bprft'); ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id('poster_avatar_size'); ?>" name="<?php echo $this->get_field_name('poster_avatar_size'); ?>" type="text" value="<?php echo $instance['poster_avatar_size']; ?>" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('show_group'); ?>"><?php _e('Show group name?','bprft'); ?></label>
			<input type="checkbox" class="checkbox" <?php checked( $instance['show_group'], 'on' ); ?> id="<?php echo $this->get_field_id('show_group'); ?>" name="<?php echo $this->get_field_name('show_group'); ?>" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('show_group_avatar'); ?>"><?php _e('Show group avatar?','bprft'); ?></label>
			<input type="checkbox" class="checkbox" <?php checked( $instance['show_group_avatar'], 'on' ); ?> id="<?php echo $this->get_field_id('show_group_avatar'); ?>" name="<?php echo $this->get_field_name('show_group_avatar'); ?>" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('group_avatar_size'); ?>"><?php _e('Group avatar size in px','bprft'); ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id('group_avatar_size'); ?>" name="<?php echo $this->get_field_name('group_avatar_size'); ?>" type="text" value="<?php echo $instance['group_avatar_size']; ?>" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('show_freshness'); ?>"><?php _e('Show freshness author?','bprft'); ?></label>
			<input type="checkbox" class="checkbox" <?php checked( $instance['show_freshness'], 'on' ); ?> id="<?php echo $this->get_field_id('show_freshness'); ?>" name="<?php echo $this->get_field_name('show_freshness'); ?>" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('show_fresh_avatar'); ?>"><?php _e('Show freshness author avatar?','bprft'); ?></label>
			<input type="checkbox" class="checkbox" <?php checked( $instance['show_fresh_avatar'], 'on' ); ?> id="<?php echo $this->get_field_id('show_fresh_avatar'); ?>" name="<?php echo $this->get_field_name('show_fresh_avatar'); ?>" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('fresh_avatar_size'); ?>"><?php _e('Freshness author avatar size in px','bprft'); ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id('fresh_avatar_size'); ?>" name="<?php echo $this->get_field_name('fresh_avatar_size'); ?>" type="text" value="<?php echo $instance['fresh_avatar_size']; ?>" />
		</p>				
		<p>
			<label for="<?php echo $this->get_field_id('show_sincedate'); ?>"><?php _e('Show since date?','bprft'); ?></label>
			<input type="checkbox" class="checkbox" <?php checked( $instance['show_sincedate'], 'on' ); ?> id="<?php echo $this->get_field_id('show_sincedate'); ?>" name="<?php echo $this->get_field_name('show_sincedate'); ?>" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('groups'); ?>"><?php _e('Topics by groups slug (comma separated). Leave blank for all.','bprft'); ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id('groups'); ?>" name="<?php echo $this->get_field_name('groups'); ?>" type="text" value="<?php echo $instance['groups']; ?>" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('users'); ?>"><?php _e('Topics by users slug (comma separated). Leave blank for all.','bprft'); ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id('users'); ?>" name="<?php echo $this->get_field_name('users'); ?>" type="text" value="<?php echo $instance['users']; ?>" />
		</p>			
		<p>
			<label for="<?php echo $this->get_field_id('show_total_posts'); ?>"><?php _e('Show total posts?','bprft'); ?></label>
			<input type="checkbox" class="checkbox" <?php checked( $instance['show_total_posts'], 'on' ); ?> id="<?php echo $this->get_field_id('show_total_posts'); ?>" name="<?php echo $this->get_field_name('show_total_posts'); ?>" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('show_credit'); ?>"><?php _e('Give credit to plugin author?','bprft'); ?></label>
			<input type="checkbox" class="checkbox" <?php checked( $instance['show_credit'], 'on' ); ?> id="<?php echo $this->get_field_id('show_credit'); ?>" name="<?php echo $this->get_field_name('show_credit'); ?>" />
		</p>
        <?php }

}

// End class bprft_widget

add_action('widgets_init', create_function('', 'return register_widget("bprft_widget");'));
?>