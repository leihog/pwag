<html>
<body>

Token was successfully recieved from Google. <br />
Edit index.php and change the row <br />
define('AUTH_SUB_TOKEN', ''); <br />
into <br />
define('AUTH_SUB_TOKEN', '<?php echo $this->AuthSubToken; ?>'); <br />

PWAG has now been configured and should work flawlessly.

</body>
</html>