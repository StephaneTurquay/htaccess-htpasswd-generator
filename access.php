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
		
			if (file_put_contents('.htaccess', $htaccess_content, FILE_APPEND)) {

				echo '<p style="color:green;">.htaccess was existing and settings added.</p>';
			}
			else {

				echo '<p style="color:red;">Error: settings has not been added to .htaccess</p>';
			}
			
		}
		
	} else {
	
		if (file_put_contents('.htaccess', $htaccess_content, FILE_APPEND)) {

			echo '<p style="color:green;">.htaccess has been created and settings added.</p>';
		}
		else {

			echo '<p style="color:red;">Error: .htaccess has not been created and/or settings added.</p>';
		}
	}

	if (file_exists('.htpasswd')) {
	
		$htpasswd = file_get_contents('.htpasswd');
		
		if (strpos($htpasswd, $username) === false) {
	    	
	    	if (file_put_contents('.htpasswd', ' 
'. $password, FILE_APPEND)) {

	    		echo '<p style="color:green;">.htpasswd was existing and password added.</p>';

	    	} else {

	    		echo '<p style="color:red;">Error: password has not been added to .htpasswd</p>';
	    	}
		}
	
	} else {
		
		if (file_put_contents('.htpasswd', $password, FILE_APPEND)) {

			echo '<p style="color:green;">.htpasswd has been create and password added</p>';
		
		} else {

			echo '<p style="color:red;">Error: .htpasswd has not been create and/or password has not been added.</p>';
		}
	}

	if (unlink(__FILE__)) {
		
		echo '<p style="color:green;">access.php has been deleted.</p>';
	}
	else {
		
		echo '<p style="color:red;">Error: access.php has not been deleted.</p>';
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