<?php

require_once get_template_directory() . '/inc/options/shortcodes/shortcodes.php';
require_once CUSTOM_HELPERS_DIR . 'hb_utility.php';
require_once CUSTOM_HELPERS_DIR . 'hb_ui_elements.php';
/**
 * Custom shortcode functions go here
 * @author Andriy Sobol
 */

/**
 * @description latest taxonomy topics shown on main page
 * @param array $atts
 * @return string
 */
function hb_shortcode_latest_taxonomy_topics_as_list($atts) {
    extract(shortcode_atts(array(
        'title' => '',
        'style' => '',
        'topics' => ''
                    ), $atts));
    if (empty($topics)){
       $args = array(
       'hide_empty' => 1,
       'taxonomy' => 'teaching_topics',
       'pad_counts' => 1,
       'hierarchical' => 0,
       'number' => '2',
        );
        $categories = get_categories($args);
    } else {
        $categories = hb_get_categorien_for_entered_topics($topics);
    }
    //loop over all related posts
    $output_loop = '';
    foreach ($categories as $taxonomy) {
        $link = get_term_link($taxonomy);
        $summary = hb_get_taxonomy_term_summary_mini($taxonomy);

        $more_text = hb_ui_link(array(
            'link' => $link,
            'class' => 'more-link',
            'content' => __('Go to topic', THEME_FRONT_TD)));
        $title = hb_ui_title(
                array(
                    'tag' => 3,
                    'content' => hb_ui_link(
                            array(
                                'link' => $link,
                                'content' => $taxonomy->name))));
        $content =  $summary . $more_text;

        $output_loop .= oxy_shortcode_layout(NULL, $title . $content, "unstyled row-fluid");
    }
    $output = oxy_shortcode_layout(NULL, $output_loop, 'unstyled row-fluid');
    return hb_get_custom_shortcode_section($atts, $output);
}
add_shortcode('latest_taxonomy_topics', 'hb_shortcode_latest_taxonomy_topics_as_list');

/**
 * @description used on main page for latest videos
 * @global type $post
 * @param array $atts
 * @return string
 */
function hb_shortcode_recent_video($atts) {
    extract(shortcode_atts(array(
        'title' => '',
        'cat' => null), $atts));

    $args = array(
        'post_type' => array('oxy_video'),
        'showposts' => 3, // Number of related posts that will be shown.  
        'orderby' => 'date'
    );
    $my_query = new wp_query($args);
    if ($my_query->have_posts()) :
        global $post;
        while ($my_query->have_posts()) {
            $my_query->the_post();
            setup_postdata($post);
            $date = get_the_time(get_option("date_format"));
            $post_link = hb_get_linkformat(get_post_format());
            $icon_class_array = explode('"', oxy_post_icon($post->ID, false));

            $span_left = hb_shortcode_image(array(
                'size' => 'box-medium',
                'source' => CUSTOM_IMAGES_DIR . 'video1.jpg',
                'icon' => $icon_class_array[1],
                'link' => $post_link
            ));
            $span_left .= hb_ui_title(array(
                'tag' => 5,
                'class' => 'text-center light',
                'content' => $date));

            $title_right = hb_ui_title(array(
                'tag' => 3,
                'class' => 'text-center',
                'content' => get_the_title()));
            $content_right = '<p>' . oxy_limit_excerpt(get_the_content(), 15) . '</p>';
            $content_right .= hb_ui_link(array(
                'link' => get_permalink(),
                'class' => 'more-link',
                'content' => hb_get_more_text($post->post_type)));
            $span_right = hb_ui_link(array(
                'link' => $post_link,
                'content' => $title_right));
            $span_right .= apply_filters('the_content', $content_right);

            $merge_spans = oxy_shortcode_layout(NULL, $span_left, 'span3');
            $merge_spans .= oxy_shortcode_layout(NULL, $span_right, 'span9');
            $result .= oxy_shortcode_layout(NULL, $merge_spans, 'span4');
        }
    endif;
    // reset post data
    wp_reset_postdata();
    return hb_get_custom_shortcode_section($atts, $result);
}
add_shortcode('hb_recent_videos', 'hb_shortcode_recent_video');

