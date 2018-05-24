<!DOCTYPE html>
<html lang="en">
	<head>
		<title>Test WarningNote</title>
		<style>
			#warningNote { background-color: #ffffaa; padding: .1rem 1.5rem; text-align: center; }
			ul { list-style: none; }
			li.ok:before {content: " ✔ "; color: green; }
			li.check:before {content: " ❓ "; color: orange; }
			li.bad:before {content: " ✘ "; color: red; }
		</style>
	</head>
	<body>
		<h1>Check WarningNote setup</h1>
<?php
	require_once('status.php');
	$github = "gitHub";
	
echo "<h3>Are all files present?</h3>\n";
	echo "		<ul>\n";
	$files = array('cache.php', 'config.php', 'status.php', 'warningnote.js.php');
	foreach ($files as $f) {
		if (file_exists($f)) {
			echo "		<li class='ok'><strong>".$f.":</strong> present and accounted for.</li>\n";
		} else {
			echo "		<li class='bad'><strong>".$f.":</strong> missing: try downloading it from <a href='$github'>$github</a>.</li>\n";
		}
	}
	echo "		</ul>\n";

echo "<h3>Is your configuration correct?</h3>\n";
	echo "		<p>Check these settings and make any edits necessary in config.php.</p>\n";
	echo "		<ul>\n";
	if (isset($alma) && $alma!="") {
		echo "		<li class='check'>Your <strong>Alma instance</strong> is recorded as: $alma</li>\n";
	} else {
		echo "		<li class='bad'>Your <strong>Alma instance</strong> is not defined.</li>\n";
	}
	if (isset($primo) && $primo!="") {
		echo "		<li class='check'>Your <strong>Primo instance</strong> is recorded as: $primo</li>\n";
	} else {
		echo "		<li class='bad'>Your <strong>Primo instance</strong> is not defined.</li>\n";
	}
	if ($use_cached_data) {
		echo "		<li class='ok'>You <strong>are</strong> using the caching functionality to avoid overloading the API.</li>\n";
		echo "		<li class='check'>API data will be cached in the \"<strong>$cache_folder</strong>\" folder for <strong>$cache_seconds seconds</strong> before a new copy is downloaded.</li>\n";
	} else {
		echo "		<li class='bad'>You <strong>are not</strong> using the caching functionality. It's recommended to change \$use_cached_data to <em>true</em> to avoid overloading the API, especially if your site is heavily used.</li>\n";		
	}
	if (file_exists($manual_note)) {
		echo "		<li class='ok'>";
	} else {
		echo "		<li class='bad'>";		
	}
	echo "You can add a <strong>manual note</strong> to the file: $manual_note. Currently this file ";
	if (file_exists($manual_note)) {
		$handle = fopen($manual_note, "r");
		echo "contains the text: \"" . fread($handle, filesize($manual_note)) . "\"";
		fclose($handle);
	} else {
		echo "does not exist.";
	}
	echo "</li>\n";
	echo "		<li class='check'><strong>Automated statuses</strong> will be described as: \"".$readable_status['PERF']."\", \"".$readable_status['ERROR']."\", or \"".$readable_status['MAINT']."\" (with some elaboration per warningnote.js.php).</li>\n";
	echo "		<li class='check'>Automated warnings will <strong>conclude</strong> with: \"$auto_apology\"</li>\n";
	echo "		</ul>\n";


echo "<h3>Can you communicate with other websites?</h3>\n";
	$test_curl_url = "https://httpbin.org/post";
	function testPost() {
		global $test_curl_url, $curlopt_ssl_verifypeer;
		$test_curl_data = "Hello=world";
		$ch = curl_init($test_curl_url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/x-www-form-urlencoded'));
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $test_curl_data);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, $curlopt_ssl_verifypeer);
		$result = curl_exec($ch);
		curl_close($ch);
		return $result;
	}
	$result = testPost();
	echo "		<ul>\n";
	if ($result && json_decode($result)->form->Hello == "world") {
		echo "		<li class='ok'>You can <strong>post data</strong> to other websites (or at least to <a href='$test_curl_url'>$test_curl_url</a>) and receive the response.</li>\n";
	} else {
		echo "		<li class='bad'>You <strong>can't post data</strong> successfully to other websites and receive the response - or at least to <a href='$test_curl_url'>$test_curl_url</a>. Firstly check this test service still exists. :-) If it does, then check your php config is set up to be able to make cURL calls and to be able to cope with SSL. If you can't store certificates locally you can always set the config parameter \$curlopt_ssl_verifypeer to <em>false</em>: this is not generally good security but in this context is probably fairly low-risk.</li>\n";
	}
	echo "		</ul>\n";

