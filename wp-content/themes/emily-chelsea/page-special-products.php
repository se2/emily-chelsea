<?php

/**
 * Template Name: Special Products
 */
?>
<?php get_header() ?>
<header class="woocommerce-products-header">
    <div class="d-flex">
        <h1 class="woocommerce-products-header__title page-title"><?php the_title(); ?></h1>
    </div>
    <?php echo do_shortcode('[facetwp facet="result_count"]'); ?>
</header>
<?php echo TTG_Template::get_template_part('products-filter'); ?>
<article class="container-fluid post page-article">
    <?php the_content() ?>
</article>
<?php get_footer() ?>