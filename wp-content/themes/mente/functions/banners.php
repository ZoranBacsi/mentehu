<?php
function tie_banner( $banner , $before= false , $after = false){
	if(tie_get_option( $banner )):
		echo $before;
		?>
		<?php
		if(tie_get_option( $banner.'_img' )):
			$target="";
			if( tie_get_option( $banner.'_tab' )) $target='target="_blank"'; ?>
			
			<a href="<?php echo tie_get_option( $banner.'_url' ) ?>" title="<?php echo tie_get_option( $banner.'_alt') ?>" <?php echo $target ?>>
				<img src="<?php echo tie_get_option( $banner.'_img' ) ?>" alt="<?php echo tie_get_option( $banner.'_alt') ?>" />
			</a>
			
		<?php elseif(tie_get_option( $banner.'_adsense' )): ?>
			<?php echo htmlspecialchars_decode(tie_get_option( $banner.'_adsense' )) ?>
		<?php	
		endif;
		?>
		
		<?php
		echo $after;
	endif;
}
?>