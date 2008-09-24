<?php
/*
 Plugin Name: Hatena Star
 Plugin URI: http://hidetake.org/pages/hatena-star-plugin
 Description: Adds Hatena Star icon.
 Author: Hidetake Iwata
 Author URI: http://hidetake.org/
 Version: 1.0
 License: GPLv2 (http://www.fsf.org/licensing/licenses/info/GPLv2.html)
 */

load_plugin_textdomain('hatena-star', 'wp-content/plugins/hatena-star/');



if(!function_exists('hatenastar_add_pages')):
function hatenastar_add_pages()
{
	add_submenu_page('options-general.php', __('Hatena Star'), __('Hatena Star'), 'manage_options', __FILE__, 'hatenastar_submenu');
}
endif;
add_action('admin_menu', 'hatenastar_add_pages');



if(!function_exists('hatenastar_submenu')):
function hatenastar_submenu()
{
	if(isset($_POST['update'])) {
		if(function_exists('current_user_can') && !current_user_can('manage_options')) {
			die(__('Cheatin&#8217; uh?'));
		}
		// save settings
		$options = array(
			token => wp_specialchars($_POST['token']),
			headerTagAndClassName => array(wp_specialchars($_POST['headerTag']), wp_specialchars($_POST['headerClassName'])),
			siteConfig => $_POST['siteConfig'],
		);
		update_option('hatena-star-options', $options);
//		var_dump($_POST);
//		var_dump($options);
?>
<div id="message" class="updated fade"><p><?php _e('Options saved.') ?></p></div>
<?php
	}
	else {
		// load settings
		$options = get_option('hatena-star-options');
		if(!$options) {
			$options = array(
				headerTagAndClassName => array('h2', '')
			);
		}
	}
	// render view
?>
<div class="wrap">
<h2><?php _e('Hatena Star Settings', 'hatena-star') ?></h2>
<form method="post" action="<?php echo $_SERVER['REQUEST_URI'] ?>">
<table class="form-table">
<tr>
<th align="left"><?php _e('Authentication Token', 'hatena-star') ?></th>
<td>
<input type="text" name="token" size="32" value="<?php echo $options['token'] ?>" />
<br/>
<?php _e('Authentication token allows the association between your Hatena account and Hatena Stars. For details, see <a href="http://d.hatena.ne.jp/hatenastar/20070707/1184453490">How to set up Hatena Star service into your blog</a>.', 'hatena-star') ?>
</td>
</tr>

<tr>
<th align="left"><?php _e('Tags', 'hatena-star') ?></th>
<td>
<?php _e('Tag Name', 'hatena-star') ?> <input type="text" name="headerTag" size="16" value="<?php echo $options['headerTagAndClassName'][0] ?>" />
<?php _e('Class Name', 'hatena-star') ?> <input type="text" name="headerClassName" size="16" value="<?php echo $options['headerTagAndClassName'][1] ?>" />
<br/>
<?php _e('Example, set Tag Name <span style="border:solid 1px silver;">h2</span> and Class Name <span style="border:solid 1px silver;">title</span>, star icons will be shown after &lt;h2 class="title"&gt; elements.', 'hatena-star') ?>
</td>
</tr>

<tr>
<th align="left"><?php _e('Custom', 'hatena-star') ?></th>
<td>
Hatena.Star.SiteConfig = {
<br/>
<textarea name="siteConfig" style="width:32em; height:10em;"><?php echo $options['siteConfig'] ?></textarea>
<br/>
};
<br/>
<?php _e('For details, see <a href="http://d.hatena.ne.jp/hatenastar/20070707/1184453490">How to set up Hatena Star service into your blog</a>.', 'hatena-star') ?>
</td>
</tr>
</table>

<p class="submit"><input type="submit" name="update" value="<?php _e('Update options &raquo;'); ?>" /></p>
</form>
</div>
<?php
}
endif;



if(!function_exists('hatenastar_head')):
function hatenastar_head()
{
	$options = get_option('hatena-star-options');
?>
<script type="text/javascript" src="http://s.hatena.ne.jp/js/HatenaStar.js"></script>
<script type="text/javascript">
//<![CDATA[
<?php if($options['token']): ?>
Hatena.Star.Token = '<?php echo $options['token'] ?>';
<?php endif; ?>
<?php if($options['headerTagAndClassName']): ?>
Hatena.Star.EntryLoader.headerTagAndClassName = ['<?php echo $options['headerTagAndClassName'][0] ?>','<?php echo $options['headerTagAndClassName'][1] ?>'];
<?php endif; ?>
<?php if($options['siteConfig']): ?>
Hatena.Star.SiteConfig = {
<?php echo $options['siteConfig'] ?>
};
<?php endif; ?>
//]]>
</script>
<?php
}
endif;
add_action('wp_head', 'hatenastar_head');



?>
