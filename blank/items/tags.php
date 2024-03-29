<?php
$pageTitle = __('Browse %s Tags', ob_item_label());
echo head(array('title' => $pageTitle, 'bodyclass' => 'items tags'));
?>

<div id="tags-title">
    <h1><?php echo $pageTitle; ?></h1>
</div>

<?php echo ob_secondary_nav(); ?>

<div id="primary-content">
    <?php
    echo tag_cloud($tags, 'items/browse');
    ?>
</div>

<?php echo foot(); ?>