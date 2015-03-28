
</div>
</div>
<footer id="footer" role="contentinfo" class="<?php echo oxy_get_option('skin'); ?>">
    <div class="wrapper wrapper-transparent">
        <div class="container-fluid">
            <div class="row-fluid">
                <?php
                $columns = oxy_get_option('footer_columns');
                $span = 'span' . (12 / $columns);
                ?>
                <div class="<?php echo $span; ?> text-left"><?php dynamic_sidebar("footer-left"); ?></div>
                <?php if ($columns == 3) { ?>
                    <div class="<?php echo $span; ?>"><?php dynamic_sidebar("footer-middle"); ?></div><?php
                } else if ($columns == 4) {
                    ?>
                    <div class="<?php echo $span; ?>"><?php dynamic_sidebar("footer-middle-left"); ?></div>
                    <div class="<?php echo $span; ?>"><?php dynamic_sidebar("footer-middle-right"); ?></div><?php }
                ?>
                <div class="<?php echo $span; ?> text-right"><?php dynamic_sidebar("footer-right"); ?></div>
            </div>
        </div>
    </div>
</footer>


<script type="text/javascript">
    //<![CDATA[
    var _gaq = _gaq || [];
    _gaq.push(['_setAccount', '<?php echo oxy_get_option('google_anal') ?>']);
    _gaq.push(['_trackPageview']);

    (function() {
        var ga = document.createElement('script');
        ga.type = 'text/javascript';
        ga.async = true;
        ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
        var s = document.getElementsByTagName('script')[0];
        s.parentNode.insertBefore(ga, s);
    })();
    //]]>
</script>
<div id="fb-root"></div>
<!-- Yandex.Metrika counter -->
<script type="text/javascript">
    (function(d, w, c) {
        (w[c] = w[c] || []).push(function() {
            try {
                w.yaCounter27123095 = new Ya.Metrika({id: 27123095,
                    webvisor: true,
                    clickmap: true,
                    trackLinks: true,
                    accurateTrackBounce: true});
            } catch (e) {
            }
        });

        var n = d.getElementsByTagName("script")[0],
                s = d.createElement("script"),
                f = function() {
            n.parentNode.insertBefore(s, n);
        };
        s.type = "text/javascript";
        s.async = true;
        s.src = (d.location.protocol == "https:" ? "https:" : "http:") + "//mc.yandex.ru/metrika/watch.js";

        if (w.opera == "[object Opera]") {
            d.addEventListener("DOMContentLoaded", f, false);
        } else {
            f();
        }
    })(document, window, "yandex_metrika_callbacks");
</script>
<noscript><div><img src="//mc.yandex.ru/watch/27123095" style="position:absolute; left:-9999px;" alt="" /></div></noscript>
<!-- /Yandex.Metrika counter -->
<?php wp_footer(); ?>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
<script src= "<?php echo home_url() ?>/wp-content/themes/smartbox-theme-custom/inc/js/custom_main.js" type="text/javascript"></script>
</body>
</html>