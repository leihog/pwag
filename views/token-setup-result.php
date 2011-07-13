<html>
<body>

Token was successfully recieved from Google. <br />
Edit config.php and change the row <br />
define('AUTH_SUB_TOKEN', 'secret...'); <br />
into <br />
define('AUTH_SUB_TOKEN', '<?php echo $this->AuthSubToken; ?>'); <br />

PWAG has now been configured and should work flawlessly.

</body>
</html>
