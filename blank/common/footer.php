    </article>

    <footer role="contentinfo">

        <div id="footer-text">
            <!-- Address -->
            <?php
            $site_orgname = trim(get_theme_option("site_orgname"));
            $site_address = trim(get_theme_option("site_address"));
            $site_phone = trim(get_theme_option("site_phone"));
            $site_email = trim(get_theme_option("site_email"));
            echo ($site_orgname || $site_address) ? "<p class='site-info address'>".implode(' | ', array($site_orgname,$site_address))."</p>" : null;
            echo ($site_phone || $site_email) ? "<p class='site-info contact'>".implode(' | ', array($site_phone,$site_email))."</p>" : null;
            ?>

            <!-- Social -->
            <?php echo ob_social_links();?>

            <!-- Copyright -->
            <?php
            if ((get_theme_option('footer_copyright') == 1) && $copyright = option('copyright')) {
                echo '<p class="site-info copyright">&copy; '.$copyright.'</p>';
            } ?>

            <!-- Omeka info -->
            <?php echo ob_site_info();?>
        </div>

        <?php fire_plugin_hook('public_footer', array('view' => $this)); ?>

    </footer>

    <!-- req. markup for side menu -->
    <?php echo ob_mmenu_markup();?>

    </div><!-- end wrap -->
    </body>

    </html>