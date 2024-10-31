<?php
/*
Plugin Name: Revolution Ajax Page Loader Lite
Plugin URI: http://wpexplored.com
Description: Ajaxify your wordpress site just in minutes. Works on post, page, category, archieve, and almost eveywhere.
Version: 1.0
Author: Sunil Chaulagain
Author URI: http://wpexplored.com
License: GPLv2 or later
*/

/*
This program is free software; you can redistribute it and/or
modify it under the terms of the GNU General Public License
as published by the Free Software Foundation; either version 2
of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
*/
if(!session_id()) {
	session_start();
	$_SESSION['ajaxdata']='nset';
} else { 
	$_SESSION['ajaxdata']='nset';
} 
$revoltload= ($_GET["revoltload"] == "true"); 
if ($revoltload) {  
	$_SESSION['ajaxdata']='set';
	ob_start();
}
add_action('wp_head', 'revoltload_header',100);
add_action('wp_footer', 'revoltload_footer',1001);

function revoltload_footer(){
	if($_SESSION['ajaxdata']=='set'){
		$page_content = ob_get_clean();
		die(json_encode(array(
		"title" => get_the_title(),
		"content" => $page_content
		)));
		?> </div> <?php
	}
}

function revoltload_header(){
	?>
	<script src="<?php echo WP_PLUGIN_URL; ?>/revoltload/js/revoltload.js"></script>		
	<script>
	jQuery(document).ready(function($) {
	$.revoltload();
	<?php if(get_option("revoltload_display")==1) { ?>
	function initPage() {
	$("#ploading").css("display", "block");	
        $("#footertext").css("display", "block");	
	}
	function destroyPage() {
	$("#ploading").css("display", "none");
        $("#footertext").css("display", "none");
	}
	function destroyPage2() {
	$("#ploading").fadeOut(2000);
	}
	$(window).on("revoltload.request", initPage)
	.on("revoltload.load", destroyPage2)
	.on("revoltload.render", destroyPage)
	<?php } ?>
	$("#ploading").css("display", "none");
	});
	</script>
	<?php
	if(get_option("revoltload_switch")==0){
		$position=get_option("revoltload_position");
		if($position==1){
		$style="left:0;top:0;";
		} elseif($position==2){
		$style="left:0;bottom:0;";
		} elseif($position==3){
		$style="right:0;top:0;";
		} elseif($position==4){
		$style="right:0;bottom:0";
		} elseif($position==5){
		$style="left:45%;top:0";
		} elseif($position==6){
		$style="left:45%;bottom:0";
		}elseif($position==7){
		$style="left:45%;top:45%";
		}

	} else { 
	$position=get_option("revoltload_position");
		if($position==1){
		$style="left:0;top:0;";
		} elseif($position==2){
		$style="left:0;bottom:0;";
		} elseif($position==3){
		$style="right:0;top:0;";
		} elseif($position==4){
		$style="right:0;bottom:0";
		} elseif($position==5){
		$style="left:48%;top:0";
		} elseif($position==6){
		$style="left:48%;bottom:0";
		}elseif($position==7){
		$style="left:48%;top:48%";
		}
	}?>  
	<style>
	.ploading{
	display:none;
	position:absolute;
	background:#fff;
	border:#ccc solid 2px; 
	<?php if(get_option("revoltload_switch")==0){ ?>
	padding:5px;
	background:#F4F7A1;
	<?php } ?>
	background:#fff;
	font-weight:600;
	box-shadow:  0px 0px 14px 2px #444;
	z-index:1000000000000000000000000000;
	<?php echo $style; ?>
	}
        .footertext{
        position:absolute;
        background:#111;
        color:#fff;
        font-weight:600;
	box-shadow:  0px 0px 14px 2px #444;
	z-index:1000000000000000000000000000;
        top:0px;
	display:none;
        padding:10px;
        font-size:16px;
        }
	</style>

	<div id="ploading" class="ploading">
        <?php if(get_option("revoltload_switch")==0){ echo get_option("revoltload_text"); } else { echo "<img src='".get_option("revoltload_image")."' >"; } ?></div>
<div id="footertext" class="footertext">Powered by <a href="http://wpexplored.com">Wpexplored.com</a></div>
	<div id="revoltload">
	<?php	if($_SESSION['ajaxdata']=='set') { ob_get_clean(); ob_start(); } ?>
	<?php 

} 

