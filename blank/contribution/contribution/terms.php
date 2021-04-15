<?php
$head = array('title' => __('Contribution Terms of Service'));
echo head($head);
?>

<!-- Title -->
<div id="contribution-title">
    <h1><?php echo $head['title']; ?></h1>
</div>

<div id="primary-content">
    <?php echo get_option('contribution_consent_text'); ?>
</div>
<?php echo foot(); ?>