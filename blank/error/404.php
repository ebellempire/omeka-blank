<?php
$pageTitle = __('404 Page Not Found');
echo head(array('title' => $pageTitle));
?>
<!-- Title -->
<div id="error-title">
    <h1><?php echo $pageTitle; ?></h1>
</div>

<div id="primary-content">
    <p><?php echo __('%s is not a valid URL.', html_escape($badUri)); ?></p>
</div>
<?php echo foot(); ?>