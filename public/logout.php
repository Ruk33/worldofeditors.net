<?php

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	// 5 days
	ini_set('session.gc_maxlifetime', 432000);
	session_set_cookie_params(432000);
	session_start();

	include "../include/env.php";
	include "../include/discord.php";

	discord_logout();
}

?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>WorldOfEditors - Logout</title>
	<meta http-equiv="refresh" content="0; url=/jugar.php" />
</head>
<body>
	Redireccionando a WorldOfEditors!
</body>
</html>
