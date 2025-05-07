<?php
$sessionGrants = true; //false = GJP check is done every time; true = GJP check is done once per hour; significantly improves performance, slightly descreases security
$unregisteredSubmissions = false; //false = green accounts can't upload levels, appear on the leaderboards etc; true = green accounts can do everything
$preactivateAccounts = true; //false = acounts need to be activated at tools/account/activateAccount.php; true = accounts can log in immediately

/*
	Captcha settings
	Currently the only supported provider is hCaptcha
	https://www.hcaptcha.com/
*/
$enableCaptcha = true;
$hCaptchaKey = "13efb4dd-747d-4860-b473-6ec6ec838bf5";
$hCaptchaSecret = "0x272CAf56F7aB0747f1aeCbA9dA51D746046dC7D1";
