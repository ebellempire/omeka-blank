    </article>

    <footer role="contentinfo">

        <?php if (get_theme_option('footer_nav') == 1):?>
        <!-- Footer Nav -->
        <div id="nav-container" class="bottom">
            <nav class="center" id="bottom-nav" role="navigation">
                <?php echo public_nav_main(); ?>
            </nav>
            <div class="search-container center" role="search">
                <?php echo search_form(); ?>
            </div>
        </div>
        <?php endif;?>

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

            <!-- Omeka -->
            <?php
            if ((get_theme_option('omeka') == 1)) {
                echo '<p class="site-info cms">'.__('Proudly powered by <a href="http://omeka.org">Omeka</a>.').'</p>';
            }?>
        </div>

        <?php fire_plugin_hook('public_footer', array('view' => $this)); ?>

    </footer>

    </div><!-- end wrap -->
    </body>

    </html>