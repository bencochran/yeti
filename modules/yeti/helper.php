<?php

/**
 * sanitize_body_text
 *
 * @param string $text 
 * @return string
 * @author Ben Cochran
 */
function sanitize_body_text($text)
{
	$text = trim($text);
	$text = str_replace("\r\n","\n",$text);
	$text = str_replace("\r","",$text);
	$text = strip_tags($text, "<br><a><strong><em>");
	$text = str_replace("\n","",nl2br($text));
	// Limit consecutive <br />s to two.
    $text = preg_replace('/(\<br(\s*)?\/?\>){3,}/i', "<br /><br />", $text);
	
	return $text;
}

function sanitize_line_text($text)
{
	return $text;
}

// Opposite of nl2br
function br2nl($string) {
    return preg_replace('/\<br(\s*)?\/?\>/i', "\n", $string);
}

function time_diff($time, $opt = array()) {
	// The default values
	$defOptions = array(
		'to' => 0,
		'parts' => 1,
		'precision' => 'second',
		'distance' => TRUE,
		'separator' => ', '
	);
	$opt = array_merge($defOptions, $opt);
	// Default to current time if no to point is given
	(!$opt['to']) && ($opt['to'] = time());
	// Init an empty string
	$str = '';
	// To or From computation
	$diff = ($opt['to'] > $time) ? $opt['to']-$time : $time-$opt['to'];
	// An array of label => periods of seconds;
	$periods = array(
		'decade' => 315569260,
		'year' => 31556926,
		'month' => 2629744,
		'week' => 604800,
		'day' => 86400,
		'hour' => 3600,
		'minute' => 60,
		'second' => 1
	);
	// Round to precision
	if ($opt['precision'] != 'second')
		$diff = round(($diff/$periods[$opt['precision']])) * $periods[$opt['precision']];
	// Report the value is 'less than 1 ' precision period away
	(0 == $diff) && ($str = 'less than 1 '.$opt['precision']);
	// Loop over each period
	foreach ($periods as $label => $value) {
	// Stitch together the time difference string
		(($x=floor($diff/$value))&&$opt['parts']--) && $str.=($str?$opt['separator']:'').($x.' '.$label.($x>1?'s':''));
		// Stop processing if no more parts are going to be reported.
		if ($opt['parts'] == 0 || $label == $opt['precision']) break;
		// Get ready for the next pass
		$diff -= $x*$value;
	}
	$opt['distance'] && $str.=($str&&$opt['to']>=$time)?' ago':' away';
	return $str;
}

function pretty_size($bytes) {
	$base = 1024;
	$idx = 0;
	$suffixes = array('bytes', 'KB', 'MB', 'GB', 'TB', 'PB');

	while($bytes > $base) {
		$bytes = round($bytes / $base, 1);
		$idx += 1;
	}

	return $bytes . ' ' . $suffixes[$idx];
}

function flash_status($key, $value) {
	$_SESSION['flash']['status'][$key] = $value;
}

function flash_form($key, $value) {
	$_SESSION['flash']['form'][$key] = $value;
}

function buffer_end_clean()
{
	while (ob_get_level() > 0) ob_end_clean();
}

function buffer_end_flush()
{
	while (ob_get_level() > 0) ob_end_flush();
}

function require_active_user()
{
	static $user;
	if (empty($user))
	{
		if(isset($_SESSION['user_uid']))
		{
			$user = User::find(array('uid' => $_SESSION['user_uid'], 'include_hidden' => true));
		}
		if (empty($user))
		{
			buffer_end_clean();
			$_SESSION['flash']['referrer'] = $_SERVER['REQUEST_URI'];
			header('Location: /user/login');
			die();
		}
	}
	return $user;
}

function get_active_user()
{
	static $user;
	if (empty($user))
	{
		if(isset($_SESSION['user_uid']))
		{
			$user = User::find(array('uid' => $_SESSION['user_uid'], 'include_hidden' => true));
		}
		if (empty($user))
		{
			return false;
		}
	}
	return $user;
}


function view_build_tab($cur, $new, $url, $title, $req_login) {
	global $base_url;

	$active = (($cur == $new) and !($req_login and !User::logged_in()));
	$classes = array();

	if($active) {
		array_push($classes, 'active');
	}
	if($req_login and !User::logged_in()) {
		array_push($classes, 'disabled');
	}

	if(($req_login and User::logged_in()) or (!$req_login)) {
		$contents = '<a href="' . $base_url . $url . '" title="' . $title . '"';
		$contents .= '>' . $new . '</a>';
	} else {
		$contents = $new;
	}

	$tab = '<li';
	if(count($classes) > 0) {
		$classes = implode($classes, ' ');
		$tab .= ' class="' . $classes . '">';
	} else {
		$tab .= '>';
	}

	$tab .= $contents . '</li>';

	return $tab;
}
?>
