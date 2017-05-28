<?php
global $get_meta , $post;
if( ( tie_get_option( 'share_post' ) &&  empty( $get_meta["tie_hide_share"][0] ) ) || $get_meta["tie_hide_share"][0] == 'no' ):?>

<div class="share-post">
	<span class="share-text"><?php _e( 'Share !' , 'tie' );?></span>
	<ul>			
	<?php if( tie_get_option( 'share_tweet' ) ): ?>
		<li><a href="https://twitter.com/share" class="twitter-share-button" data-url="<?php the_permalink(); ?>" data-text="<?php the_title(); ?>" data-via="<?php echo tie_get_option( 'share_twitter_username' ) ?>" data-lang="en">tweet</a> <script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0];if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src="//platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");</script></li>
	<?php endif; ?>
	<?php if( tie_get_option( 'share_facebook' ) ): ?>
		<li>
			<div id="fb-root"></div>
			<script>(function(d, s, id) {
			  var js, fjs = d.getElementsByTagName(s)[0];
			  if (d.getElementById(id)) return;
			  js = d.createElement(s); js.id = id;
			  js.src = "//connect.facebook.net/en_US/all.js#xfbml=1";
			  fjs.parentNode.insertBefore(js, fjs);
			}(document, 'script', 'facebook-jssdk'));</script>
			<div class="fb-like" data-href="<?php the_permalink(); ?>" data-send="false" data-layout="button_count" data-width="90" data-show-faces="false"></div>
		</li>
	<?php endif; ?>
	<?php if( tie_get_option( 'share_google' ) ): ?>
		<li style="width:80px;"><div class="g-plusone" data-size="medium" data-href="<?php the_permalink(); ?>"></div>
			<script type='text/javascript'>
			  (function() {
				var po = document.createElement('script'); po.type = 'text/javascript'; po.async = true;
				po.src = 'https://apis.google.com/js/plusone.js';
				var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(po, s);
			  })();
			</script>
		</li>
	<?php endif; ?>
	<?php if( tie_get_option( 'share_stumble' ) ): ?>
		<li><su:badge layout="2"></su:badge>
			<script type="text/javascript">
				(function() {
					var li = document.createElement('script'); li.type = 'text/javascript'; li.async = true;
					li.src = ('https:' == document.location.protocol ? 'https:' : 'http:') + '//platform.stumbleupon.com/1/widgets.js';
					var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(li, s);
				})();
			</script>
		</li>
	<?php endif; ?>
	<?php if( tie_get_option( 'share_linkdin' ) ): ?>
		<li><script src="http://platform.linkedin.com/in.js" type="text/javascript"></script><script type="IN/Share" data-url="<?php the_permalink(); ?>" data-counter="right"></script></li>
	<?php endif; ?>
	<?php if( tie_get_option( 'share_pinterest' ) ): ?>
		<li style="width:80px;"><script type="text/javascript" src="http://assets.pinterest.com/js/pinit.js"></script><a href="http://pinterest.com/pin/create/button/?url=<?php the_permalink(); ?>&amp;media=<?php echo tie_thumb_src('', 660 ,330); ?>" class="pin-it-button" count-layout="horizontal"><img border="0" src="http://assets.pinterest.com/images/PinExt.png" title="Pin It" /></a></li>
	<?php endif; ?>
	</ul>
	<div class="clear"></div>
</div> <!-- .share-post -->
<?php endif; ?>