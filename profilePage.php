<?php
include 'top.php';
if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true) {
    $sql = 'SELECT * FROM tblProfiles WHERE fpkUserId = ?';
    $data = array($_SESSION['id']);
    
    $profile = $thisDatabaseReader->select($sql, $data);
}
?>
<main>
    <h1>Profile</h1>
    <?php
        print_r($profile);
        if (is_array($profile)) {
            foreach ($profile as $profileInfo) {
                print '<p>Created on: '. $profileInfo['fldCreateDate'] . '</p>';
            }
        }
    ?>
</main>
<?php
include 'footer.php';
?>