/**
 * @description overreid <b>blockquote</b> from parent template
 * @param array $atts
 * @param String $content
 * @return String
 */
function hb_shortcode_blockquote($atts, $content) {
    return hb_get_blockquote(array(
        'content' => $content,
        'params' => $atts));
}
add_shortcode('blockquote', 'hb_shortcode_blockquote');

/**
 * @description overreid <b>image</b> from parent template. Purpose: added new attribute alt to checkstyle
 * @param array $atts (size, rounded, polaroid, source, icon, link, alt)
 * @param String $content
 * @return string
 * @see oxy_shortcode_image($atts , $content = '')
 */
function hb_shortcode_image($atts , $content = ''){
    extract( shortcode_atts( array(
        'size'       => 'box-medium',
        'rounded'    => 'yes',
        'polaroid'   => 'no',
        'source'     => '',
        'icon'       => '',
        'link'       => '',
        'alt'       => 'holybunch_image'
    ), $atts ) );
    
    $iconclass= ($icon != '')?'<i class="'.$icon.'"></i>':'';
    $polaroidclss = ( $polaroid == 'yes')? 'img-polaroid':'';
    $extraclass = ($rounded == 'no')?' no-rounded':'';
    $tag = ($link != '')?'a':'span';
    $ref = ($tag == 'a')?' href="'.$link.'"':'';

    $output = '<div class="round-box'.$extraclass.' '.$size.'"> <'.$tag.' class="box-inner"'.$ref.'>';
    $output.= '<img class="img-circle '.$polaroidclss.'"  src="'.$source.'" alt="'.$alt.'" />'.$iconclass.'</'.$tag.'></div>';
    return $output;
}
add_shortcode( 'image' , 'hb_shortcode_image');

/**
 * @description shows recents blogs on main page
 * @global type $post
 * @param array $atts
 * @return String
 */
function hb_shortcode_recent_blog_posts($atts) {
    extract(shortcode_atts(array(
        'title' => '',
        'style' => '',
        'src_url' => ''
                    ), $atts));

    $args = array(
        'showposts' => 2, // Number of related posts that will be shown.  
        'orderby' => 'date',
    );
    $my_query = new wp_query($args);
    if ($my_query->have_posts()) {
        while ($my_query->have_posts()) {
            global $post;
            $my_query->the_post();
            setup_postdata($post);
            $author_avatar = get_avatar(get_the_author_meta('ID'), 300);
            $author = get_the_author();
            $date = get_the_time(get_option("date_format"));
            $post_link = hb_get_linkformat(get_post_format());


            $div_avatar_left = oxy_shortcode_layout(NULL, $author_avatar, 'round-box box-small');
            $title_autor_left = hb_ui_title(array(
                'tag' => 5,
                'class' => 'text-center',
                'content' => $author));
            $title_date_left = hb_ui_title(array(
                'tag' => 5,
                'class' => 'text-center light',
                'content' => $date));

            $link_right = hb_ui_link(array(
                'content' => get_the_title(),
                'link' => $post_link));

            $title_right = hb_ui_title(array(
                'tag' => 3,
                'content' => $link_right));

            $content_right = oxy_limit_excerpt(strip_tags(get_the_content()), 30);
            $content_right .= hb_ui_link(array(
                'content' => hb_get_more_text($post->post_type),
                'link' => $post_link,
                'class' => 'more-link'));

            $text_right = apply_filters('the_content', $content_right);

            $merge_spans = oxy_shortcode_layout(NULL, $div_avatar_left . $title_autor_left . $title_date_left, 'span3 post-info');
            $merge_spans .= oxy_shortcode_layout(NULL, $title_right . $text_right, 'span9');
            $output_loop .= oxy_shortcode_layout(NULL, oxy_shortcode_row(NULL, $merge_spans, NULL), 'span6');
        }
    }
    return hb_get_custom_shortcode_section($atts, oxy_shortcode_layout(NULL, $output_loop, 'unstyled row-fluid'));
}
add_shortcode('hb_blog_posts', 'hb_shortcode_recent_blog_posts');

