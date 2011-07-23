<?php if (!defined('BB2_CORE')) die('I said no cheating!');

require_once("bad-behavior/responses.inc.php");

function bb2_admin_pages() {
	global $wp_db_version;

	if (function_exists('current_user_can')) {
		// The new 2.x way
		if (current_user_can('manage_options')) {
			$bb2_is_admin = true;
		}
	} else {
		// The old 1.x way
		global $user_ID;
		if (user_can_edit_user($user_ID, 0)) {
			$bb2_is_admin = true;
		}
	}

	if ($bb2_is_admin) {
		add_options_page(__("Bad Behavior"), __("Bad Behavior"), 8, 'bb2_options', 'bb2_options');
		if ($wp_db_version >= 4772) {	// Version 2.1 or later
			add_management_page(__("Bad Behavior"), __("Bad Behavior"), 8, 'bb2_manage', 'bb2_manage');
		}
		@session_start();
	}
}

function bb2_clean_log_link($uri) {
	foreach (array("paged", "ip", "key", "blocked", "request_method", "user_agent") as $arg) {
		$uri = remove_query_arg($arg, $uri);
	}
	return $uri;
}

function bb2_httpbl_lookup($ip) {
	// NB: Many of these are defunct
	$engines = array(
		1 => "AltaVista",
		2 => "Teoma/Ask Crawler",
		3 => "Baidu Spide",
		4 => "Excite",
		5 => "Googlebot",
		6 => "Looksmart",
		7 => "Lycos",
		8 => "msnbot",
		9 => "Yahoo! Slurp",
		10 => "Twiceler",
		11 => "Infoseek",
		12 => "Minor Search Engine",
	);
	$settings = bb2_read_settings();
	$httpbl_key = $settings['httpbl_key'];
	if (!$httpbl_key) return false;

	$r = $_SESSION['httpbl'][$ip];
	$d = "";
	if (!$r) {	// Lookup
		$find = implode('.', array_reverse(explode('.', $ip)));
		$result = gethostbynamel("${httpbl_key}.${find}.dnsbl.httpbl.org.");
		if (!empty($result)) {
			$r = $result[0];
			$_SESSION['httpbl'][$ip] = $r;
		}
	}
	if ($r) {	// Interpret
		$ip = explode('.', $r);
		if ($ip[0] == 127) {
			if ($ip[3] == 0) {
				if ($engines[$ip[2]]) {
					$d .= $engines[$ip[2]];
				} else {
					$d .= "Search engine ${ip[2]}<br/>\n";
				}
			}
			if ($ip[3] & 1) {
				$d .= "Suspicious<br/>\n";
			}
			if ($ip[3] & 2) {
				$d .= "Harvester<br/>\n";
			}
			if ($ip[3] & 4) {
				$d .= "Comment Spammer<br/>\n";
			}
			if ($ip[3] & 7) {
				$d .= "Threat level ${ip[2]}<br/>\n";
			}
			if ($ip[3] > 0) {
				$d .= "Age ${ip[1]} days<br/>\n";
			}
		}
	}
	return $d;
}

