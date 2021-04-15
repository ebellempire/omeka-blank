<?php echo head(array('bodyid' => 'home')); ?>

<div class="col-container two">
    <div class="col">
        <!--  Featured Item -->
        <?php echo ob_featured_item_block(true);?>

        <!-- Call to Action -->
        <?php echo ob_cta_block();?>
    </div>
    <div class="col">
        <!-- Homepage Text Block 1 -->
        <?php echo ob_homepage_text_block_1(get_theme_option('homepage_block_1_heading'), get_theme_option('homepage_block_2_img'));?>

        <!-- Homepage Text Block 2 -->
        <?php echo ob_homepage_text_block_2(get_theme_option('homepage_block_2_heading'), get_theme_option('homepage_block_2_img'));?>
    </div>

    <!-- Plugin Hooks -->
    <?php fire_plugin_hook('public_home', array('view' => $this)); ?>

    <?php echo foot(); ?>