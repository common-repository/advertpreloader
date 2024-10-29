<div class="container-fluid">
    
    <div class="row-fluid">
        <div class="col-lg-10">
            <form name="preloader_options" id="preloader_options" action="#" method="post">
                <div class="panel panel-success">
                    <div class="panel-heading">
                        <h3 class="panel-title"><?php _e('Content Preloader','AdvertLoader');?></h3>
                    </div>
                    <div class="panel-body">
                        <div class="row">

                            <div class="col-sm-12"><?php _e('Timer (in seconds):','AdvertLoader');?></div>
                            <div class="col-sm-12">
                                After 
                                <input type="number" value="<?php echo GetPreloaderOptions("preloader_timer");?>" name="preloader_timer" min="1">
                                <?php _e('seconds:','AdvertLoader');?> 
                                <select name="preloader_show_automatically">
                                    <option <?php if(GetPreloaderOptions("preloader_show_automatically")==="yes"){echo "selected";}?> value="yes"><?php _e('Show content automatically','AdvertLoader');?></option>
                                    <option <?php if(GetPreloaderOptions("preloader_show_automatically")!=="yes"){echo "selected";}?> value="no"><?php _e('Show content on button click','AdvertLoader');?></option>
                                </select><br>
                                <small class="text-info"><?php _e('"Show content" button will appear after specified number of seconds','AdvertLoader');?></small>
                                <hr>
                            </div>
                            
                            <div class="col-sm-12"><?php _e('Where do you want to show preloader?','AdvertLoader');?></div>
                            <div class="col-sm-12">
                                <select name="preloader_show_on_page_type">
                                    <option <?php if(GetPreloaderOptions("preloader_show_on_page_type")==="only_full_content"){echo "selected";}?> value="only_full_content"><?php _e('Only on full content page (recommended)','AdvertLoader');?></option>
                                    <option <?php if(GetPreloaderOptions("preloader_show_on_page_type")==="full_content_and_homepage"){echo "selected";}?> value="full_content_and_homepage"><?php _e('On full content page and homepage','AdvertLoader');?></option>
                                    <option <?php if(GetPreloaderOptions("preloader_show_on_page_type")==="disable_advert_preloader"){echo "selected";}?> value="disable_advert_preloader"><?php _e('Disable AdvertPreloader','AdvertLoader');?></option>
                                </select><hr>
                            </div>
                    

                            <div class="col-sm-12"><?php _e('Loading title:','AdvertLoader');?></div>
                            <div class="col-sm-12">
                                <input type="text" value="<?php echo GetPreloaderOptions("preloader_title");?>" class="form-control" name="preloader_title">
                            </div>
                            <div class="col-sm-12"><?php _e('Loading completed title:','AdvertLoader');?></div>
                            <div class="col-sm-12">
                                <input type="text" value="<?php echo GetPreloaderOptions("preloader_title_completed");?>" class="form-control" name="preloader_title_completed">
                                <hr>
                            </div>

                            <div class="col-sm-12"><?php _e('Show content button text:','AdvertLoader');?></div>
                            <div class="col-sm-12">
                                <input type="text" value="<?php echo GetPreloaderOptions("preloader_button");?>" class="form-control" name="preloader_button">
                                <hr>
                            </div>

                            <div class="col-sm-12"><?php _e('Preloader content','AdvertLoader');?> (<small><?php _e('adsense code or some HTML','AdvertLoader');?></small>):</div>
                            <div class="col-sm-12">
                                <textarea class="form-control" name="preloader_banner_code" rows="3"><?php echo GetPreloaderOptions("preloader_banner_code");?></textarea>
                                <hr>
                            </div>

                            <div class="col-sm-12"><?php _e('Progress bar image:','AdvertLoader');?></div>
                            <div class="col-sm-12">
                                <select name="preloader_progress_bar_image">
                                    <?php for($i=1;$i<=10;$i++):?>
                                    <option <?php if(GetPreloaderOptions("preloader_progress_bar_image")===$i.'.GIF'){echo "selected";}?> value="<?=$i;?>.GIF"><?=$i;?>.GIF</option>
                                    <?php endfor;?>
                                </select>
                                <hr>
                            </div>

                            <div class="col-sm-12"><?php _e('Show preloader on:','AdvertLoader');?></div>
                            <div class="col-sm-12">
                                <select name="preloader_show_on">
                                    <option <?php if(GetPreloaderOptions("preloader_show_on")==="all_posts"){echo "selected";}?> value="all_posts"><?php _e('All posts','AdvertLoader');?></option>
                                    <option <?php if(GetPreloaderOptions("preloader_show_on")==="all_pages"){echo "selected";}?> value="all_pages"><?php _e('All pages','AdvertLoader');?></option>
                                    <option <?php if(GetPreloaderOptions("preloader_show_on")==="all_posts_and_pages"){echo "selected";}?> value="all_posts_and_pages"><?php _e('All posts & pages','AdvertLoader');?></option>
                                    <option <?php if(GetPreloaderOptions("preloader_show_on")==="selected_posts"){echo "selected";}?> value="selected_posts"><?php _e('Only on selected posts and pages','AdvertLoader');?></option>
                                </select><br>
                                <small class="text-info"><?php _e('* if you choose "only on selected posts and pages" you have to check "Show Preloader" checkbox under Edit post form','AdvertLoader');?></small> 
                                <hr>
                            </div>
                            
                            
                            
                        </div>

                    </div>
                </div>
                <div class="well well-sm">
                    <div class="row">
                        <div class="col-sm-12"><input type="submit" class="btn btn-success btn-block" name="submit_settings" value="Save Changes!"></div>
                    </div>
                </div>
            </form>
        </div>
        <!--
        <div class="col-lg-2">
            <div class="row-fluid">
                <?php
                $loader_path = plugin_dir_url( __FILE__ ).'/loaders/';
                $loader_path = str_replace("templates/","",$loader_path);
                ?>
                <?php for($i=1;$i<=10;$i++):?>
                <div class="col-lg-12">
                    <div class="panel panel-default">
                        <div class="panel-heading"><?php echo $i.'.GIF';?></div>
                        <div class="panel-body">
                           <img src="<?php echo $loader_path.$i.'.GIF';?>" class="img-polaroid" width="50">
                        </div>
                    </div>
                </div>
                <?php endfor;?>
            </div>
        </div>
        -->
    </div>
    
    

    
    
</div>

