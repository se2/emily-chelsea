<?php

use TTG\Build_Ring\Controller;
use TTG\Build_Ring\MODE;
use TTG\Build_Ring\PRODUCT_TYPES;

$filters = get_field('filters', 'options');
$custom_filter = get_field('custom_filter', 'options');
$mode = Controller::get_mode();
$current_term = $mode === MODE::START_WITH_SETTING ? PRODUCT_TYPES::RING : PRODUCT_TYPES::STONE;

if (!empty($custom_filter)) {
    foreach ($custom_filter as $key => $value) {
        $type = $value['type'];
        $term = $value['term'];
        $page = $value['page'];

        if (
            $type === 'term'
            && !empty($term)
            && $term->slug === $current_term
            && !empty($value['filter'])
        ) {
            $filters = $value['filter'];
            break;
        }
    }
}
?>
<?php
if ($mode === MODE::START_WITH_SETTING) {
?>
    <style>
        .products-filter--aside .products-filter__item:nth-child(2),
        .products-filter--aside .products-filter__item:nth-child(3) {
            display: none !important;
        }
    </style>
<?php
}
?>
<div class="products-filter products-filter--not-sticky products-filter--aside">
    <div>
        <h3 class="products-filter__title">FILTER BY:</h3>
        <input class="products-filter__checkbox" type="checkbox" />
        <div class="products-filter__items">
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