/**
 * @description used on contact page, about us as contact form
 * @param array $atts
 * @param String $content
 * @return String
 */
function hb_shortcode_contact_form($atts, $content = null) {
    // setup options
    extract(shortcode_atts(array(
        'title' => 'Contact us',
        'id' => ''), $atts));
    $div_content = oxy_shortcode_layout(NULL, do_shortcode($content), 'contact-details');
    $div_left = oxy_shortcode_layout(NULL, do_shortcode($div_content), 'span5');
    $div_right = oxy_shortcode_layout(NULL, do_shortcode('[contact-form-7 id="' . $id . '" title="ContactForm"]'), 'span7');
    return hb_get_custom_shortcode_section($atts, $div_left . $div_right);
}
add_shortcode('hb_contact_form', 'hb_shortcode_contact_form');

/**
 * @description used on main pages in dutch and german in order to show latest conten
 * @global type $post
 * @param array $atts
 * @return String
 */
function hb_shortcode_recent_content($atts) {
    extract(shortcode_atts(array(
        'title' => '',
        'cat' => null,
        'style' => ''), $atts));

    $args = array(
        'post_type' => array('oxy_content'),
        'showposts' => 3, // Number of related posts that will be shown.  
        'orderby' => 'date'
    );
    $my_query = new wp_query($args);
    if ($my_query->have_posts()) {
        global $post;
        while ($my_query->have_posts()) {
            $my_query->the_post();
            setup_postdata($post);
            $title_link = hb_ui_link(array(
                'link' => hb_get_linkformat(get_post_format()),
                'content' => hb_ui_title(array(
                    'tag' => 3,
                    'class' => 'text-center',
                    'content' => get_the_title()
                ))
            ));
            $content = get_field('summary', $post->ID);
            $content .= hb_ui_link(array(
                'content' => hb_get_more_text($post->post_type),
                'link' => get_permalink(),
                'class' => 'more-link'));
            $text = apply_filters('the_content', $content);
            $output_loop .= oxy_shortcode_layout(NULL, do_shortcode($title_link . $text), 'span4');
        }
    }
    $output = oxy_shortcode_layout(NULL, $output_loop, 'unstyled row-fluid');
    wp_reset_postdata();
    return hb_get_custom_shortcode_section($atts, $output);
}
add_shortcode('hb_recent_content', 'hb_shortcode_recent_content');

/**
 * @description used for integration of google calendar into section
 * @param array $atts
 * @return String
 */
