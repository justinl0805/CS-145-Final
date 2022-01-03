<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="author" content="Justin Lai">
        <meta name="description" content="The top file for the website.">
        <title>Top</title>

        <link rel="stylesheet" href="css/custom.css?version=<?php print time(); ?>" type="text/css">
        <link rel="stylesheet" media="(max-width:800px)" 
        href="css/tablet.css?version=<?php print time(); ?>" type="text/css">
        <link rel="stylesheet" media="(max-width:600px)" 
        href="css/phone.css?version=<?php print time(); ?>" type="text/css">

        <link rel="apple-touch-icon" sizes="180x180" href="images/apple-touch-icon.png">
        <link rel="icon" type="image/png" sizes="32x32" href="images/favicon-32x32.png">
        <link rel="icon" type="image/png" sizes="16x16" href="images/favicon-16x16.png">
        <link rel="manifest" href="/site.webmanifest">
        <link rel="mask-icon" href="/safari-pinned-tab.svg" color="#5bbad5">
        <meta name="msapplication-TileColor" content="#da532c">
        <meta name="theme-color" content="#ffffff">     
<?php
include 'lib/constants.php';

print '<!-- make Database connections -->';
require_once(LIB_PATH . '/Database.php');

// Database Constants
$thisDatabaseReader = new Database('jtlai_reader', 'r', DATABASE_NAME);
$thisDatabaseWriter = new Database('jtlai_writer', 'w', DATABASE_NAME);
?>
    <script src="script.js?version=<?php print time(); ?>"></script>
    <script>
        
    </script>
</head>

<?php
print '<body id="' . PATH_PARTS['filename'] . '">';
print '<!-- ***** START OF BODY **** -->';
print PHP_EOL;

session_start();

include 'header.php';
print PHP_EOL;

include 'nav.php';
print PHP_EOL;
?>