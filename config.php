<?php
$alma = ""; // eg "Alma AP01";
$primo = ""; // eg "Primo MT APAC01";
$use_cached_data = true;
$cache_folder = 'cache/';
$cache_seconds = 1 * 60;
$manual_note = "warningnote.txt";

$curlopt_ssl_verifypeer = true;

$readable_status = array(
	'OK' => 'Operating normally',
	'PERF' => 'Performance issues',
	'ERROR' => 'Service disruption',
	'MAINT' => 'Scheduled maintenance',
	'SERVICE' => 'Operating normally but note: ',
);

$auto_apology = "Full service will be restored as soon as possible. In the meantime you may wish to search directly through our databases. We apologise for the inconvenience."; //remember to escape any double-quotes eg \"
?>