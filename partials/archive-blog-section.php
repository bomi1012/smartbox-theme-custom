<?php
/**
 * Shows a simple blog section, used for showing all glogs in hb_loop_blog
 * @author tomikb
 */
?>

<?php 
$allow_comments = oxy_get_option( 'site_comments' );
 ?>
<article id="post-<?php the_ID(); ?>" <?php post_class('row-fluid'); ?>>
    <?php get_template_part( 'partials/post-gutter' ); ?>
    <div class="<?php echo  oxy_get_option( 'blog_image_size' ) == 'normal'? 'span10':'span12'; ?> post-body">
        <div class="post-head">
            <h2 class="small-screen-center">
                <?php if ( is_single() ) : ?>
                    <?php the_title(); ?>
                <?php else : ?>
                    <a href="<?php the_permalink(); ?>" title="<?php echo esc_attr( sprintf( __( 'Permalink to %s', THEME_FRONT_TD ), the_title_attribute( 'echo=0' ) ) ); ?>" rel="bookmark">
                        <?php the_title(); ?>
                    </a>
                <?php endif; // is_single() ?>
            </h2>
            <?php get_template_part( 'partials/post-extras' ); ?>
        </div>
        <div class="entry-content">
            <?php
            if ( has_post_thumbnail() ) {
                $img = wp_get_attachment_image_src( get_post_thumbnail_id($post->ID), 'full' );
                $img_link = is_single() ? $img[0] : get_permalink();
                $link_class = is_single() ? 'class="fancybox"' : '';
                echo '<figure>';
                if( oxy_get_option('blog_fancybox') == 'on') {
                    echo '<a href="' . $img_link . '" ' . $link_class . '>';
                }
                echo '<img alt="featured image" src="'.$img[0].'">';
                if( oxy_get_option('blog_fancybox') == 'on') {
                    echo '</a>';
                }
                echo '</figure>';
            } ?>
            <?php $content= oxy_limit_excerpt(strip_tags(get_the_content()), 100) ;
                $more_text=  hb_get_more_text($post->post_type);
                $link = get_permalink();
                $content .= '<a href="' . $link . '" class="more-link">' . $more_text . '</a>'; 
                echo apply_filters('the_content', $content);?>
        </div>
    </div>
</article>

