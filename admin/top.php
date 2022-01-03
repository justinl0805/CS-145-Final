<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="author" content="Justin Lai">
        <meta name="description" content="The top file for the website.">
        <title>Top</title>

        <link rel="stylesheet" href="../css/custom.css?version=<?php print time(); ?>" type="text/css">    
        <link rel="stylesheet" media="(max-width:800px)" 
        href="../css/tablet.css?version=<?php print time(); ?>" type="text/css">
        <link rel="stylesheet" media="(max-width:600px)" 
        href="../css/phone.css?version=<?php print time(); ?>" type="text/css"> 
<!-- **** include libraries **** -->
<?php 
$path = 'lib/';

if (substr('//'. $_SERVER['SERVER_NAME'] . pathinfo($_SERVER['PHP_SELF'])['dirname'] . '/', -6) == 'admin/'){
    $path = '../' . $path;
}

// To save user login
session_start();

include $path . 'constants.php';

print '<!-- make Database connections -->';

$path = 'lib/';

if (substr(BASE_PATH, -6) == 'admin/'){
    $path = '../' . $path;
}

require_once($path . 'Database.php');
// create constants for these in lab 3
$thisDatabaseReader = new Database('jtlai_reader', 'r', DATABASE_NAME);
$thisDatabaseWriter = new Database('jtlai_writer', 'w', DATABASE_NAME);
    
$netId = htmlentities($_SERVER["REMOTE_USER"], ENT_QUOTES, "UTF-8");
$sql = 'SELECT pmkNetId FROM tblAdminNetId ';
$sql .= 'WHERE pmkNetId = ?';

$data = array();
$data[] = $netId;

$check = $thisDatabaseReader->select($sql, $data);

if(empty($check)){
    die;
}

print '</head>';

print '<body id="' . PATH_PARTS['filename'] . '">';
print '<!-- ***** START OF BODY **** -->';

print PHP_EOL;

include './header.php';
print PHP_EOL;

include './nav.php';
print PHP_EOL;

?>