function hb_shortcode_add_element_into_wrapper($atts) {
    extract(shortcode_atts(array(
        'title' => '',
        'style' => '',
        'src_url' => ''
                    ), $atts));
    return hb_get_custom_shortcode_section($atts, hb_create_videowrapper_div($src_url, "span12"));
}
add_shortcode('hb_add_into_wrapper', 'hb_shortcode_add_element_into_wrapper');
/**
 * @description a helper method in order to get the recent content
 * @global type $post
 * @param array $atts
     * title - title for the span e.g. sermons/blog
     * icon - the label of the icon e.g. icon-music
 * @return String
 *          as span12 (no section)
 */   
 function hb_get_recent_content($title, $content_type, $count, $src){
        $args = array(
        'title' => '',
        'post_type' => array($content_type),
        'showposts' => $count, // Number of related posts that will be shown.  
        'orderby' => 'date',
    );
    $my_query = new wp_query($args);
    if ($my_query->have_posts()) {
        while ($my_query->have_posts()) {
            global $post;
            $my_query->the_post();
            setup_postdata($post);
            
            $date = get_the_time(get_option("date_format"));
            $image = hb_shortcode_image(array(
                'size' => 'size-full',
                'rounded' =>'no',
                'source' => $src,
                'link'=> hb_get_linkformat(get_post_format())
            ));
            $content = $image;
            $title_link = hb_ui_link(array(
                'link' => hb_get_linkformat(get_post_format()),
                'content' => hb_ui_title(array(
                    'content' => get_the_title()
                ))
            ));
            $content .='<h3 class="text-center">'.$title.'</h3>';
            $content .= '<p><b>'. $title_link .' </b>';
            if($content_type == "oxy_content"){
                $summary = oxy_limit_excerpt(strip_tags(get_field('summary', $post->ID)), 40);
                $content .= $summary.'</p>';
            }else {
                 $content .=oxy_limit_excerpt(strip_tags(get_the_content()), 40).'</p>';
            }
            
            $content.= hb_create_ui_btn(get_permalink(),'More');

            $text = apply_filters('the_content', $content);

            $output_loop .= oxy_shortcode_layout(NULL, do_shortcode( $text), 'span12');
        }
    }
    return $output_loop;
    }
/**
 * @description used on main pages in dutch and german in order to show latest video
 * @global type $post
 * @param array $atts
     * title - title for the span e.g. sermons/blog
     * icon - the label of the icon e.g. icon-music
 * @return String
 */      
    function hb_shortcode_newest_video($atts){
        extract(shortcode_atts(array(
        'title' => '',
         'src' => ''), $atts));
        return hb_get_recent_content($title, 'oxy_video', 1, $src);
    }
    add_shortcode('hb_newest_video', 'hb_shortcode_newest_video');
 /**
 * @description used on main pages in dutch and german in order to show latest sermon
 * @global type $post
 * @param array $atts
     * title - title for the span e.g. sermons/blog
     * icon - the label of the icon e.g. icon-music
 * @return String
 */     
    function hb_shortcode_newest_sermon($atts){
         extract(shortcode_atts(array(
        'title' => '',
         'src' => ''), $atts));
        return hb_get_recent_content($title, 'oxy_content', 1, $src);
    }
    add_shortcode('hb_newest_sermon', 'hb_shortcode_newest_sermon');
 /**
 * @description used on main pages in dutch and german in order to show latest blog posts
 * @global type $post
 * @param array $atts
     * title - title for the span e.g. sermons/blog
     * icon - the label of the icon e.g. icon-music
 * @return String
 */   
     function hb_shortcode_newest_blog_post($atts){
          extract(shortcode_atts(array(
        'title' => '',
         'src' => ''), $atts));
        return hb_get_recent_content($title, 'post', 1, $src);
    }
    add_shortcode('hb_newest_blog', 'hb_shortcode_newest_blog_post');
    /**
 * @description used on main pages in dutch and german in order to show latest portfolio item
     * e.g. music videoclip or for children corner
 * @global type $post
 * @param array $atts
     * title - title for the span e.g. sermons/blog
     * icon - the label of the icon e.g. icon-music
 * @return String
 */
     function hb_shortcode_newest_portfolio($atts){
          extract(shortcode_atts(array(
        'title' => '',
        'src' => ''), $atts));
        return hb_get_recent_content($title, 'oxy_portfolio_image' , 1, $src);
    }
    add_shortcode('hb_newest_portfolio', 'hb_shortcode_newest_portfolio');
    
 /**
 * @description used for example on main page for section with random video
 * @param array $atts
 * @return string
 */
