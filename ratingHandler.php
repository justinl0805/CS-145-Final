<?php
include 'lib/constants.php';
require_once(LIB_PATH . '/Database.php');

// Database Constants
$thisDatabaseReader = new Database('jtlai_reader', 'r', DATABASE_NAME);
$thisDatabaseWriter = new Database('jtlai_writer', 'w', DATABASE_NAME);

$type = (isset($_GET['type'])) ? (int) htmlspecialchars($_GET['type']) : 0;
$id = (isset($_GET['id'])) ? (int) htmlspecialchars($_GET['id']) : 0;
$postType = (isset($_GET['postType'])) ? (int) htmlspecialchars($_GET['postType']) : 0;

// Select the value from the database
if ($postType === 1) {
    $sql = 'SELECT fldRating FROM tblComments WHERE pmkCommentId = ?';
}else{
    $sql = 'SELECT fldRating FROM tblPosts WHERE pmkPostId = ?';
}

$data = array();
$data[] = $id;

$getCommentRatings = $thisDatabaseReader->select($sql, $data);
$commentRating = 0;
if (is_array($getCommentRatings)) {
    foreach ($getCommentRatings as $getCommentRating) {
        $commentRating = $getCommentRating['fldRating'];
    }
}

// Upvote button clicked
if ($type === 1) {
    $commentRating++;
}else{ // Downvote Button clicked
    $commentRating--;
}

if ($postType == 1) {
    $sql = 'UPDATE tblComments SET fldRating = ? WHERE pmkCommentId = ?';
}else{
    $sql = 'UPDATE tblPosts SET fldRating = ? WHERE pmkPostId = ?';
}

$data = array();
$data[] = $commentRating;
$data[] = $id;

// Update the database
$thisDatabaseWriter->update($sql, $data);

// Select the updated information from the database
/*$sql2 = 'SELECT fldRating FROM tblComments WHERE pmkCommentId = ?';
$data2 = array();
$data2 = $id;*/

//$updated = $thisDatabaseReader->select($sql, $data);

// responseText returns out correctly
//echo "out";

// Test Query:
// SELECT fldRating FROM tblComments WHERE pmkCommentId = 20 (returns a value in MySQL DB)

// This doesn't get the proper value
/*$getCommentRatings = $thisDatabaseReader->select($sql2, $data2);
$commentRating = 0;
if (is_array($getCommentRatings)) {
    foreach ($getCommentRatings as $getCommentRating) {
        $commentRating = $getCommentRating['fldRating'];
    }
}*/ 

// Return the value we selected and incremented in the first place
echo $commentRating;
?>