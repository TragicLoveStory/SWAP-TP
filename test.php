<?php 
use PragmaRX\Google2FA\Google2FA;
$google2fa = new Google2FA();
$secret = $google2fa->generateSecretKey();
return $google2fa->generateSecretKey();
?>