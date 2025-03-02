<figure class="product-images">

	<div class="main-image">
		<?php the_post_thumbnail('woocommerce_single'); ?>
	</div>


	<div class="gallery-images">
		<?php global $product;
		$attachment_ids = $product->get_gallery_image_ids();
		foreach ($attachment_ids as $attachment_id) {
			$full_sized_image = wp_get_attachment_image_url($attachment_id, 'large'); ?>
			<a href="<?php echo $full_sized_image; ?>">
				<?php $attr = array(
					  'data-full' => $full_sized_image
				);
				echo wp_get_attachment_image($attachment_id, 'thumbnail', false, $attr); ?>
			</a>
		<?php } ?>
		<a href="<?php echo get_the_post_thumbnail_url(); ?>">
			<?php $full_sized_image = get_the_post_thumbnail_url(get_the_ID(), 'full');
				 $attr = array(
					'data-full' => $full_sized_image
				 );
				 the_post_thumbnail('thumbnail', $attr ); ?>
		</a>
	</div>


</figure>