function hb_shortcode_hero_section_with_video($atts) {
    extract(shortcode_atts(array(
        'title' => '',
        'summary' => '',
        'random_posts' => 'true',
        'post_video' => '',
        'taxonomy_slug' => '',
        'style'=>'',
        'class'=>''
                    ), $atts));

    $title = $title === null ? oxy_get_option('blog_title') : $title;

    //take random video post and show it
    if ($random_posts) {
        $args = array(
            'post_type' => 'oxy_video',
            'showposts' => 1,
            'orderby' => 'rand'
        );
        $my_query = new wp_query($args);
        $post_video = $my_query->post;
    }

    if (!empty($post_video)) {
        $title = $post_video->post_title;
        $summary = hb_limit_string($post_video->post_content, 50);
        $shortcode = get_field('video_shortcode', $post_video->ID);
    }
    $section_title = '<h1 style="border-bottom: 1px solid #9F8234;padding-top: 0.5em">'.__('Video', THEME_FRONT_TD).'</h1>';
    $content = '<div>'. $section_title . '<h2>'.$title.'</h2><p style="border-bottom: 1px solid #9F8234;padding: 1em 0">' . $summary . '</p></div>' ;
    $videoarchive_page = get_page_by_path("videoarchive");
    if($videoarchive_page !=null){
        $link = '<a class="btn btn-inverse btn-mini" href="videoarchive">'.get_the_title($videoarchive_page).'</a>';
        $content .= $link;
    }
    $text_right = oxy_shortcode_layout(NULL, do_shortcode($content), 'span6 ');
    $video_left = oxy_shortcode_layout(NULL, hb_create_videowrapper_div($shortcode, 'span6'), '');
    $row = oxy_shortcode_row(NULL, $video_left. $text_right, NULL);
    return  hb_get_custom_shortcode_section($atts, do_shortcode($row));
   
}
add_shortcode('hero_section_with_video', 'hb_shortcode_hero_section_with_video');
function hb_get_hot_topics($atts) {
   // setup options
    extract(shortcode_atts(array(
        'title' => '',
        'cat' => null,
        'style' => '',
        'class'=>''), $atts));

   $args = array(
        'post_type' => array('oxy_content'),
        'showposts' => 4, // Number of related posts that will be shown.  
        'orderby' => 'date'
    );
    $my_query = new wp_query($args);
    $item_num=1;
    $output='';
    if ($my_query->have_posts()) {
        global $post;
        $output .='<ul class="unstyled row-fluid"><div style="margin-top: 2em;">';
        while ($my_query->have_posts()) {
            $my_query->the_post();
            setup_postdata($post);
            if ($item_num > 2) {
                    $output.= '</ul><ul class="unstyled row-fluid"><div style="margin-top: 2em;">';
                    $item_num = 1;
                }
            $output .='<li class="span6">';
            $publishedOn = __('Published_on', THEME_FRONT_TD)." ".get_the_time(get_option("date_format"));
            $output .= '<h2><a href="'.hb_get_linkformat(get_post_format()).'">'.get_the_title().'</a></h2>';
            $output .='<p style="font-size: 0.8em; margin-top: -1.5em;">'.$publishedOn.'</p>';
            $content = get_field('summary', $post->ID);
            
            $text = apply_filters('the_content', $content);
            $output.='<p>' . apply_filters('the_content', $text) . '</p>';
            $term_list = wp_get_post_terms($post->ID, 'teaching_topics');
            $terms = "";
            foreach ( $term_list as $term ) {
                $term_link = get_term_link($term);
                if(empty($terms)){
                $terms.= '<a href="'.esc_url($term_link).'">'.$term->name .'</a> ';
                } else {
                 $terms.= '| <a href="'.esc_url($term_link).'">'.$term->name .'</a> ';
               }
            }
            $output.='<p class="readmore">'.$terms.'</p></li>';
            $item_num++;
           }
            $output .= '</ul>';

    }
    wp_reset_postdata();
    return  oxy_shortcode_section($atts, $output);
}
add_shortcode('hb_hot_topics', 'hb_get_hot_topics');

