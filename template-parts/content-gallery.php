  				<div class="container-slideshow">
					<!-- Basic slider 
					================================================== -->
						<div class="gallery-slideshow">
							<!-- Find slides fra slide-kategori billeder -->
							<?php
					
								function strip_shortcode_gallery( $content ) {
										preg_match_all( '/' . get_shortcode_regex() . '/s', $content, $matches, PREG_SET_ORDER );

										if ( ! empty( $matches ) ) {
												foreach ( $matches as $shortcode ) {
														if ( 'gallery' === $shortcode[2] ) {
																$pos = strpos( $content, $shortcode[0] );
																if( false !== $pos ) {
																		return substr_replace( $content, '', $pos, strlen( $shortcode[0] ) );
																}
														}
												}
										}

										return $content;
								}	
								$images = get_children( 'post_type=attachment&post_mime_type=image&post_parent='.$id.'&order=ASC');
								$post_thumbnail_id = get_post_thumbnail_id($id);
								$end = end($images)-1;
					
								if ( !empty($images) ) {
									foreach ( $images as $attachment_id => $attachment ) {
										if ($post_thumbnail_id != $attachment_id) {
											echo wp_get_attachment_link( $attachment_id, '' , false, false, wp_get_attachment_image( $attachment_id, 'medium' ), array( 'class' => 'thickbox') );
										}
									}
								}
								$content = strip_shortcode_gallery( get_the_content() );                                        
            		$content = str_replace( ']]>', ']]&gt;', apply_filters( 'the_content', $content ) ); 					
							?>
						</div>
					</div>
					<p style="margin-top: 30px;"></p>
					<?php echo $content; ?>

