<?php
extract($args);
?>
<div class="blog-head">
    <div class="blog-head__inner">
        <h1 class="blog-head__title"><?php echo $title; ?></h1>
        <div class="blog-head__meta"><?php echo $datetime; ?></div>
    </div>
</div>
<div class="blog-head-thumb">
    <?php echo $image; ?>
</div>