<?php
include './top.php';
if (isset($_POST['report'])) {
    if ($_POST['report'] == "userPosts") {
        $sql = 'SELECT fldUsername, COUNT(pmkPostId) AS fldPosts FROM tblUsers ';
        $sql .= 'LEFT JOIN tblPosts on tblUsers.pmkUserId = tblPosts.fpkUserId ';
        $sql .= 'GROUP BY pmkUserId ';
        $sql .= 'ORDER BY fldPosts DESC';        
    }

    if ($_POST['report'] == "userComments") {
        $sql = 'SELECT fldUsername, COUNT(pmkCommentId) AS fldComments FROM tblUsers ';
        $sql .= 'LEFT JOIN tblComments on tblUsers.pmkUserId = tblComments.fpkUserId ';
        $sql .= 'GROUP BY pmkUserId ';
        $sql .= 'ORDER BY fldComments DESC';
    }

    if (!empty($_POST['report'])) {
        $data = '';
        $userQueryCounts = $thisDatabaseReader->select($sql, $data);
    }
}


?>
<main>
    <h1>User Reports</h1>
    <form method="POST">
    <fieldset>
        <label>Get Report: </label>
        <select name="report" onchange="this.form.submit()">
            <option value="">None</option>
            <option value="userPosts" <?php 
            if (isset($_POST['report'])) {
                print($_POST['report'] == "userPosts" ? "selected" : ""); 
            }
            ?>>Number of Posts for Each User</option>
            <option value="userComments" <?php 
            if (isset($_POST['report'])) {
                print($_POST['report'] == "userComments" ? "selected" : ""); 
            }    
            ?>>Number of Comments for Each User</option>
        </select>
    </fieldset>
    </form>
    <?php
    if (isset($_POST['report'])) {
        if ($_POST['report'] == "userPosts") {
            print '<h3>Number of Posts for Each User</h3>';
        }

        if ($_POST['report'] == "userComments") {
            print '<h3>Number of Comments for Each User</h3>';
        }

        print '<ol class="adminReport">';
        if (!empty($_POST['report'])) {
            if (is_array($userQueryCounts)) {
                foreach ($userQueryCounts as $userQueryCount) {
                    print('<li><b>Username: </b>' . $userQueryCount['fldUsername'] . ''); 
                    if ($_POST['report'] == "userPosts") {
                        print('<ul><li><b>Posts: </b>' . $userQueryCount['fldPosts'] . '</li></ul></li>');
                    }

                    if ($_POST['report'] == "userComments") {
                        print('<ul><li><b>Comments: </b>' . $userQueryCount['fldComments'] . '</li></ul></li>');
                    }
                }
            }
        }
        print '</ol>';
    }
    ?>
</main>
