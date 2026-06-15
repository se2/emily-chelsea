<?php
$filters = get_field('blog_filters', 'options');
$blog_search = get_field('blog_search', 'options');
?>
<div class="d-flex flex-wrap blog-search-filter">
    <div class="blog-search-filter__left">
        <div class="blog-filter d-md-flex flex-md-wrap align-items-md-center">
            <h3 class="blog-filter__title">FILTER BY CATEGORY:</h3>
            <!-- <input class="blog-filter__checkbox" type="checkbox" /> -->
            <div class="blog-filter__items d-md-flex flex-md-wrap">
                <?php
                if (!empty($filters)) {
                    foreach ($filters as $key => $value) {
                        if (!empty($value['shortcode'])) {
                ?>
                            <div class="blog-filter__item">
                                <?php echo do_shortcode($value['shortcode']) ?>
                            </div>
                <?php
                        }
                    }
                }
                ?>
            </div>
        </div>
    </div>
    <div class="blog-search-filter__right">
        <div class="blog-search">
            <h3 class="blog-search__title">SEARCH ARTICLES:</h3>
            <?php echo do_shortcode($blog_search) ?>
        </div>
    </div>

</div>