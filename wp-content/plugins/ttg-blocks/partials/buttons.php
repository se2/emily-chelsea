<?php
extract($args);
?>
<?php
if (!empty($buttons)) {
?>
    <div class="ttg-buttons">
        <div class="d-flex flex-wrap ttg-buttons__inner">
            <?php
            foreach ($buttons as $key => $btn) {
                echo TTG_Blocks_Template_Parts_Helper::button(
                    array(
                        'style' => $btn['style'],
                        'link' => $btn['link'],
                        'size' => $btn['size'],
                        'custom_style' => [
                            'type' => $btn['type'],
                            'common' => $btn['custom_style'],
                            'viewport' => [
                                'pc' => $btn['pc'],
                                'tablet' => $btn['tablet'],
                                'mobile' => $btn['mobile']
                            ]
                        ],

                    )
                );
            }
            ?>
        </div>
    </div>
<?php
}
?>