function bb2_manage() {
	global $wpdb;

	$request_uri = $_SERVER["REQUEST_URI"];
	if (!$request_uri) $request_uri = $_SERVER['SCRIPT_NAME'];	# IIS
	$settings = bb2_read_settings();
	$rows_per_page = 100;
	$where = "";

	// Get query variables desired by the user with input validation
	$paged = 0 + $_GET['paged']; if (!$paged) $paged = 1;
	if ($_GET['key']) $where .= "AND `key` = '" . $wpdb->escape($_GET['key']) . "' ";
	if ($_GET['blocked']) $where .= "AND `key` != '00000000' ";
	if ($_GET['ip']) $where .= "AND `ip` = '" . $wpdb->escape($_GET['ip']) . "' ";
	if ($_GET['user_agent']) $where .= "AND `user_agent` = '" . $wpdb->escape($_GET['user_agent']) . "' ";
	if ($_GET['request_method']) $where .= "AND `request_method` = '" . $wpdb->escape($_GET['request_method']) . "' ";

	// Query the DB based on variables selected
	$r = bb2_db_query("SELECT COUNT(*) FROM `" . $settings['log_table']);
	$results = bb2_db_rows($r);
	$totalcount = $results[0]["COUNT(*)"];
	$r = bb2_db_query("SELECT COUNT(*) FROM `" . $settings['log_table'] . "` WHERE 1=1 " . $where);
	$results = bb2_db_rows($r);
	$count = $results[0]["COUNT(*)"];
	$pages = ceil($count / 100);
	$r = bb2_db_query("SELECT * FROM `" . $settings['log_table'] . "` WHERE 1=1 " . $where . "ORDER BY `date` DESC LIMIT " . ($paged - 1) * $rows_per_page . "," . $rows_per_page);
	$results = bb2_db_rows($r);

	// Display rows to the user
?>
<div class="wrap">
<h2><?php _e("Bad Behavior"); ?></h2>
<form method="post" action="<?php echo $request_uri; ?>">
	<p>For more information please visit the <a href="http://www.bad-behavior.ioerror.us/">Bad Behavior</a> homepage.</p>
	<p>If you find Bad Behavior valuable, please consider <a href="https://www.paypal.com/cgi-bin/webscr?cmd=_xclick&business=error%40ioerror%2eus&item_name=Bad%20Behavior%20<?php echo BB2_VERSION; ?>%20%28From%20Admin%29&no_shipping=1&cn=Comments%20about%20Bad%20Behavior&tax=0&currency_code=USD&bn=PP%2dDonationsBF&charset=UTF%2d8">donating</a> to help further development of Bad Behavior.</p>

<div class="tablenav">
<?php
	$page_links = paginate_links(array('base' => add_query_arg("paged", "%#%"), 'format' => '', 'total' => $pages, 'current' => $paged));
	if ($page_links) echo "<div class=\"tablenav-pages\">$page_links</div>\n";
?>
<div class="alignleft">
<?php if ($count < $totalcount): ?>
Displaying <strong><?php echo $count; ?></strong> of <strong><?php echo $totalcount; ?></strong> records filtered by:<br/>
<?php if ($_GET['key']) echo "Status [<a href=\"" . remove_query_arg(array("paged", "key"), $request_uri) . "\">X</a>] "; ?>
<?php if ($_GET['blocked']) echo "Blocked [<a href=\"" . remove_query_arg(array("paged", "blocked"), $request_uri) . "\">X</a>] "; ?>
<?php if ($_GET['ip']) echo "IP [<a href=\"" . remove_query_arg(array("paged", "ip"), $request_uri) . "\">X</a>] "; ?>
<?php if ($_GET['user_agent']) echo "User Agent [<a href=\"" . remove_query_arg(array("paged", "user_agent"), $request_uri) . "\">X</a>] "; ?>
<?php if ($_GET['request_method']) echo "GET/POST [<a href=\"" . remove_query_arg(array("paged", "request_method"), $request_uri) . "\">X</a>] "; ?>
<?php else: ?>
Displaying all <strong><?php echo $totalcount; ?></strong> records<br/>
<?php endif; ?>
<?php if (!$_GET['key'] && !$_GET['blocked']) { ?><a href="<?php echo add_query_arg(array("blocked" => "true", "paged" => false), $request_uri); ?>">Show Blocked</a><?php } ?>
</div>
</div>

<table class="widefat">
	<thead>
	<tr>
	<th scope="col" class="check-column"><input type="checkbox" onclick="checkAll(document.getElementById('request-filter'));" /></th>
	<th scope="col"><?php _e("IP/Date/Status"); ?></th>
	<th scope="col"><?php _e("Headers"); ?></th>
	<th scope="col"><?php _e("Entity"); ?></th>
	</tr>
	</thead>
	<tbody>
<?php
	$alternate = 0;
	if ($results) foreach ($results as $result) {
		$key = bb2_get_response($result["key"]);
		$alternate++;
		if ($alternate % 2) {
			echo "<tr id=\"request-" . $result["id"] . "\" valign=\"top\">\n";
		} else {
			echo "<tr id=\"request-" . $result["id"] . "\" class=\"alternate\" valign=\"top\">\n";
		}
		echo "<th scope=\"row\" class=\"check-column\"><input type=\"checkbox\" name=\"submit[]\" value=\"" . $result["id"] . "\" /></th>\n";
		$httpbl = bb2_httpbl_lookup($result["ip"]);
		$host = gethostbyaddr($result["ip"]);
		if (!strcmp($host, $result["ip"])) {
			$host = "";
		} else {
			$host .= "<br/>\n";
		}
		echo "<td><a href=\"" . add_query_arg("ip", $result["ip"], remove_query_arg("paged", $request_uri)) . "\">" . $result["ip"] . "</a><br/>$host<br/>\n" . $result["date"] . "<br/><br/><a href=\"" . add_query_arg("key", $result["key"], remove_query_arg(array("paged", "blocked"), $request_uri)) . "\">" . $key["log"] . "</a>\n";
		if ($httpbl) echo "<br/><br/>http:BL:<br/>$httpbl\n";
		echo "</td>\n";
		$headers = str_replace("\n", "<br/>\n", htmlspecialchars($result['http_headers']));
		if (@strpos($headers, $result['user_agent']) !== FALSE) $headers = substr_replace($headers, "<a href=\"" . add_query_arg("user_agent", rawurlencode($result["user_agent"]), remove_query_arg("paged", $request_uri)) . "\">" . $result['user_agent'] . "</a>", strpos($headers, $result['user_agent']), strlen($result['user_agent']));
		if (@strpos($headers, $result['request_method']) !== FALSE) $headers = substr_replace($headers, "<a href=\"" . add_query_arg("request_method", rawurlencode($result["request_method"]), remove_query_arg("paged", $request_uri)) . "\">" . $result['request_method'] . "</a>", strpos($headers, $result['request_method']), strlen($result['request_method']));
		echo "<td>$headers</td>\n";
		echo "<td>" . str_replace("\n", "<br/>\n", htmlspecialchars($result["request_entity"])) . "</td>\n";
		echo "</tr>\n";
	}
?>
	</tbody>
</table>
<div class="tablenav">
<?php
	$page_links = paginate_links(array('base' => add_query_arg("paged", "%#%"), 'format' => '', 'total' => $pages, 'current' => $paged));
	if ($page_links) echo "<div class=\"tablenav-pages\">$page_links</div>\n";
?>
<div class="alignleft">
</div>
</div>
</form>
</div>
<?php
}

