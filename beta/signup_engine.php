<?php

session_start();

// Validation

if (isset($_POST['first_name'])) {
	$FirstName = filter_input(INPUT_POST, 'first_name', FILTER_SANITIZE_STRING);
}

if (isset($_POST['last_name'])) {
	$LastName = filter_input(INPUT_POST, 'last_name', FILTER_SANITIZE_STRING);
}

if (isset($_POST['email'])) {
	$Email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
}

if (isset($_POST['software'])) {
	$Software = filter_input(INPUT_POST, 'software', FILTER_SANITIZE_STRING);

	if ($Software == "SwifTube") {
		$Identifier = "com.mattbarker.SwifTubeiMessage";
	} 

	else {
		$Identifier= "";
	}

}

$NameValidation = isset($FirstName) && isset($LastName);
$EmailValidation = isset($Email) && filter_var($Email, FILTER_VALIDATE_EMAIL);
$SoftwareValidation = isset($Software) && $Identifier != "";
$Validation = $NameValidation && $EmailValidation && $SoftwareValidation;

if (!$Validation) {
	print "<meta http-equiv=\"refresh\" content=\"0;URL=signup_failure.html\">";
  	exit;
}

// Prepare Email

$EmailTo = "mattbarker016@gmail.com";

$Subject = "[".$Software."] Beta Tester Request";

$Body = "Name: ".$FirstName." ".$LastName."\n";
$Body .= "Email: ".$Email."\n\n";
$Body .= "Fastlane Command\n\n";

$Fastlane  = "fastlane pilot add ".$Email;
$Fastlane .= " -f ".$FirstName;
$Fastlane .= " -l ".$LastName;
$Fastlane .= " -a ".$Identifier;
$Fastlane .= " -g 'Beta Tester Group 1'";

$Body .= $Fastlane;

// Send Email 

$success = mail($EmailTo, $Subject, $Body);

// Redirect

if ($success) {
  	print "<meta http-equiv=\"refresh\" content=\"0;URL=signup_success.html\">";
} else {
  	print "<meta http-equiv=\"refresh\" content=\"0;URL=signup_failure.html\">";
}

?>