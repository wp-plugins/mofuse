<?php
/*
Plugin Name: MoFuse Plugin
Plugin URI: http://www.mofuse.com/wordpress/
Description: MoFuse's Wordpress plugin. If you need a MoFuse account, visit <a href="http://www.mofuse.com">www.mofuse.com</a> and create a free account and you can have a mobile version of your Wordpress blog up and running in just seconds.
Author: David Berube
Version: 0.9n
Author URI: http://daveberube.com/
*/

// Get options from WP database
	$mf_sid=get_option("mf_sid");
	$mf_redirect=get_option("mf_redirect");
	$mf_sms=get_option("mf_sms");
	$mf_cname=get_option("mf_cname");
	$mf_iphone=get_option("mf_iphone");
	
	$mf_subd=str_replace("http://", "", get_settings('home'));
	$currenturl=$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI'];
	if ($mf_subd[strlen($mf_subd)-1]=='/') { } else {
		$mf_subd=$mf_subd.'/';
	}
	
	if (strlen($mf_cname)>3) { $mf_site_url=$mf_cname; } else { $mf_site_url=$mf_sid . ".mofuse.mobi"; }
	
// Mobile Detection
function wp_mf_mobile_detect() {
	global $mf_site_url;
	global $mf_iphone;

	if ($_SESSION['mofuse_nomobile']==1 || $_GET['nomobile']==1) { $_SESSION['mofuse_nomobile']=1; } else {
		// Access MoFuse API to detect mobile device
		$mf_ua=$_SERVER["HTTP_USER_AGENT"];
		$isMobile = false;
		$isBot = false;


		$isMobile = stripos($ac, 'application/vnd.wap.xhtml+xml') !== false
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
				|| stripos($mf_ua, 'nintendo wii') !== false;

			if($isMobile) { $mf_ismobile=1; }
    		if (stripos($mf_ua, 'iphone')!==false || stripos($ua, 'ipod')!==false) { $mf_isiphone=1; }
			if (stripos($mf_ua, 'android')!==false) { $mf_isandroid=1; }
	
	}

	if ($mf_isandroid==1 || $mf_isiphone==1) { $mf_ismobile=0; }

	if (($mf_ismobile==1 || $mf_isandroid==1) || ($mf_isiphone==1 && $mf_iphone!="n")) {
		if ($_GET['nomobile'] == 1) { } else {
			if ($_SERVER['REQUEST_URI']=="/" || $_SERVER['REQUEST_URI']=="") { } else {
				$mf_post_url=urlencode("http://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']);
				// Redirect to Archive page
	//			header("Location: http://db.mofuse.mobi/?page=archive&p=".$mf_post_url);
				if ($mf_isiphone==1) {
					wp_redirect("http://".$mf_site_url."/iphone/?page=archive&p=".$mf_post_url);
					exit();
				} elseif ($mf_isandroid==1) {
					wp_redirect("http://".$mf_site_url."/iphone/?page=archive&p=".$mf_post_url);
					exit();					
				} else {
					wp_redirect("http://".$mf_site_url."/?page=archive&p=".$mf_post_url);
					exit();
				}
			}
			// Redirect to mobile home	
			wp_redirect("http://".$mf_site_url);
			exit();
		}
	}
}
// End Mobile Detection

// Plugin work

	add_action('init', 'wp_mf_mobile_detect');
	add_action('admin_menu', 'mf_add_pages');

	function mf_add_pages() {
		add_options_page('MoFuse', 'MoFuse', 10, __FILE__, 'mf_toplevel_page');
	}

	function mf_toplevel_page() {
	
	    if($_POST) {
			$mf_sid=$_POST['mf_sid'];
			$mf_redirect=$_POST['mf_redirect'];
			$mf_sms=$_POST['mf_sms'];
			$mf_iphone=$_POST['mf_iphone'];
			
			$mf_cname=$_POST['mf_cname'];
			$mf_cname=str_replace("http://", "", $mf_cname);
			$mf_cname=str_replace("/", "", $mf_cname);
        
			update_option('mf_sid', $mf_sid);
			update_option('mf_redirect', $mf_redirect);
			update_option('mf_sms', $mf_sms);
			update_option('mf_cname', $mf_cname);
			update_option('mf_iphone', $mf_iphone);
			
			$mf_saved=1;
		}

// Get options from Wordpress Database (again)
	$mf_sid=get_option("mf_sid");
	$mf_redirect=get_option("mf_redirect");
	$mf_sms=get_option("mf_sms");
	$mf_cname=get_option("mf_cname");
	$mf_iphone=get_option("mf_iphone");
?>
<style type="text/css">
p {
	font-size: 16px;
	margin: 0 0 10px 0;
}

#mofuse_wrap { background-color: #464646; margin-right: 0px; padding: 20px; min-height: 700px; margin-bottom: -10px; padding-bottom: 20px;}
#mofuse_logo { margin: 0 0 20px 0; }
#mofuse_yellow_message { margin: 0; padding: 10px; background-color: #ffffcc; font-size: 17px; color: #333; border-bottom: 1px solid #ebebeb; }
#mofuse_links { font-size: 12px; margin: 0 0 10px 0px; padding: 0 0 10px 10px; border-bottom: 1px solid #c1c1c1;}
#mofuse_links_left { width: 48%; float: left; }
#mofuse_links_right { width: 48%; float: right; text-align: right; }
#mofuse_links a { margin-right: 10px; }

.mofuse_settings_box { margin: 0; padding: 10px; background-color: #fff; font-size: 17px; color: #333; }

p.mofuse_headline { font-size: 22px; letter-spacing: -0.03em; color: #333; margin: 0 0 10px 0; }
p.mofuse_sub_headline { font-size: 19px; letter-spacing: -0.03em; color: #342F27; margin: 0 0 10px 0; }
p.mofuse_helpers { margin: 4px 0; font-size: 14px; color: #666; background-color: #E0E7F1; padding: 3px; width: 90%; }

.mofuse_input { padding: 5px; color: #23486D; font-weight: bold; border: 1px solid #c1c1c1; font-size: 16px; background-color: #fbfbfb; }
.mofuse_button { padding: 3px; color: #333; border: 1px solid #c1c1c1; font-size: 16px; }

#mofuse_settings_table { margin-left: 10px; margin-top: 5px; }
#mofuse_settings_table tr td { font-size: 16px; padding: 20px 5px; border-bottom: 1px solid #ebebeb; }

#mofuse_hidden { display: none; margin-top: 10px; margin-left: 10px; padding: 20px 5px; font-size:14px;}
#mofuse_hidden_small_text p { font-size: 14px; }

.mofuse_clearfix { clear: both; }
</style>
<div id="mofuse_wrap">
<form name="form1" method="post" action="<?php echo str_replace( '%7E', '~', $_SERVER['REQUEST_URI']); ?>">
	<img src="http://www.mofuse.com/images/logo_wordpress.png" id="mofuse_logo"/>
    
	<div id="mofuse_yellow_message">
		<p style="margin: 0;">
			<img src="http://app.mofuse.com/users/images/icons/exclamation.png" align="absmiddle" style="margin-right: 5px;" /> <strong>Note:</strong> This plugin requires a free <a href="http://www.mofuse.com/">MoFuse</a> account.
        </p>
	</div>
    
    <div id="mofuse_site_id" class="mofuse_settings_box">

		<?php
		if ($mf_saved==1) {
			?>
			<div class="updated" style="margin: 20px 0;"><p><strong><?php _e('Options saved.', 'mt_trans_domain' ); ?></strong></p></div>  
            <?php
		}
		?>

    	<p class="mofuse_headline" id="mofuse_headline">
	        <?php _e("Settings")?>
        </p>
        
        <div id="mofuse_links">
        	<div id="mofuse_links_left">
			    <a href="javascript:;" onclick="mofuse_showHidden();" id="mofuse_hidden_link"><strong>What's New</strong></a><a href="http://app.mofuse.com/" target="_blank">Your Account</a> 
            </div>
            <div id="mofuse_links_right">
    	        <a href="http://blog.mofuse.com/" target="_blank">MoFuse Blog</a> <a href="http://twitter.com/dberube/" target="_blank">Twitter</a>
	        </div>
		<div class="mofuse_clearfix"></div></div>

		<div id="mofuse_settings">
		<table id="mofuse_settings_table" cellpadding="5" cellspacing="0" border="0" width="100%">
        	<tr>
            	<td width="30%" valign="top">
                	Your Site ID
                </td>
                <td width="70%">
	                http://<input name="mf_sid" type="text" size="20" value="<?php echo $mf_sid; ?>" class="mofuse_input">.mofuse.mobi
                    <p class="mofuse_helpers">
                    	This is in your MoFuse URL, for example http://myblog.mofuse.mobi<br /><b>myblog</b> would be your Site ID.
                    </p>
                </td>
            </tr>

        	<tr>
            	<td width="25%" valign="top">
                	Custom Domain Name<br /><span style="color: #999; font-size: 14px;">(optional)</span>
                </td>
                <td width="75%">
	                http://<input name="mf_cname" type="text" size="20" value="<?php echo $mf_cname; ?>" class="mofuse_input" style="width: 325px;">
                    <p class="mofuse_helpers">
                    	This is the custom domain you set your mobile site up to use.
                    </p>
                    <p class="mofuse_helpers">
                    	<strong>THIS MUST ALREADY BE SETUP AND WORKING PRIOR TO ENTERING IT IN HERE.</strong>
                    </p>
                </td>
            </tr>

        	<tr>
        	  <td valign="top">Enable iPhone Site?</td>
        	  <td>
                    <label>
                      <input name="mf_iphone" type="radio" id="mf_iphone_0" value="y" <?php if ($mf_iphone=='y') { echo 'checked="checked"'; } ?> />
                      Yes
                    </label>
                    <label style="margin-left: 10px;">
                      <input type="radio" name="mf_iphone" value="n" id="mf_iphone_1" <?php if ($mf_iphone=='n') { echo 'checked="checked"'; } ?> />
                      No
                    </label>
                    <p class="mofuse_helpers">
                    	Setting to No will show iPhone readers your regular site.
                    </p>        
              </td>
      	  </tr>
        	<tr>
            	<td width="25%" valign="top">
                	Enabled Plugin?
                </td>
                <td width="75%">
                    <label>
                      <input name="mf_redirect" type="radio" id="mf_redirect_0" value="y" <?php if ($mf_redirect=='y') { echo 'checked="checked"'; } ?> />
                      Yes
                    </label>
                    <label style="margin-left: 10px;">
                      <input type="radio" name="mf_redirect" value="n" id="mf_redirect_1" <?php if ($mf_redirect=='n') { echo 'checked="checked"'; } ?> />
                      No
                    </label>
                </td>
            </tr>
            
        	<tr>
            	<td width="25%" valign="top">&nbsp;
                	
                </td>
                <td width="75%">
					<input type="submit" name="Submit" value="<?php _e('Save Settings', 'mf_trans_domain' ) ?>" class="mofuse_button"/>
                </td>
            </tr>
        </table>
        </div>

        <div id="mofuse_hidden">
            <p class="mofuse_sub_headline">Deep Link</p>
            <div id="mofuse_hidden_small_text">
				<p>We implemented new technology in the beginning of February that enables some blogs using MoFuse the ability to have a mobile archive of their blog.</p>
    	        <p>The best way to explain this is by example.</p>
        	    <p style="margin-left: 30px; color: #342F27;">
            		A user on a mobile handset is using Google's mobile search. Their search results show a post from this blog that was made a few weeks back. That blog post is no longer in your RSS feed becuase you've posted 10 items since then and your RSS feed only shows your 10 latest posts.
	            </p>
    	        <p style="margin-left: 30px; color: #342F27;">
        	    	The mobile user clicks the search result linking to your old blog post. The MoFuse plugin will now send a request to the MoFuse server and we will work some magic. MoFuse will now try and display the mobile version of that page to the mobile user. If we cannot serve the mobile version of that page we will pass the mobile user on to the full version of the page -- never breaking a link. 
            	</p>
                <p>MoFuse may not be able to display a mobile version for every page or post. The more traffic your mobile site gets the more likely we are to be able to successfully serve the mobile pageview.</p>
			</div>

            <p class="mofuse_sub_headline">Improved Mobile Detection</p>
            <div id="mofuse_hidden_small_text">
				<p>You no longer have to make API calls to the MoFuse servers to detect a mobile device. The plugin does it all locally.</p>
			</div>

            <p class="mofuse_sub_headline">iPhone Option</p>
            <div id="mofuse_hidden_small_text">
				<p>We don't suggest you do this, but you can choose to ignore your iPhone traffic. Doing this will show iPhone users your full HTML version.</p>
			</div>

        </div>
        
    </div>
</form>

</div>

<script type="text/javascript">
var mf_hidden=1;

function mofuse_showHidden() {
	if (mf_hidden==1) {
		document.getElementById('mofuse_hidden').style.display='block';
		document.getElementById('mofuse_settings').style.display='none';
		document.getElementById('mofuse_hidden_link').innerHTML="<strong>Back to Settings</strong>";
		document.getElementById('mofuse_headline').innerHTML="What's New";
		mf_hidden=2;
	} else {
		document.getElementById('mofuse_hidden').style.display='none';
		document.getElementById('mofuse_settings').style.display='block';
		document.getElementById('mofuse_hidden_link').innerHTML="<strong>What's New</strong>";	
		document.getElementById('mofuse_headline').innerHTML="Settings";
		mf_hidden=1;
	}
}
</script>
<?php } ?>