echo "<h3>Is caching working?</h3>\n";
	echo "		<ul>\n";
	if ($use_cached_data) {
		$result = testPost();
		if (createCache($use_cached_data, $result)) {
			echo "		<li class='ok'>You can <strong>cache</strong> a file.</li>\n";
		} else {
			echo "		<li class='bad'>You <strong>can't cache</strong> a file. Check that your cache folder exists and that it has appropriate read/write permissions. (This error also shows when you haven't successfully communicated with the website in the step above.)</li>\n";			
		}
		if (json_decode(checkCache($use_cached_data))->form->Hello == "world") {
			echo "		<li class='ok'>You can <strong>retrieve</strong> a cached file.</li>\n";
		} else {
			echo "		<li class='bad'>You <strong>can't retrieve</strong> a cached file. Check that your cache folder exists and that files are being cached to it. (This error also shows when you haven't successfully communicated with the website in the step above.)</li>\n";			
		}
	} else {
		echo "		<li class='bad'>You <strong>are not</strong> using the caching functionality. It's recommended to change \$use_cached_data to <em>true</em> to avoid overloading the API, especially if your site is heavily used.</li>\n";				
	}
	echo "		</ul>\n";

echo "<h3>Are you getting data from the Ex Libris API?</h3>\n";
	echo "		<ul>\n";
	if ($status) {
		echo "		<li class='check'>The API is returning data for: <ul>";
		foreach ($status->instance as $i) {
			echo "<li class='ok'>" . $i->attributes()->service . "</li>";
		}
		echo "</ul></li>\n";
	} else {
		echo "		<li class='bad'>No response is being returned from the API at <strong>$url</strong> with data <strong>act=get_status&client=xml&envs=".$envs."</strong>. Check that you can communicate with other websites and that your config.php file lists your Alma and Primo instances correctly.</li>\n";
	}
	echo "		</ul>\n";

echo "<h3>Are you getting data for your own instances?</h3>\n";
	echo "		<p>Compare these results for accuracy against the <a href='http://status.exlibrisgroup.com/'>Ex Libris System Status</a> page.</p>\n";
	echo "		<ul>\n";
	echo "		<li class='check'>Your <strong>Alma status</strong> currently shows as: $readable_status[$status_A]</li>\n";
	echo "		<li class='check'>Your <strong>Alma note</strong> currently shows as: $note_A</li>\n";
	echo "		<li class='check'>Your <strong>Primo status</strong> currently shows as: $readable_status[$status_P]</li>\n";
	echo "		<li class='check'>Your <strong>Primo note</strong> currently shows as: $note_P</li>\n";
	echo "		</ul>\n";

echo "<h3>Does it display in the right place?</h3>\n";
?>
		<prm-search-bar>
			<ul>
			<li class='check'>If any warning note is current, it will display in a yellow bar below as it would beneath the Primo search bar.
				<ul>
				<li class='check'>Any manual note will take priority.</li>
				<li class='check'>If <? echo $manual_note; ?> is empty, an automated note will appear if either Alma and/or Primo have a status of "<? echo $readable_status['PERF']; ?>", "<? echo $readable_status['ERROR']; ?>", or "<? echo $readable_status['MAINT']; ?>".</li>
				<li class='check'>If both Alma and Primo have a status of "<? echo $readable_status['OK']; ?>" or "<? echo $readable_status['SERVICE']; ?>", no message (or yellow bar) will appear.</li></ul>
			</li>
			</ul>
		</prm-search-bar>
		
		<script type="text/javascript" src="warningnote.js.php"></script>
	</body>
</html>