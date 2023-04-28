<?php echo head(array('bodyid' => 'home')); ?>

<div>
    <!--  Gallery -->
    <?php echo ob_homepage_gallery_markup();?>
    <!--  Featured Item -->
    <?php echo ob_featured_item_block();?>
    <!--  Featured Collection -->
    <?php echo ob_featured_collection_block();?>
    <!-- Homepage Text Block 1 -->
    <?php echo ob_homepage_text_block_1();?>
    <!-- Homepage Text Block 2 -->
    <?php echo ob_homepage_text_block_2();?>
    <!-- Recent Items -->
    <?php echo ob_recent_items_block();?>
    <!-- Call to Action -->
    <?php echo ob_cta_block();?>
    <!-- Plugin Hooks -->
    <?php fire_plugin_hook('public_home', array('view' => $this)); ?>
</div>

<?php echo foot(); ?>