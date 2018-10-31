<?php
	require_once('status.php');
	$handle = fopen($manual_note, "r");
	$warning = fread($handle, filesize($manual_note));
	fclose($handle);
?>
theAlert = "<?php echo $warning; ?>";

function warningNote(warningText) {
	var warningPlace = document.getElementsByTagName("prm-search-bar")[0];
	var warningNote = document.createElement("div");
	warningNote.id = "warningNote";
	warningNote.className = "bar alert-bar layout-align-center-center layout-row";
	warningNote.innerHTML = warningText;
	warningPlace.parentNode.insertBefore(warningNote,warningPlace.nextSibling);
}

status_A = '<?php echo $status_A; ?>';
note_A = '<?php echo $note_A; ?>';
status_P = '<?php echo $status_P; ?>';
note_P = '<?php echo $note_P; ?>';

if (theAlert == "") {
	if (status_A == "PERF" || status_A == "ERROR" || status_A == "MAINT") {
		theAlert = theAlert + 'Automated note: <strong>' + '<?php echo $readable_status[$status_A]; ?>' + '</strong>';
		if (status_A == "MAINT") {
			theAlert = theAlert + ' in progress';
		} else {
			theAlert = theAlert + ' detected';
		}
		theAlert = theAlert + '. Some functions (eg requests, renewals, availability statuses, and/or links to full-text) may be ';
		if (status_A == "PERF") {
			theAlert = theAlert + 'slow';
		} else {
			theAlert = theAlert + 'temporarily unavailable';
		}
	}
	if (status_P == "PERF" || status_P == "ERROR" || status_P == "MAINT") {
		theAlert = theAlert + 'Automated note: <strong>' + '<?php echo $readable_status[$status_P]; ?>' + '</strong>';
		if (status_P == "MAINT") {
			theAlert = theAlert + ' in progress';
		} else {
			theAlert = theAlert + ' detected';
		}
		theAlert = theAlert + '. Some functions (especially search-related) may be ';
		if (status_P == "PERF") {
			theAlert = theAlert + 'slow';
		} else {
			theAlert = theAlert + 'temporarily unavailable';
		}
	}
	if (theAlert != "") {
		theAlert = theAlert + '.<br/>' + "<?php echo $auto_apology; ?>";
	}
}

if (theAlert != "") {
	warningNote("<p>"+theAlert+"</p>");
}