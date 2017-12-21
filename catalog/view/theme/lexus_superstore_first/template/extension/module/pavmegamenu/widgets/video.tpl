<?php if( $show_title ) { ?><div class="widget-heading box-heading"><?php echo $heading_title?></div><?php } ?>
<?php if ( isset($video_link) ) { ?>
<div class="widget box widget-video">
	<?php if( $show_title ) { ?>
		<h3 class="menu-title"><span class="menu-title"><em class="fa fa-caret-right"></em><?php echo $widget_name;?></span></h3>
		<?php } ?>
	<div class="widget-html">
		<div class="widget-inner">
			<iframe src="<?php echo $video_link ?>" style="width:<?php echo $width ?>;height:<?php echo $height ?>;"></iframe>
			<?php if ( $subinfo ) { ?>
			<div><?php $subinfo ?></div>
			<?php } ?>
		</div>
	</div>
</div>
<?php } ?>