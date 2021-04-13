<?php echo head(array('bodyid' => 'home')); ?>

<!-- Homepage Text Block 1: see custom.php -->
<?php echo homepage_text_block_1(get_theme_option('homepage_block_1_heading'), get_theme_option('homepage_block_2_img'));?>

<!-- Homepage Text Block 2: see custom.php -->
<?php echo homepage_text_block_2(get_theme_option('homepage_block_2_heading'), get_theme_option('homepage_block_2_img'));?>

<!--  Featured Item: see custom.php -->
<?php echo featured_item_block(true);?>

<!-- Donate: see custom.php -->
<?php echo donation_block();?>

<!-- Plugin Hooks -->
<?php fire_plugin_hook('public_home', array('view' => $this)); ?>

<?php echo foot(); ?>