<?php 

if (!empty($_POST['username']) && !empty($_POST['password'])) {
	
	$username = filter_var(trim($_POST['username']), FILTER_SANITIZE_STRING);
	$password = filter_var(trim($_POST['password']), FILTER_SANITIZE_STRING);
	$password = crypt($password, base64_encode($password));
	$password = $username . ':' . $password;


	$htaccess_content = '
AuthName "Protected Access"
AuthType Basic
AuthUserFile "' . dirname(__FILE__) . '/.htpasswd"
Require valid-user';
		
	if (file_exists('.htaccess')) {
	
		$htaccess = file_get_contents('.htaccess');
	
		if (strpos($htaccess,'AuthUserFile') === false) {
		
			file_put_contents('.htaccess', $htaccess_content, FILE_APPEND);
			
		}
		
	} else {
	
		file_put_contents('.htaccess', $htaccess_content, FILE_APPEND);
	}

	if (file_exists('.htpasswd')) {
	
		$htpasswd = file_get_contents('.htpasswd');
		
		if (strpos($htpasswd, $username) === false) {
	    	
	    	file_put_contents('.htpasswd', ' 
'. $password, FILE_APPEND);
		}
	
	} else {
		
		file_put_contents('.htpasswd', $password, FILE_APPEND);
	}

	if (unlink(__FILE__)) {
		
		echo '.htaccess & .htpasswd has been created and access.php deleted.';
	}
	else {
		
		echo 'Error: access.php has not been deleted. Please verify your htaccess and htpasswd';
	}

}
else {

?>
<html>
	<head>
		<title>.htaccess Generator</title>
	</head>
	<body>
		<form method="post">
			<input type="text" name="username" placeholder="Username" />
			<input type="password" name="password" placeholder="Password" />
			<input type="submit" value="Generate" />
		</form>
	</body>
</html>
<?php
}
?>