define('BAR_PLUGIN_URL', plugin_dir_url( __FILE__ ));
include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
register_activation_hook(__FILE__,'revoltload_install'); 

function revoltload_install() {
	add_option('revoltload_version', '2');
	add_option('revoltload_display', '1');
	add_option('revoltload_switch', '1');
	add_option('revoltload_text', 'Loading...');
	add_option('revoltload_position', '5');
	$image_url=WP_PLUGIN_URL.'/revoltload/imgs/loader.gif';
	add_option('revoltload_image', $image_url);
}

function pw_loading_scripts_jquery_again() {
	wp_enqueue_script('jquery');
}

add_action('wp_print_scripts', 'pw_loading_scripts_jquery_again');

function revolt_admin(){
	global $wpdb;
	if(isset($_POST['submit'])) {
	update_option( revoltload_text, $_POST['revoltload_text'] );
		update_option( revoltload_position, $_POST['revoltload_position'] );
	if(isset($_POST['revoltload_display'])) {
	update_option( revoltload_display,'1' );
	} else {
	update_option( revoltload_display,'0' );
	}
	if(isset($_POST['revoltload_switch'])) {
	update_option( revoltload_switch,'1' );
	} else {
	update_option( revoltload_switch,'0' );
	}
}


?>
<div style=" padding:10px;">
<div  class="widgets-holder-wrap " style="width:560px;float:left;margin-right:20px;">
<div class="sidebar-name"><h3>Revolution Ajax Loader Settings</h3></div>
<div class="widget-holder" style="border: 1px solid #ccc;padding:10px; ">
<form method="post" action=""> 
<div style="font-weight:bold;font-size:13px;margin-bottom:6px;"> Enable loader :
<?php $revoltload_display=get_option('revoltload_display'); ?>
<?php if($revoltload_display==1 ) { ?>
<input name="revoltload_display"  type="checkbox" checked="checked"  />
<?php } else { ?>
<input name="revoltload_display"  type="checkbox"  />
<?php } ?> 
</div>

<div style="font-weight:bold;font-size:13px;margin-bottom:6px;"> Loader Image/ Text Switch :
<?php $revoltload_switch=get_option('revoltload_switch'); ?>
<?php if($revoltload_switch==1 ) { ?>
<input name="revoltload_switch"  type="checkbox" checked="checked"  />
<?php } else { ?>
<input name="revoltload_switch"  type="checkbox"  />
<?php } ?> 
</div>

<div style="font-weight:bold;font-size:13px;margin-bottom:6px;"> Loader Text :
<?php $revoltload_text=get_option('revoltload_text'); ?>
<input type="text" name="revoltload_text"  size="20" value="<?php echo $revoltload_text; ?>">
</div>

<div style="font-weight:bold;font-size:13px;margin-bottom:6px;"> Loader Position :
<?php $position=get_option('revoltload_position'); ?>
<select name="revoltload_position" >
<option selected="selected" value="<?php echo $position; ?>"><?php if($position==1){ echo 'Left,Top'; }elseif($position==2){ echo 'Left,Bottom'; }elseif($position==3){ echo 'Right,Top';} elseif($position==4){ echo 'Right,Bottom';}elseif($position==5){ echo 'Top, Center';}elseif($position==6){ echo 'Bottom, Center';}elseif($position==7){ echo 'Center';} ?>  </option>
<?php if($position!=1) { ?><option value="1">Left,Top</option> <?php } ?>
<?php if($position!=2) { ?><option value="2">Left,Bottom</option> <?php } ?>
<?php if($position!=3) { ?><option value="3">Right,Top</option> <?php } ?>
<?php if($position!=4) { ?><option value="4">Right,Bottom</option><?php } ?>
<?php if($position!=5) { ?><option value="5">Top, Center</option><?php } ?>
<?php if($position!=6) { ?><option value="6">Bottom, Center</option><?php } ?>
<?php if($position!=7) { ?><option value="7">Center</option><?php } ?>
</select>
</div>

<div class="clear"></div>
<hr>
<input type="submit" name="submit" class="button-primary" value="<?php _e('Save Changes') ?>" />

</form>
</div></div>   
</div>

<?php
}

function revoltload_menu() {
	add_menu_page('Revolution Loader', 'Revolution Loader', 'add_users', 'revoltload', 'revolt_admin','', 4);
}
add_action('admin_menu', 'revoltload_menu');
?>