<!DOCTYPE html>
<html lang="en">
<head>

    <title>Bobby's Hobbies</title>
    <meta charset="utf-8">
    <meta name="author" content="Zachary Kiefer Steve Carlson">
    <meta name="description" content="Bobbie's Hobbies">
    <link rel="stylesheet" href="lib/custom.css" type="text/css">
    <link rel="stylesheet" media="(max-width: 648px)" href="lib/custom-tablet.css" type="text/css">
    <link rel="stylesheet" media="(max-width: 500px)" href="lib/custom-phone.css" type="text/css">
    <link rel="icon" href="images/tabIcon.png">
</head>
<?php
//~~~~~~~~~~~~~~~~Including statments~~~~~~~~~~~~~~~~~~~~~~~~~~
print '<!-- Including Libraries -->';

include 'lib/constants.php';

include LIB_PATH . '/Connect-With-Database.php';

include LIB_PATH . '/functions.php';

print '<!-- Libraries Included -->';
//~~~~~~~~~~~~~~~~Session~~~~~~~~~~~~~~~~~~~~~~~~~~
print '<!-- Starting sessions -->';
session_start();

@$_SESSION["loggedIn"];
@$_SESSION["firstName"];
@$_SESSION["lastName"];
@$_SESSION["email"];
@$_SESSION["adminAccess"];
@$_SESSION["userId"];

if (DEBUG) {
    print '<p>Contents of the array Session<pre>';
    print_r($_SESSION);
    print '</pre></p>';
}
print '<!-- Session started -->';
//~~~~~~~~~~~~~~~~Body Tag~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

print '<body id="' . PATH_PARTS['filename'] . '">';
include 'header.php';
include 'nav.php';
?>
