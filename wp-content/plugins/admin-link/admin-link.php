<?php
/** 
 * Plugins name: Custom Plugin 
 * author: CybSam
 * description: go to knowledge base post
 * */

 function my_admin_links( $admin_bar ){
    $admin_bar->add_menu(
        array( 
            'id'=>'my-admin-front-page', 
            'title'=>'Article', 
            'href'=>'edit.php?post_type=epkb_post_type_1' 
        ));
 }

 add_action('admin_bar_menu','my_admin_links',100);






?>