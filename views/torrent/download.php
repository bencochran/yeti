<?php
if (empty($active_user) || empty($torrent))
	throw new Lvc_Exception('Download requested but I didn\'t have a user or torrent!');
buffer_end_clean();
// Set headers
header('Content-Type: application/x-bittorrent');
header('Content-Disposition: attachment; filename="' . $torrent->path . '"');
header('Content-Transfer-Encoding: binary');
header('Pragma: no-cache');
header('Expires: 0');

print $torrent->unique_for(array('user' => $active_user)); 
die();
?>
