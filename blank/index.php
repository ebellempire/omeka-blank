<?php echo head(array('bodyid' => 'home')); ?>

<!-- Homepage Text Block 1 -->
<?php echo ob_homepage_text_block_1(get_theme_option('homepage_block_1_heading'), get_theme_option('homepage_block_2_img'));?>

<!-- Homepage Text Block 2 -->
<?php echo ob_homepage_text_block_2(get_theme_option('homepage_block_2_heading'), get_theme_option('homepage_block_2_img'));?>

<!--  Featured Item -->
<?php echo ob_featured_item_block(true);?>

<!-- Call to Action -->
<?php echo ob_cta_block();?>

<!-- Plugin Hooks -->
<?php fire_plugin_hook('public_home', array('view' => $this)); ?>

<?php echo foot(); ?>