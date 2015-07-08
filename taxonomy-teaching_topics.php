<?php
/**
 * Displays taxonomy topic page
 */
get_header();
?>
<?php
$term = $wp_query->queried_object;
$title = $term->name;
if ($term->slug == "god") 
    $title = "";
?>


<?php
    $taxonomy_term = $wp_query->queried_object;
    echo hb_ui_taxonomy_topic_page($taxonomy_term);
?>
<?php get_footer();

