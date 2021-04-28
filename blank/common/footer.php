    </article>

    <footer role="contentinfo">

        <div id="footer-text">
            <!-- Contact -->
            <?php echo ob_contact_info();?>

            <!-- Social -->
            <?php echo ob_social_links();?>

            <!-- Copyright -->
            <?php
            if ((get_theme_option('footer_copyright') == 1) && $copyright = option('copyright')) {
                echo '<p class="site-info copyright">&copy; '.$copyright.'</p>';
            } ?>

            <!-- Omeka Info -->
            <?php echo ob_site_info();?>
        </div>

        <?php fire_plugin_hook('public_footer', array('view' => $this)); ?>

    </footer>

    <!-- req. markup for side menu -->
    <?php echo ob_mmenu_markup();?>

    </div><!-- end wrap -->
    </body>

    </html>