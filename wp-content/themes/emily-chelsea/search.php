<?php
add_filter('ttg_block_styles_post', function () {
    return get_field('search_page', 'options');
});
?>
<?php get_header() ?>
<article class="container-fluid post page-article">
    <div class="search-page">
        <div class="d-md-flex flex-md-wrap justify-content-md-between search-page__top">
            <div class="pb-md-4 pb-md-0 md-search-page__top__left">
                <h2 class="heading-medium search-page__title">Search Results</h2>
                <?php
                global $wp_query;
                $s =  esc_html(get_query_var('s'));
                ?>
                <?php
                if (!empty($s)) {
                ?>
                    <p><?php echo $wp_query->found_posts ?> results found for <strong><?php echo get_query_var('s') ?></strong></p>
                <?php
                }
                ?>
            </div>
            <div class="search-page__top__right">
                <?php
                $search_sort = get_field('search_sort', 'options');
                if (!empty($search_sort)) {
                    echo do_shortcode($search_sort);
                }
                ?>
            </div>
        </div>

        <div class="search-page__result">
            <div class="blog-list columns-3 gutter-large">
                <div class="facetwp-template d-flex flex-wrap blog-list__inner">
                    <?php
                    while (have_posts()) {
                        the_post();
                        global $post;
                        echo TTG_Template::get_template_part('post-item-type-2', ['post' => $post]);
                    }
                    ?>
                </div>
                <?php
                if ($wp_query->max_num_pages > 1) {
                ?>
                    <nav class="woocommerce-pagination">
                        <?php
                        echo do_shortcode('[facetwp facet="load_more"]');
                        ?>
                    </nav>
                <?php
                }
                ?>
            </div>
        </div>
    </div>
    <?php
    $search_page = get_field('search_page', 'options');
    if ($search_page) {
        $content = apply_filters('the_content', $search_page->post_content);
        echo $content;
    }
    ?>
</article>
<?php get_footer() ?>