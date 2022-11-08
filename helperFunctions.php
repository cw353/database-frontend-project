<?php
	/* sanitize a string for use in mysql */
	function sanitizeMysql(string $input) {
			return mysql_real_escape_string($input);	
	}

	/* sanitize a string for use in html */
	function sanitizeHtml(string $input) {
			return htmlspecialchars($input);	
	}
?>
