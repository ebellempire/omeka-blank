<?php
$pageTitle = __('403 Page Forbidden');
echo head(array('title' => $pageTitle));
?>
<?php echo flash(); ?>

<!-- Title -->
<div id="error-title">
    <h1><?php echo $pageTitle; ?></h1>
</div>

<div id="primary-content">
    <p><?php echo __('You do not have permission to access this page.'); ?></p>
</div>
<?php echo foot(); ?>