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
<h1 style="font-family: Roboto Slab,Arial, Helvetica, sans-serif;font-size: 4em; padding-bottom: 0.5em"><?php echo $term->name?>
    </h1>


<?php
    $taxonomy_term = $wp_query->queried_object;
    echo hb_ui_taxonomy_topic_page($taxonomy_term);
?>
<?php get_footer();

