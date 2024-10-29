<?php
// get options
function GetPreloaderOptions($optname){
    
    $MyPreloaderSettings = unserialize(get_option('advert_preloader_all_options'));
    if($MyPreloaderSettings[$optname]){
        return stripslashes($MyPreloaderSettings[$optname]);
    } else {
        return false;
    }
    
}
