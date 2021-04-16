<!-- Modified to allow for removal of search params, etc -->
<!-- @todo: add x buttons to each filter, add corresponding JS -->
<div id="<?php echo $options['id']; ?>">
    <ul>
        <li><?php echo __('Query:');?> <?php echo '<span class="search-filter query-filter" data-record-type="?query='.urlencode($query).'">'.html_escape($query).'</span>'; ?></li>
        <li><?php echo __('Record types:');?>
            <?php foreach ($record_types as $record_type): ?>
            <?php
            $record_type_label = html_escape($record_type);
            $record_type_label = str_replace('Item', ob_item_label(), $record_type); //tweak to use custom label...
            $record_type_prep = str_replace(" ", "", str_replace('Simple Page', 'SimplePagesPage', $record_type)); // prep for data-record-type
            $record_type_data_attr = '&record_types[]='.$record_type_prep; // used to x out of search record filter
            ?>
            <span class="search-filter record-type-filter" data-record-type="<?php echo $record_type_data_attr;?>"><?php echo $record_type_label; ?></span>
            <?php endforeach; ?>
        </li>
        <li><?php echo __('Query type:');?> <?php echo '<span class="search-filter query-type-filter" data-record-type="&query_type='.urlencode(strtolower($query_type)).'">'.html_escape($query_type).'</span>'; ?></li>
    </ul>
</div>