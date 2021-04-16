<?php
$pageTitle = __('%s Search', ob_item_label('plural'));
echo head(array('title' => $pageTitle,'bodyclass' => 'items advanced-search'));
?>

<div id="search-title">
    <h1><?php echo $pageTitle; ?></h1>
</div>

<?php echo ob_secondary_nav(); ?>

<div id="search-form-container">
    <?php echo $this->partial('items/search-form.php', array('formAttributes' =>array('id' => 'advanced-search-form'))); ?>
</div>

<?php echo foot(); ?>