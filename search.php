<?php
/**
 * Custom search results template for WooCommerce products.
 *
 * Template Name: Search
 */

get_header(); ?>
	<div class="overskrift-baggrund">
		<h1 class="overskrift"><?php printf( esc_html__( 'Søgeresultater for: %s', 'yourtheme' ), '<span>' . get_search_query() . '</span>' ); ?></h1>
	</div>
	<section class="container sideindhold wctemplate">    
    
		<?php if ( have_posts() ) : ?>

        <div class="woocommerce-search-results">
            <?php while ( have_posts() ) : the_post(); ?>

                <?php if ( 'product' === get_post_type() ) : ?>
                    <?php
                    global $product; ?>

                    <!-- Produktboks -->
                    <div class="custom-product-item">
                        <!-- Produktbillede -->
                        <div class="product-image">
                            <a href="<?php the_permalink(); ?>">
                                <?php echo woocommerce_get_product_thumbnail(); ?>
                            </a>
                        </div>

                        <!-- Produktnavn -->
                        <h2 class="woocommerce-loop-product__title">
                            <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                        </h2>

                        <!-- Pris -->
                        <div class="product-price">
                            <?php echo $product->get_price_html(); ?>
                        </div>

                        <!-- Kort beskrivelse -->
                        <div class="product-short-description">
                            <?php echo apply_filters( 'woocommerce_short_description', $post->post_excerpt ); ?>
                        </div>

                        <!-- SKU -->
                        <div class="product-sku">
                            <strong>SKU:</strong> <?php echo $product->get_sku(); ?>
                        </div>

                        <!-- Se varen knap -->
                        <a href="<?php the_permalink(); ?>" class="button view-product-button">Se varen</a>
                    </div>

                <?php endif; ?>
            <?php endwhile; ?>
        </div>

    <?php else : ?>
        <p><?php esc_html_e( 'Ingen resultater fundet.', 'yourtheme' ); ?></p>
    <?php endif; ?>
	</section>

<?php get_footer(); ?>