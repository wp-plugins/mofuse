<?php
/*
Plugin Name: MoFuse Plugin
Plugin URI: http://www.mofuse.com/wordpress
Description: MoFuse's Wordpress plugin. If you need a MoFuse account, visit <a href="http://www.mofuse.com">www.mofuse.com</a> and create a free account and you can have a mobile version of your Wordpress blog up and running in just seconds.
Author: MoFuse
Version: 0.8 
Author URI: http://www.mofuse.com
*/

// Get options from Wordpress Database
	$mf_sid=get_option("mf_sid");
	$mf_redirect=get_option("mf_redirect");
	$mf_sms=get_option("mf_sms");
	$mf_cname=get_option("mf_cname");
	
	$mf_subd=str_replace("http://", "", get_settings('home'));
	$currenturl=$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI'];
	if ($mf_subd[strlen($mf_subd)-1]=='/') { } else {
		$mf_subd=$mf_subd.'/';
	}

// Mobile detect & redirect
	$mf_ua=$_SERVER['HTTP_USER_AGENT'];
	$mf_ismobile = stripos($ac, 'application/vnd.wap.xhtml+xml') !== false
    	    || $op != ''
	        || stripos($mf_ua, 'sony') !== false 
    	    || stripos($mf_ua, 'symbian') !== false 
        	|| stripos($mf_ua, 'nokia') !== false 
	        || stripos($mf_ua, 'samsung') !== false 
    	    || stripos($mf_ua, 'mobile') !== false
        	|| stripos($mf_ua, 'windows ce') !== false
	        || stripos($mf_ua, 'epoc') !== false
    	    || stripos($mf_ua, 'opera mini') !== false
        	|| stripos($mf_ua, 'nitro') !== false
	        || stripos($mf_ua, 'j2me') !== false
    	    || stripos($mf_ua, 'midp-') !== false
        	|| stripos($mf_ua, 'cldc-') !== false
	        || stripos($mf_ua, 'netfront') !== false
    	    || stripos($mf_ua, 'mot') !== false
        	|| stripos($mf_ua, 'up.browser') !== false
	        || stripos($mf_ua, 'up.link') !== false
    	    || stripos($mf_ua, 'audiovox') !== false
        	|| stripos($mf_ua, 'blackberry') !== false
	        || stripos($mf_ua, 'ericsson,') !== false
    	    || stripos($mf_ua, 'panasonic') !== false
        	|| stripos($mf_ua, 'philips') !== false
	        || stripos($mf_ua, 'sanyo') !== false
    	    || stripos($mf_ua, 'sharp') !== false
        	|| stripos($mf_ua, 'sie-') !== false
	        || stripos($mf_ua, 'portalmmm') !== false
    	    || stripos($mf_ua, 'blazer') !== false
        	|| stripos($mf_ua, 'avantgo') !== false
	        || stripos($mf_ua, 'danger') !== false
    	    || stripos($mf_ua, 'palm') !== false
        	|| stripos($mf_ua, 'series60') !== false
	        || stripos($mf_ua, 'palmsource') !== false
    	    || stripos($mf_ua, 'pocketpc') !== false
        	|| stripos($mf_ua, 'smartphone') !== false
	        || stripos($mf_ua, 'rover') !== false
    	    || stripos($mf_ua, 'ipaq') !== false
        	|| stripos($mf_ua, 'au-mic,') !== false
	        || stripos($mf_ua, 'alcatel') !== false
    	    || stripos($mf_ua, 'ericy') !== false
        	|| stripos($mf_ua, 'up.link') !== false
	        || stripos($mf_ua, 'vodafone/') !== false
    	    || stripos($mf_ua, 'wap1.') !== false
        	|| stripos($mf_ua, 'wap2.') !== false
			|| stripos($mf_ua, 'teleca') !== false
			|| stripos($mf_ua, 'playstation') !== false
			|| stripos($mf_ua, 'nitro') !== false
			|| stripos($mf_ua, 'nintendo wii') !== false
			|| stripos($mf_ua, 'iphone') !== false
			|| stripos($mf_ua, 'ipod') !== false;
			
	
	function mf_hpd($currenturl, $mf_subd) {
		if($currenturl=="" || $currenturl=="/" || $currenturl==$mf_subd) {
			return true;
		} else {
			return false; 
		}
	}

	if ($mf_redirect=='y' && $_GET['nomobile']!=1) {
		if($mf_ismobile){ 
			if (mf_hpd($currenturl, $mf_subd)) {
				//header("Location: http://www.google.com");
				add_action('wp_head', "sendtoMobile",3);
			}
		}
	}

	function sendtoMobile() {
		global $mf_sid;
		global $mf_cname;
		if ($mf_cname) {
			$mf_cname=str_replace("http://", "", $mf_cname);
			header("Location: http://$mf_cname");
		} else {
			header("Location: http://$mf_sid.mofuse.mobi");
		}
	}

