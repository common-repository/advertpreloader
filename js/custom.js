jQuery(document).ready(function() {
    var button_delay = jQuery("#AdvertPreloaderButtonDelay").val();
    button_delay = button_delay * 1000;
    
    
    var auto_show = jQuery("#AdvertPreloaderAutoShow").val();
    
    if(auto_show === 'yes'){
        
        setTimeout(function(){
            jQuery(".AdvertPreloaderHide").show();
            jQuery(".AdvertPreloaderMain").hide();
        }, button_delay);
        
    } else {
        
        setTimeout(function(){
            jQuery( ".AdvertPreloaderShowButtonStyle" ).fadeIn( 1000 );
            jQuery(".AdvertPreloaderLoaderImage").fadeOut(500);
            jQuery(".AdvertPreloaderTitle").fadeOut(500);
            jQuery( ".AdvertPreloaderTitleSuccess" ).fadeIn( 150 );
        }, button_delay);
        
        
    }
});

jQuery(document).on("click", ".AdvertPreloaderShowButtonStyle", function(event) {
    jQuery(".AdvertPreloaderHide").show();
    jQuery(".AdvertPreloaderMain").hide();
});
