<?php
	function createCache($url,$content) {
		global $cache_folder;
		$filename = md5($url);
		$filepath = $cache_folder . $filename;
		$handle = fopen($filepath, "w");
		$success = fwrite($handle, $content);
		fclose($handle);
		return $success;
	}

	function checkCache($url) {
		global $cache_folder, $cache_seconds;
		$filename = md5($url);
		$filepath = $cache_folder . $filename;
		if (!file_exists($filepath)) {
			return false;
		} else {
			$timestamp = filemtime($filepath);
			if ($timestamp + $cache_seconds > time()) {
				$content = getCache($url);
				return $content;
			} else {
				return false;
			}
		}
	}
	
	function getCache($url) {
		global $cache_folder;
		$filename = md5($url);
		$filepath = $cache_folder . $filename;
		$handle = fopen($filepath, "r");
		$content = fread($handle, filesize($filepath));
		fclose($handle);
		return $content;
	}
?>