// End of Mobile detect & redirection

// Plugin work
	add_action('admin_menu', 'mf_add_pages');

	function mf_add_pages() {
		add_options_page('MoFuse', 'MoFuse', 10, __FILE__, 'mf_toplevel_page');
	}

	function mf_toplevel_page() {
		echo '<img src="http://mofuse.com/images/logo_wp_plugin.png" style="margin:20px 0px 0px 20px;" />';
	
	    if($_POST['mf_hidden']==1) {
			$mf_sid=$_POST['mf_sid'];
			$mf_redirect=$_POST['mf_redirect'];
			$mf_sms=$_POST['mf_sms'];
			$mf_cname=$_POST['mf_cname'];
        
			update_option('mf_sid', $mf_sid);
			update_option('mf_redirect', $mf_redirect);
			update_option('mf_sms', $mf_sms);
			update_option('mf_cname', $mf_cname);
			
			?>
			<div class="updated"><p><strong><?php _e('Options saved.', 'mt_trans_domain' ); ?></strong></p></div>
			<?php
		}

// Get options from Wordpress Database (again)
	$mf_sid=get_option("mf_sid");
	$mf_redirect=get_option("mf_redirect");
	$mf_sms=get_option("mf_sms");
	$mf_cname=get_option("mf_cname");
    ?>

	<div class="wrap">
	<form name="form1" method="post" action="<?php echo str_replace( '%7E', '~', $_SERVER['REQUEST_URI']); ?>">
		<input type="hidden" name="mf_hidden" value="1">
	<p style="margin-top: 10px; padding: 10px; border-bottom: 1px solid #ebebeb; border-top: 1px solid #ebebeb; font-size: 14px;">
	<a href="http://app.mofuse.com" style="margin-right: 20px; text-decoration: none;">Your MoFuse Account Dashboard</a> <a href="http://www.mofuse.com" style=" text-decoration: none;">Create a Mobile Site</a>
	</p>
    
		<div style="margin: 0 0 20px 0; padding: 10px; border: 1px solid #ebebeb; background-color: #fbfbfb;">
			<p style="font-size: 17px; color: #333333; margin: 0 0 0 0;"><?php _e("Your MoFuse Site ID:", 'mf_trans_domain' ); ?></p>
			<p style="margin: 5px 0 0 10px;">
	        http://<input name="mf_sid" type="text" size="20" value="<?php echo $mf_sid; ?>">.mofuse.mobi
			<br /><span style="color: #666666;">This is in your MoFuse URL, for example http://<em>myblog</em>.mofuse.mobi -- <b>myblog</b> would be your Site ID.<br />If you are using a custom domain name, we will forward the visitor to that domain name automatically.</span>
			</p>            
        </div>

		<div style="margin: 0 0 20px 0; padding: 10px; border: 1px solid #ebebeb; background-color: #fbfbfb;">
	        <p style="font-size: 17px; color: #333333; margin: 0 0 0 0;"><?php _e("Enable Automatic Detect & Redirect:", 'mf_trans_domain' ); ?></p>
			<p style="margin: 5px 0 0 10px;">
			<label>
			<input name="mf_redirect" type="radio" id="mf_redirect_0" value="y" <?php if ($mf_redirect=='y') { echo 'checked="checked"'; } ?> />
			Yes</label>
			<label>
			<input type="radio" name="mf_redirect" value="n" id="mf_redirect_1" <?php if ($mf_redirect=='n') { echo 'checked="checked"'; } ?> />
			No</label>
		</p>

		<p style="font-size: 17px; color: #999999; margin: 20px 0 0 0; display: none;"><?php _e("Enable SMS Widget:", 'mf_trans_domain' ); ?></p>
			<p style="margin: 5px 0 0 10px; display: none;">
    	    <label>
			<input name="mf_sms" type="radio" id="mf_sms_0" value="y" <?php if ($mf_sms=='y') { echo 'checked="checked"'; } ?> />
			Yes</label>
			<label>
			<input type="radio" name="mf_sms" value="n" id="mf_sms_1" <?php if ($mf_sms=='n') { echo 'checked="checked"'; } ?> />
			No</label>
    	    <br /><span style="color: #999999;">Not active in this version</span>
			</p>
        </div>

		<p class="submit">
		<input type="submit" name="Submit" value="<?php _e('Update Options', 'mf_trans_domain' ) ?>" />
		</p>

	</form>
	</div>
<?php
}
?>