function bb2_options()
{
	$settings = bb2_read_settings();

	$request_uri = $_SERVER["REQUEST_URI"];
	if (!$request_uri) $request_uri = $_SERVER['SCRIPT_NAME'];	# IIS

	if ($_POST) {
		if ($_POST['display_stats']) {
			$settings['display_stats'] = true;
		} else {
			$settings['display_stats'] = false;
		}
		if ($_POST['strict']) {
			$settings['strict'] = true;
		} else {
			$settings['strict'] = false;
		}
		if ($_POST['verbose']) {
			$settings['verbose'] = true;
		} else {
			$settings['verbose'] = false;
		}
		if ($_POST['logging']) {
			if ($_POST['logging'] == 'verbose') {
				$settings['verbose'] = true;
				$settings['logging'] = true;
			} else if ($_POST['logging'] == 'normal') {
				$settings['verbose'] = false;
				$settings['logging'] = true;
			} else {
				$settings['verbose'] = false;
				$settings['logging'] = false;
			}
		} else {
			$settings['verbose'] = false;
			$settings['logging'] = false;
		}
		if ($_POST['httpbl_key']) {
			$settings['httpbl_key'] = $_POST['httpbl_key'];
		} else {
			$settings['httpbl_key'] = '';
		}
		if ($_POST['httpbl_threat']) {
			$settings['httpbl_threat'] = $_POST['httpbl_threat'];
		} else {
			$settings['httpbl_threat'] = '25';
		}
		if ($_POST['httpbl_maxage']) {
			$settings['httpbl_maxage'] = $_POST['httpbl_maxage'];
		} else {
			$settings['httpbl_maxage'] = '30';
		}
		if ($_POST['offsite_forms']) {
			$settings['offsite_forms'] = true;
		} else {
			$settings['offsite_forms'] = false;
		}
		bb2_write_settings($settings);
?>
	<div id="message" class="updated fade"><p><strong><?php _e('Options saved.') ?></strong></p></div>
<?php
	}
?>
	<div class="wrap">
	<h2><?php _e("Bad Behavior"); ?></h2>
	<form method="post" action="<?php echo $request_uri; ?>">
	<p>For more information please visit the <a href="http://www.bad-behavior.ioerror.us/">Bad Behavior</a> homepage.</p>
	<p>If you find Bad Behavior valuable, please consider making a <a href="https://www.paypal.com/cgi-bin/webscr?cmd=_xclick&business=error%40ioerror%2eus&item_name=Bad%20Behavior%20<?php echo BB2_VERSION; ?>%20%28From%20Admin%29&no_shipping=1&cn=Comments%20about%20Bad%20Behavior&tax=0&currency_code=USD&bn=PP%2dDonationsBF&charset=UTF%2d8">financial contribution</a> to further development of Bad Behavior.</p>

	<h3><?php _e('Statistics'); ?></h3>
	<?php bb2_insert_stats(true); ?>
	<table class="form-table">
	<tr><td><label><input type="checkbox" name="display_stats" value="true" <?php if ($settings['display_stats']) { ?>checked="checked" <?php } ?>/> <?php _e('Display statistics in blog footer'); ?></label></td></tr>
	</table>

	<h3><?php _e('Logging'); ?></h3>
	<table class="form-table">
	<tr><td><label><input type="radio" name="logging" value="verbose" <?php if ($settings['verbose'] && $settings['logging']) { ?>checked="checked" <?php } ?>/> <?php _e('Verbose HTTP request logging'); ?></label></td></tr>
	<tr><td><label><input type="radio" name="logging" value="normal" <?php if ($settings['logging'] && !$settings['verbose']) { ?>checked="checked" <?php } ?>/> <?php _e('Normal HTTP request logging (recommended)'); ?></label></td></tr>
	<tr><td><label><input type="radio" name="logging" value="false" <?php if (!$settings['logging']) { ?>checked="checked" <?php } ?>/> <?php _e('Do not log HTTP requests (not recommended)'); ?></label></td></tr>
	</table>

	<h3><?php _e('Security'); ?></h3>
	<table class="form-table">
	<tr><td><label><input type="checkbox" name="strict" value="true" <?php if ($settings['strict']) { ?>checked="checked" <?php } ?>/> <?php _e('Strict checking (blocks more spam but may block some people)'); ?></label></td></tr>
	<tr><td><label><input type="checkbox" name="offsite_forms" value="true" <?php if ($settings['offsite_forms']) { ?>checked="checked" <?php } ?>/> <?php _e('Allow form postings from other web sites (required for OpenID; increases spam received)'); ?></label></td></tr>
	</table>

	<h3><?php _e('http:BL'); ?></h3>
	<p>To use Bad Behavior's http:BL features you must have an <a href="http://www.projecthoneypot.org/httpbl_configure.php?rf=24694">http:BL Access Key</a>.</p>
	<table class="form-table">
	<tr><td><label><input type="text" size="12" maxlength="12" name="httpbl_key" value="<?php echo $settings['httpbl_key']; ?>" /> http:BL Access Key</label></td></tr>
	<tr><td><label><input type="text" size="3" maxlength="3" name="httpbl_threat" value="<?php echo $settings['httpbl_threat']; ?>" /> Minimum Threat Level (25 is recommended)</label></td></tr>
	<tr><td><label><input type="text" size="3" maxlength="3" name="httpbl_maxage" value="<?php echo $settings['httpbl_maxage']; ?>" /> Maximum Age of Data (30 is recommended)</label></td></tr>
	</table>

	<p class="submit"><input class="button" type="submit" name="submit" value="<?php _e('Update &raquo;'); ?>" /></p>
	</form>
	</div>
<?php
}

add_action('admin_menu', 'bb2_admin_pages');

function bb2_plugin_action_links($links, $file) {
	if ($file == "bad-behavior/bad-behavior-wordpress.php" && function_exists("admin_url")) {
		$log_link = '<a href="' . admin_url("tools.php?page=bb2_manage") . '">Log</a>';
		$settings_link = '<a href="' . admin_url("options-general.php?page=bb2_options") . '">Settings</a>';
		array_unshift($links, $settings_link, $log_link);
	}
	return $links;
}
add_filter("plugin_action_links", "bb2_plugin_action_links", 10, 2);

?>