function show_podcast_section($atts) {  
    global $post;
    
    $atts['class'] = 'updates';
    extract(shortcode_atts(array(
        'topic' => 'default topic',
        'mp3_title' => 'default title',
        'mp3_path' => ''), $atts));
    
    $podcast_image = '<img class="alignnone size-full wp-image-5451" src="' . CUSTOM_IMAGES_DIR . 'podcast-icon.png' . '" alt="podcast-icon" width="68" height="72">';
    $mp3 = '<p style="padding-top: 1em"><span id="audioSwitch" style="cursor: pointer" class="updates_btn size=btn-large">'
            . __('listen', THEME_FRONT_TD) .  '</span></p>';
    
    $showAudio .= "<div id='showAudio' class='hidden top075em'>" . 
            hb_get_jw_player_for_video_church($post, "rtmp://46.4.85.100:1935/vod/mp3:" . $mp3_path) . "</div>";
        
    $columns =  '<!-- mp3_path in form mp3/path_to_mp3/name.mp3 -->';
    $columns .= oxy_shortcode_layout( NULL, $podcast_image, 'span2');
    $columns .= oxy_shortcode_layout( NULL, '<strong>' . $topic . '</strong> <br> ' . $mp3_title . $showAudio, 'span8');
    $columns .= oxy_shortcode_layout( NULL, $mp3, 'span2');
    
    return oxy_shortcode_section($atts , $columns);
}
add_shortcode('show_podcast', 'show_podcast_section');

function hb_navigation_panel_topics($atts) {
    // setup options
    extract(shortcode_atts(array(
        'title' => '',
        'style' => '',
        'class'=>'', 
        'parent'=>''), $atts));
    
    
    $taxonomy_name = "teaching_topics";
    $post_type = "oxy_content";
    if(empty($parent)) $parent = 0;
    if(empty($style)) $style = "font-size:1.5em;";
    if(empty($class)) $class = "chapter";
    if(empty($title)) $title = "Темы";
    $args = array(
        'taxonomy' => $taxonomy_name,
        'parent' => $parent
    );
    $top_level_topics = get_categories($args);
    $content = "[section title=\"" . $title . "\" ]";
    $counter = 0;
    $row_counter = 0;
    foreach ($top_level_topics as $top_level_topic) {
        $counter = $counter + 1;
        if ($row_counter == 0)
            $content = $content . "[row]";
        $row_counter = $row_counter + 1;
        $content = $content . "<div class=\"" . $class . "\">[span6]<p class=\"tag\">" . $counter . "</p>";
        $content = $content . "[accordions]";
        $content = $content . "[accordion title=\"" . $top_level_topic->name . "\" style=" . $style . "]";
        $args = array(
            'taxonomy' => $taxonomy_name,
            'parent' => $top_level_topic->term_id
        );
        $topics = get_categories($args);
        foreach ($topics as $taxonomy_topic) {
            $posts_in_category = get_posts(array(
                'showposts' => -1,
                'post_type' => $post_type,
                'tax_query' => array(array(
                        'taxonomy' => $taxonomy_name,
                        'field' => 'slug',
                        'terms' => $taxonomy_topic->slug)
                )
            ));
            $count = count($posts_in_category);
            //link to each topic  
            $link = get_term_link($taxonomy_topic->slug, $taxonomy_name);
            $content = $content . "<table class=\"table\"><tbody><tr><td>";
            $content = $content . "<a href=\"" . $link . "\">" . $taxonomy_topic->name . "</a></td>";
            $content = $content . "<td>" . $count . "</td></tr></tbody></table>";
        }
        $content = $content . "[/accordion]";
        $content = $content . "[/accordions] [/span6]</div>";
        if ($row_counter === 2) {
            $content = $content . "[/row]";
            $row_counter = 0;
        }
    }
    $content = $content . "[/section]";
    return do_shortcode($content);
}

add_shortcode('hb_navigation_panel_topics', 'hb_navigation_panel_topics');
