<?php

//EDIT...

	define("HOST","localhost");
	define("USER","root");
	define("PASS","");
	define("DATABASE","avatares");
	define("TABLE","avatar");
	define("AVATARFIELD","json");
	define("UNLOCKEDFIELD","json2");
	define("IDFIELD","usuario");

	//estas variables deben ser asignadas según el usuario de la sesión activa
	$userid = 1;
	$usersex = 1;// 1=M; 2=F;
	$usercoins = 100;
	$userImage = "userimages/$userid.png";

//...EDIT

include("avatartasks.php");
?>