<?php
/*
Plugin Name: MoFuse Plugin
Plugin URI: http://www.mofuse.com/wordpress
Description: MoFuse's Wordpress plugin. If you need a MoFuse account, visit <a href="http://www.mofuse.com">www.mofuse.com</a> and create a free account and you can have a mobile version of your Wordpress blog up and running in just seconds.
Author: MoFuse
Version: 0.6.3 
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
	
	$mf_ua=urlencode($_SERVER["HTTP_USER_AGENT"]);
	$mf_url="http://api.mofuse.com/?action=mobiledetect&useragent=$mf_ua";
	if (@file_get_contents($mf_url)==1 && $_GET['mf_nr']!=1) {
		$mf_ismobile=1;
	}
	
	function mf_hpd($currenturl, $mf_subd) {
		if($currenturl=="" || $currenturl=="/" || $currenturl==$mf_subd) {
			return true;
		} else {
			return false; 
		}
	}

	if ($mf_redirect=='y') {
		if($mf_ismobile==1){ 
			if (mf_hpd($currenturl, $mf_subd)) {
				//header("Location: http://www.google.com");
				add_action('wp_head', "sendtoMobile",3);
			}
		}
	}

	function sendtoMobile() {
		global $mf_sid;
		global $mf_cname;
		if ($_SESSION['mofuse_nomobile']==1 || $_GET['nomobile']==1) { $_SESSION['mofuse_nomobile']=1; } else {
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
		echo '<img src="http://mofuse.com/images/logo_small.png" style="margin:20px 0px 20px 20px;" />';
	
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

// Show stuff on the MoFuse Options page
    echo '<div class="wrap">';
//    echo "<h2>" . __( 'MoFuse Plugin Options', 'mf_trans_domain' ) . "</h2>";
	echo '<p style="margin-top: 10px; padding: 10px; background-color: #ffffec; font-size: 17px; border: 1px solid #ebebeb; color: #333;">
	<img src="http://snapple.mofuse.com/users/images/icons/exclamation.png" align="absmiddle" style="margin-right: 5px;" /> 
	If you don\'t already have a MoFuse account you can visit <a href="http://www.mofuse.com" target="_blank">www.mofuse.com</a> and create one for free.</p>';    
    ?>

	<form name="form1" method="post" action="<?php echo str_replace( '%7E', '~', $_SERVER['REQUEST_URI']); ?>">
		<input type="hidden" name="mf_hidden" value="1">

		<div style="margin: 0 0 20px 0; padding: 10px; border: 1px solid #012D7E; background-color: #fbfbfb;">
			<p style="font-size: 17px; color: #333333; margin: 0 0 0 0;"><?php _e("Your MoFuse Site ID:", 'mf_trans_domain' ); ?></p>
			<p style="margin: 5px 0 0 10px;">
	        http://<input name="mf_sid" type="text" size="20" value="<?php echo $mf_sid; ?>">.mofuse.mobi
			<br /><span style="color: #666666;">This is in your MoFuse URL, for example http://myblog.mofuse.mobi -- <b>myblog</b> would be your Site ID.</span>
            <br /><span style="color: #666666;">If you use a custom domain name, MoFuse will send your viewer to that domain instead of the mofuse.mobi domain -- automatically.</span>
			</p>            
        </div>

		<div style="margin: 0 0 20px 0; padding: 10px; border: 1px solid #012D7E; background-color: #fbfbfb;">
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