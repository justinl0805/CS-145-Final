<?php
include 'top.php';
if (isset($_POST['sort'])) {
    if ($_POST['sort'] == "newest") {
        $sql = 'SELECT pmkPostId, fpkUserId, fldPostTitle, fldContent, fldRating, fldType, fldDate FROM tblPosts ';
        $sql .= 'ORDER BY fldDate DESC';
    }elseif ($_POST['sort'] == "oldest") {
        $sql = 'SELECT pmkPostId, fpkUserId, fldPostTitle, fldContent, fldRating, fldType, fldDate FROM tblPosts ';
        $sql .= 'ORDER BY fldDate ASC';
    }elseif ($_POST['sort'] == "rating") {
        $sql = 'SELECT pmkPostId, fpkUserId, fldPostTitle, fldContent, fldRating, fldType, fldDate FROM tblPosts ';
        $sql .= 'ORDER BY fldRating DESC';
    }elseif ($_POST['sort'] == "comments") {
        // There is a column conflict when joining the two tables
        // Adding the aliases in the queries fixes that
        $sql = 'SELECT tblPosts.*, COUNT(fpkPostId) AS fldComments ';
        $sql .= 'FROM tblPosts '; 
        $sql .= 'LEFT JOIN tblComments ON tblPosts.pmkPostId = tblComments.fpkPostId ';
        $sql .= 'GROUP BY pmkPostId ';
        $sql .= 'ORDER BY fldComments DESC';
    }else{
        $sql = 'SELECT pmkPostId, fpkUserId, fldPostTitle, fldContent, fldRating, fldType, fldDate FROM tblPosts';
    }
}else{
    $sql = 'SELECT pmkPostId, fpkUserId, fldPostTitle, fldContent, fldRating, fldType, fldDate FROM tblPosts ';
    $sql .= 'ORDER BY fldDate DESC';
}

$data = '';
$posts = $thisDatabaseReader->select($sql, $data);

?>

<main>
    <h2>Posts</h2>
    <form class="sort" method="POST">
        <fieldset>
            <label>Sort by: </label>
            <select name="sort" onchange="this.form.submit()">
                <option value="newest" <?php 
                if (isset($_POST['sort'])) {
                    print($_POST['sort'] == "newest" ? "selected" : ""); 
                }
                ?>>Newest</option>
                <option value="oldest" <?php 
                if (isset($_POST['sort'])) {
                    print($_POST['sort'] == "oldest" ? "selected" : ""); 
                }    
                ?>>Oldest</option>
                <option value="rating" <?php 
                if (isset($_POST['sort'])) {
                    print($_POST['sort'] == "rating" ? "selected" : ""); 
                }    
                ?>>Rating</option>
                <option value="comments" <?php 
                if (isset($_POST['sort'])) {
                    print($_POST['sort'] == "comments" ? "selected" : ""); 
                }    
                ?>>Comments</option>
            </select>
        </fieldset>
    </form>
    <?php
    if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true) {
        print '<figure id="createPostBox">';
        print '<a href="createPosts.php">';
        print '<button id="createPost">Create a Post</button></a>';
        print '</figure>';
    }


    if (is_array($posts)) {
        // Loop once for pinned posts (which we always place at the top)
        foreach ($posts as $post) {
            if ($post['fldType'] == "pinned") {
                print '<a class="postLink" href="displayPost.php?postId=' . $post['pmkPostId'] . '">';
                print '<figure class="postBox_pinned">';

                // SQL query for getting the username
                $sql3 = 'SELECT fldUsername FROM tblUsers WHERE pmkUserId = ?';
                $data = array($post['fpkUserId']);

                $users = $thisDatabaseReader->select($sql3, $data);

                if (is_array($users)) {
                    foreach ($users as $user) {
                        print '<h3>' . $post['fldPostTitle'] . ' by ' . $user['fldUsername'] . ' on ' . $post['fldDate'] . '</h3>';
                    }
                }
                print '<p>' . $post['fldContent'] . '</p>';
                print '<p class="rating_text">Rating: ' . $post['fldRating'] . ' </p>';
                $sql2 = 'SELECT COUNT(*) FROM tblComments ';
                $sql2 .= 'WHERE fpkPostId = ?';

                $data2 = array($post['pmkPostId']);

                $comments = $thisDatabaseReader->select($sql2, $data2);
            
                if (is_array($comments)) {
                    foreach ($comments as $comment) {
                        print '<p>Comments: ' . $comment['COUNT(*)'] . ' </p>';
                    }
                }
                print '</figure>';
                print '</a>';
            }
        }

        // Loop for normal posts sorted by their own query, excludes pinned posts because we already printed them
        foreach ($posts as $post) {
            if ($post['fldType'] == "normal") {
                print '<a class="postLink" href="displayPost.php?postId=' . $post['pmkPostId'] . '">';
                print '<figure class="postBox">';

                // SQL query for getting the username
                $sql3 = 'SELECT fldUsername FROM tblUsers WHERE pmkUserId = ?';
                $data = array($post['fpkUserId']);

                $users = $thisDatabaseReader->select($sql3, $data);

                if (is_array($users)) {
                    foreach ($users as $user) {
                        print '<h3>' . $post['fldPostTitle'] . ' by ' . $user['fldUsername'] . ' on ' . $post['fldDate'] . '</h3>';
                    }
                }
                print '<p>' . $post['fldContent'] . '</p>';
                print '<form class="rate" method="POST">';
                print '<p class="rating_text">Rating: ' . $post['fldRating'] . ' </p>';
                // If user is logged in present buttons that call function, otherwise they redirect to logIn.php
                if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true) {
                    print '<img src="images/uparrow.png" alt="Upvote" class="rateButton" onclick="rateClick(1)" width="12" height="12" />';
                    print '<img src="images/downarrow.png" alt="Downvote" class="rateButton" onclick="rateClick()" width="12" height="12" />';
                }else{
                    print '<img src="images/uparrow.png" alt="Upvote" class="rateButton" onclick="logInRedirect()" width="12" height="12" />';
                    print '<img src="images/downarrow.png" alt="Downvote" class="rateButton" onclick="logInRedirect()" width="12" height="12" />';
                }
                print '</form>';
                $sql2 = 'SELECT COUNT(*) FROM tblComments ';
                $sql2 .= 'WHERE fpkPostId = ?';

                $data2 = array($post['pmkPostId']);

                $comments = $thisDatabaseReader->select($sql2, $data2);
            
                if (is_array($comments)) {
                    foreach ($comments as $comment) {
                        print '<p>Comments: ' . $comment['COUNT(*)'] . ' </p>';
                    }
                }
                
                print '</figure>';
                print '</a>';
            }
        }
    }
    ?>
<?php

print '</main>';

include 'footer.php';
?>