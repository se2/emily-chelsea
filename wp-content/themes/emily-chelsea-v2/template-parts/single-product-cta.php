<?php
extract($args);
if (!empty($product->ID)) {
    $product_id = $product->ID;
    $title = get_the_title($product_id);
    $desc = apply_filters('the_content', $product->post_content);
    $link = get_field('product_cta_link', $product_id);
    $config = TTG_Config::get_post_heading($product_id);

    $attr = [
        'class' => 'single-product-cta',
        'style' => [
            '--product-cta-height-pc' => $config['pc']['height'],
            '--product-cta-max-height-pc' => $config['pc']['max_height'],
            '--product-cta-height-tablet' => $config['tablet']['height'],
            '--product-cta-max-height-tablet' => $config['tablet']['max_height'],
            '--product-cta-height-m' => $config['mobile']['height'],
            '--product-cta-max-height-m' => $config['mobile']['max_height'],
        ]
    ];
?>
    <div <?php echo TTG_Util::generate_html_attrs($attr) ?>>

        <div class="single-product-cta__inner">
            <div class="single-product-cta__bg">
                <?php
                echo TTG_Template::get_template_part('hero-banner', ['id' => $product_id]);
                ?>
            </div>

            <div class="single-product-cta__content">
                <h2 class="heading-medium single-product-cta__title"><?php echo $title ?></h2>
                <div class="ttg-post single-product-cta__desc">
                    <?php echo $desc; ?>
                </div>
                <?php

                if ($link) :
                    $link_url = $link['url'];
                    $link_title = $link['title'];
                    $link_target = $link['target'] ? $link['target'] : '_self';
                ?>
                    <a class="btn btn--medium btn--outline btn--white single-product-cta__btn" href="<?php echo esc_url($link_url); ?>" target="<?php echo esc_attr($link_target); ?>"><?php echo esc_html($link_title); ?></a>
                <?php endif; ?>

            </div>
        </div>
    </div>
<?php
}
?>