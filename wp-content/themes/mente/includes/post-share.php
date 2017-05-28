<?php
global $post;
if( tie_get_option( 'archives_socail' ) ):?>
<div class="mini-share-post">
	<ul>			
		<li><a href="https://twitter.com/share" class="twitter-share-button" data-url="<?php the_permalink(); ?>" data-text="<?php the_title(); ?>" data-via="<?php echo tie_get_option( 'share_twitter_username' ) ?>" data-lang="en">tweet</a> <script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0];if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src="//platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");</script></li>
		<li><iframe src="http://www.facebook.com/plugins/like.php?href=<?php the_permalink(); ?>&amp;layout=button_count&amp;show_faces=false&amp;width=105&amp;action=like&amp;colorscheme=light&amp;height=21" scrolling="no" frameborder="0" style="border:none; overflow:hidden; width:105px; height:21px;" allowTransparency="true"></iframe></li>
		<li><div class="g-plusone" data-size="medium" data-href="<?php the_permalink(); ?>"></div>
			<script type='text/javascript'>
			  (function() {
				var po = document.createElement('script'); po.type = 'text/javascript'; po.async = true;
				po.src = 'https://apis.google.com/js/plusone.js';
				var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(po, s);
			  })();
			</script>
		</li>
		<li><script type="text/javascript" src="http://assets.pinterest.com/js/pinit.js"></script><a href="http://pinterest.com/pin/create/button/?url=<?php the_permalink(); ?>&amp;media=<?php echo tie_thumb_src('', 660 ,330); ?>" class="pin-it-button" count-layout="horizontal"><img border="0" src="http://assets.pinterest.com/images/PinExt.png" title="Pin It" /></a></li>
	</ul>
</div> <!-- .share-post -->
<?php endif; ?>