<?php
$title = metadata('item', 'display_title');
echo head(array('title' => $title, 'bodyclass' => 'items show'));
?>

<!-- Title -->
<h1><?php echo metadata($item, 'rich_title', array('no_escape' => true)); ?></h1>

<div class="col-container one-two">

    <div class="col">
        <!-- Select Metadata -->
        <?php echo ob_select_metadata($item, array('Date','Format','Type','Source'), array('Physical Dimensions'));?>

        <!-- Call to Action -->
        <?php echo ob_cta_block();?>
    </div>

    <div class="col">
        <!-- Description -->
        <?php echo ob_dublin($item, 'Description');?>

        <!-- Files -->
        <?php echo ob_item_files($item, true);?>

        <!-- URL -->
        <?php echo ob_item_url($item);?>

        <!-- Plugin -->
        <?php fire_plugin_hook('public_items_show', array('view' => $this, 'item' => $item)); ?>
    </div>
</div>
<!-- All Metadata -->
<?php echo all_element_texts($item, array('show_element_set_headings'=>false));?>

<!-- Item Collection -->
<?php echo ob_item_collection($item);?>

<!-- Tags -->
<?php echo ob_tags($item);?>

<!-- Citation -->
<?php echo ob_citation($item);?>

<!-- Output formats -->
<?php echo ob_output_formats($item);?>

<!-- Pagination -->
<?php echo ob_item_pagination($item);?>

<?php echo foot(); ?>