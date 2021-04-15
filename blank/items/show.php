<?php
$title = metadata('item', 'display_title');
echo head(array('title' => $title, 'bodyclass' => 'items show'));
?>

<!-- Title -->
<div id="item-title">
    <h1><?php echo metadata($item, 'rich_title', array('no_escape' => true)); ?></h1>
</div>

<!-- Primary Content -->
<div id="primary-content">
    <!-- Select Metadata -->
    <?php echo ob_select_metadata($item, array('Date','Format','Type','Source'), array('Physical Dimensions'));?>

    <!-- Item Collection -->
    <?php echo ob_item_collection($item);?>

    <!-- Tags -->
    <?php echo ob_tags($item);?>

    <!-- Call to Action -->
    <?php echo ob_cta_block();?>

    <!-- Description -->
    <?php echo ob_item_description($item);?>

    <!-- Files -->
    <?php echo ob_item_files($item, true);?>

    <!-- Link to URL if present -->
    <?php echo ob_item_url($item);?>

    <!-- Plugin -->
    <?php fire_plugin_hook('public_items_show', array('view' => $this, 'item' => $item)); ?>
</div>

<!-- Citation -->
<div id="citation-information">
    <?php echo ob_citation($item);?>
</div>

<!-- All Metadata -->
<div id="full-metadata-record">
    <?php echo all_element_texts($item, array('show_element_set_headings'=>false));?>
</div>

<!-- Output formats -->
<?php echo ob_output_formats($item);?>

<!-- Pagination -->
<?php echo ob_item_pagination($item);?>

<?php echo foot(); ?>