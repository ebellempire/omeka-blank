<?php
$title = metadata('item', 'display_title');
echo head(array('title' => $title, 'bodyclass' => 'items show','item'=>$item));
?>

<!-- Title -->
<div id="item-title">
    <h1><?php echo metadata($item, 'rich_title', array('no_escape' => true)); ?></h1>
    <?php echo ob_byline($item);?>
</div>

<!-- Primary Content -->
<div id="primary-content">
    <!-- Description -->
    <div class="main-text">
        <?php echo ob_item_description($item);?>
    </div>
    <!-- Files -->
    <?php echo ob_item_files($item, true);?>

    <!-- Link to URL if present -->
    <?php echo ob_item_url($item);?>

    <!-- Select Metadata -->
    <?php echo ob_select_metadata($item, array('Date','Format','Type','Source'), array('Physical Dimensions'));?>

    <!-- Item Collection -->
    <?php echo ob_item_collection($item);?>

    <!-- Call to Action -->
    <?php echo ob_cta_block();?>

    <!-- Plugin -->
    <?php fire_plugin_hook('public_items_show', array('view' => $this, 'item' => $item)); ?>
</div>

<!-- Citation -->
<?php echo ob_citation($item);?>

<!-- Tags -->
<?php echo ob_tags($item);?>

<!-- All Metadata -->
<?php echo ob_all_metadata($item, get_theme_option('items_full_record'));?>

<!-- Pagination -->
<?php echo ob_item_pagination($item);?>

<!-- req. markup for image viewer -->
<?php echo ob_photoswipe_markup($item);?>

<?php echo foot(); ?>