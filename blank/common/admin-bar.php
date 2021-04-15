<nav id="admin-bar">

<?php if ($user = current_user()) {
    $links = array(
        array(
            'label' => __('Account'),
            'uri' => admin_url('/users/edit/'.$user->id)
        ),
        array(
            'label' => __('Omeka Admin'),
            'uri' => admin_url('/')
        ),
        array(
            'label' => __('Log Out'),
            'uri' => url('/users/logout')
        )
    );

    $url=current_url();
    $test = preg_match_all("/.*?(\d+)$/", $url, $matches);
    if ($test==1) {
        $edit=array(
            'label'=>__('Edit Record'),
            'uri'=>admin_url($matches[0][0]),
            'class'=>'highlight'
        );
        array_unshift($links, $edit);
    }
} else {
    $links = array();
}

echo nav($links, 'public_navigation_admin_bar');
?>
</nav>
