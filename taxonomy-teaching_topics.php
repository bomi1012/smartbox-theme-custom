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
oxy_create_hero_section(hb_get_taxonomy_image('teaching_topics', $term->slug, hb_enum_taxonomy_image_type::banner_image), $title, hb_enum_taxonomy_image_type::banner_image);
?>
<?php
    $taxonomy_term = $wp_query->queried_object;
    echo hb_ui_taxonomy_topic_page($taxonomy_term);
?>
<?php get_footer();

