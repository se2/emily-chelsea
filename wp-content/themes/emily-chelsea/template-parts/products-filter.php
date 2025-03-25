<?php
$filters = get_field('filters', 'options');
$custom_filter = get_field('custom_filter', 'options');

if (!empty($custom_filter)) {
    foreach ($custom_filter as $key => $value) {
        $type = $value['type'];
        $term = $value['term'];
        $page = $value['page'];
        if (
            $type === 'term'
            && !empty($term)
            && is_tax($term->taxonomy, $term)
            && !empty($value['filter'])
        ) {
            $filters = $value['filter'];
            break;
        }

        if (
            $type === 'page'
            && !empty($page)
            && is_page($page)
            && !empty($value['filter'])
        ) {
            $filters = $value['filter'];
            break;
        }
    }
}

$attrs = [];

if (count($filters) < 3) {
    $attrs['style'] = ['--products-filter-max-width' => (count($filters) * 200) . 'px'];
}

?>
<div class="products-filter" id="products-filter" <?php echo TTG_Util::generate_html_attrs($attrs); ?>>
    <div class="d-md-flex flex-md-wrap align-items-md-center">
        <h3 class="products-filter__title">FILTER BY:</h3>
        <input class="products-filter__checkbox" type="checkbox" />
        <div class="products-filter__items d-md-flex flex-md-wrap">
            <?php
            if (!empty($filters)) {
                foreach ($filters as $key => $value) {
                    $attr = shortcode_parse_atts($value['shortcode']);
                    $facet_name = str_replace("facet=", "", $attr[1]);
                    $facet_name = str_replace("]", "", $facet_name);
                    $facet_name = str_replace('"', "", $facet_name);
                    $facet = FWP()->helper->get_facet_by_name($facet_name);
                    $label = !empty($facet['label_any']) ? $facet['label_any'] : $facet['label'];

                    if (!empty($value['shortcode'])) {
            ?>
                        <?php
                        if ($value['is_custom_dropdown']) {

                        ?>
                            <div class="products-filter__item">
                                <input class="products-filter__item__toggle" type="checkbox">
                                <div class="products-filter__item__label">
                                    <?php echo  $value['label'] ?>
                                    <div class="products-filter__item__label__arrow"></div>
                                </div>
                                <div class="products-filter__item__content">
                                    <?php echo do_shortcode($value['shortcode']) ?>
                                </div>
                            </div>
                        <?php
                        } else {
                        ?>
                            <div class="products-filter__item">
                                <div class="products-filter__item__label disabled label-type-<?php echo $facet['type']; ?>" style="display:none">
                                    <?php echo  $label ?> (0)
                                </div>
                                <?php
                                $content = do_shortcode($value['shortcode']);
                                echo do_shortcode($value['shortcode']);
                                ?>
                            </div>
                        <?php
                        }
                        ?>
            <?php
                    }
                }
            }
            ?>
        </div>
    </div>
    <?php
    echo TTG_Template::get_template_part('product-filter-user-selection');
    ?>
</div>