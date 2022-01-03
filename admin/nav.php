<nav class="navbar">
    <a class="<?php 
    if (PATH_PARTS['filename'] == "index"){
        print'activePage';
    }
    ?>" href="../index.php">Exit</a>

    <a class="<?php
    if (PATH_PARTS['filename'] == "reports"){
        print'activePage';
    }
    ?>" href="./reports.php">Reports</a>

    <a class="<?php
    if (PATH_PARTS['filename'] == "addRecord"){
        print'activePage';
    }
    ?>" href="./addRecord.php">Insert</a>

    <a class="<?php
    if (PATH_PARTS['filename'] == "updateRecord"){
        print'activePage';
    }
    ?>" href="./updateRecord.php">Update</a>

    <a class="<?php
    if (PATH_PARTS['filename'] == "deleteRecord"){
        print'activePage';
    }
    ?>" href="./deleteRecord.php">Delete</a>
</nav>