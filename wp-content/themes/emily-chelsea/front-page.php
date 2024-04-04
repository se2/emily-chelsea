<?php
get_header();
if (have_posts()) {
  while (have_posts()) {
    the_post();
?>
    <article class="container-fluid post page-article">
      <?php the_content() ?>
    </article>
<?php
  }
}
get_footer();
?>