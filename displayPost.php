<?php
include 'top.php';

$postId = (isset($_GET['postId'])) ? (int) htmlspecialchars($_GET['postId']) : 0;

$sql = 'SELECT pmkPostId, fpkUserId, fldPostTitle, fldContent, fldRating FROM tblPosts ';
$sql .= 'WHERE pmkPostId = ?';

$data = array($postId);
$posts = $thisDatabaseReader->select($sql, $data);

// Sorting queries
if (isset($_POST['sort'])) {
    if ($_POST['sort'] == "newest") {
        $sql2 = 'SELECT pmkCommentId, fldComment, fpkUserId, fldRating, fldDate FROM tblComments ';
        $sql2 .= 'WHERE fpkPostId = ? ';
        $sql2 .= 'ORDER BY fldDate DESC';
    }elseif ($_POST['sort'] == "oldest") {
        $sql2 = 'SELECT pmkCommentId, fldComment, fpkUserId, fldRating, fldDate FROM tblComments ';
        $sql2 .= 'WHERE fpkPostId = ? ';
        $sql2 .= 'ORDER BY fldDate ASC';
    }else{
        $sql2 = 'SELECT pmkCommentId, fldComment, fpkUserId, fldRating, fldDate FROM tblComments ';
        $sql2 .= 'WHERE fpkPostId = ? ';
        $sql2 .= 'ORDER BY fldRating DESC';
    }
}else{
    $sql2 = 'SELECT pmkCommentId, fldComment, fpkUserId, fldRating, fldDate FROM tblComments ';
    $sql2 .= 'WHERE fpkPostId = ? ';
    $sql2 .= 'ORDER BY fldDate DESC';
}

/*$sql2 = 'SELECT pmkCommentId, fldComment, fpkUserId, fldRating, fldDate FROM tblComments ';
$sql2 .= 'WHERE fpkPostId = ?';*/

$comments = $thisDatabaseReader->select($sql2, $data);
$sqlComments = $sql2;

function getData($field) {
    if (!isset($_POST[$field])) {
       $data = "";
    }
    else {
       $data = trim($_POST[$field]);
       $data = htmlspecialchars($data, ENT_QUOTES);
    }
    return $data;
}
?>

<main>
    <?php
        // SQL for updating a post
        if (isset($_POST['saveEditPost'])) {
            $sql = 'UPDATE tblPosts SET fldContent = ? WHERE pmkPostId = ?';
            
            $data = array();
            $data[] = $_POST['editPostArea'];
            $data[] = $_POST['postId'];

            if ($thisDatabaseWriter->update($sql, $data)) {
                //header("Refresh:0");
            }else{
                print('<h2>Comment update failed.</h2>');
            }
        }

        // SQL for deleting a post
        if (isset($_POST['confirmDeletePost'])) {
            $sql = 'DELETE FROM tblPosts WHERE pmkPostId = ?';
            $data = array($_POST['postId']);

            if ($thisDatabaseWriter->delete($sql, $data)) {
                // Redirect the user after the page is deleted
                //header("location: index.php");
            }else{
                print('<h2>Comment delete failed.</h2>');
            }
        }

        if (is_array($posts)) {
            foreach ($posts as $post) {
                // SQL query for getting the username
                $sql3 = 'SELECT fldUsername FROM tblUsers WHERE pmkUserId = ?';
                $data = array($post['fpkUserId']);

                $users = $thisDatabaseReader->select($sql3, $data);

                print '<figure class="postBox">';

                if (is_array($users)) {
                    foreach ($users as $user) {
                        print '<h3>' . $post['fldPostTitle'] . ' by ' . $user['fldUsername'] . '</h3>';
                        //print '<h4>By ' . $user['fldUsername'] . ' on ' . $comment['fldDate'] . '</h4>';
                    }
                }

                // The post's content
                print '<p id="postContent' . $post['pmkPostId'] .'">' . $post['fldContent'] . '</p>';

                // Delete confirmation text & form
                print '<form id="deletePostForm' . $post['pmkPostId'] . '" class="deletePostForm" method="POST">';
                if ((isset($_SESSION['loggedin'])) && $post['fpkUserId'] == $_SESSION['id']) {
                    print '<input type="hidden" name="postId" value="' . $post['pmkPostId'] .'">';
                }
                print '<p id="deletePostText' . $post['pmkPostId'] . '">Are you sure you want to delete this post?</p>';
                print '<button type="submit" class="modifyCommentButton" name="confirmDeletePost">Delete</button>';
                print "<button type='button' class='modifyCommentButton' name='cancelDeletePost' onclick='showDeletePost({$post['pmkPostId']})'>Cancel</button>";
                print '</form>';

                // Textarea to edit the post
                print '<form id="editPostForm' . $post['pmkPostId'] . '" class="editPostForm" method="POST">';
                if ((isset($_SESSION['loggedin'])) && $post['fpkUserId'] == $_SESSION['id']) {
                    print '<input type="hidden" name="postId" value="' . $post['pmkPostId'] .'">';
                }
                print '<textarea id="editPostArea" name="editPostArea">' . $post['fldContent'] . '</textarea>';
                print '<button type="submit" class="modifyCommentButton" name="saveEditPost">Save</button>';
                print "<button type='button' class='modifyCommentButton' name='cancelEditPost' onclick='showEditPost({$post['pmkPostId']})'>Cancel</button>";
                print '</form>';
                
                // The rating buttons for the post
                print '<form id="ratingPostForm' . $post['pmkPostId'] . '" class="rate" method="POST">';
                print '<p id="ratingPostText' . $post['pmkPostId'] . '" class="rating_text">Rating: ' . $post['fldRating'] . ' </p>';
                print "<image src='images/uparrow.png' alt='Upvote' class='rateButton' onclick='postRateClick(1, {$post['pmkPostId']})' width='12' height='12'>";
                print "<image src='images/downarrow.png' alt='Downvote' class='rateButton' onclick='postRateClick(0, {$post['pmkPostId']})' width='12' height='12'>";
                print '</form>';

                // Comment counter
                $sql2 = 'SELECT COUNT(*) FROM tblComments ';
                $sql2 .= 'WHERE fpkPostId = ?';

                $data2 = array($post['pmkPostId']);

                $numComments = $thisDatabaseReader->select($sql2, $data2);
            
                if (is_array($numComments)) {
                    foreach ($numComments as $numComment) {
                        print '<p id="numPostComments'  . $post['pmkPostId'] . '">Comments: ' . $numComment['COUNT(*)'] . ' </p>';
                    }
                }

                // Edit and delete buttons for the post
                print('<form id="postModifyButtons' . $post['pmkPostId'] . '" class="modify" method="POST">');
                if ((isset($_SESSION['loggedin'])) && $post['fpkUserId'] == $_SESSION['id']) {
                    print '<input type="hidden" name="PostId" value="' . $post['pmkPostId'] .'">';
                    print "<button type='button' class='deleteComment_button' name='btnEdit' onclick='showEditPost({$post['pmkPostId']})'>Edit</button>";
                    print "<button type='button' class='deleteComment_button' name='btnDelete' onclick='showDeletePost({$post['pmkPostId']})'>Delete</button>";
                }
                print '</figure>';
            }
        }
        
        print '<h3 id="commentHeader">Comments</h3>';

        print '<form class="sort" method="POST">';
        print '<fieldset>';
        print '<label>Sort by: </label>';
        print '<select name="sort" onchange="this.form.submit()">';
        ?>

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

        <?php
        print '</select>';
        print '</fieldset>';
        print '</form>';

        // Form to create a comment on a post
        if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true) {
            print '<form method="POST" id="createComment">';
            print '<fieldset>';
            print('<label>Post a Comment: </label>');
            print('<textarea name="writepost"></textarea>');
            print '<button type="submit" id="createComment_button" name="btnSubmit">Post</button>';
            print '</fieldset>';
            $shouldSave = true;
            if (isset($_POST['btnSubmit'])) {
                $comment = (string) getData("writepost");

                if ($comment == "") {
                    print('<p id="mistake_comment">Body of the comment is empty.</p>');
                    $shouldSave = false;
                }

                if ($shouldSave) {
                    $sql = 'INSERT INTO tblComments SET fpkPostId = ?, fldComment = ?, fpkUserId = ?, fldRating = ?, fldDate = ?';

                    $data = array();
                    $data[] = $postId;
                    $data[] = $comment;
                    $data[] = $_SESSION['id'];
                    $data[] = 0;

                    // Log the current time
                    date_default_timezone_set('America/New_York');
                    $data[] = date("Y-m-d H:i:s");

                    if ($thisDatabaseWriter->insert($sql, $data)) {
                        print('<h2>Comment post successful.</h2>');
                         // Refresh the page after sucessful query
                        //header("Refresh:0");
                    }else{
                        print('<h2>Comment post failed.</h2>');
                    }
                }
            }
            print '</form>';
        }

        // SQL for updating comments
        if (isset($_POST['saveEditComment'])) {
            $sql = 'UPDATE tblComments SET fldComment = ? WHERE pmkCommentId = ?';
            
            $data = array();
            $data[] = $_POST['editCommentArea'];
            $data[] = $_POST['commentId'];

            if ($thisDatabaseWriter->update($sql, $data)) {
                print('<h2>Comment has been updated.</h2>');
                //header("Refresh:0");
            }else{
                print('<h2>Comment update failed.</h2>');
            }
        }

        // SQL for deleting comments
        if (isset($_POST['confirmDeleteComment'])) {
            $sql = 'DELETE FROM tblComments WHERE pmkCommentId = ?';
            $data = array($_POST['commentId']);

            if ($thisDatabaseWriter->delete($sql, $data)) {
                // Refresh the page after sucessful query
                //header("Refresh:0");
            }else{
                print('<h2>Comment delete failed.</h2>');
            }
        }

        // Get all the comments again
        $comments = $thisDatabaseReader->select($sqlComments, array($postId));

        if (is_array($comments)) {
            foreach ($comments as $comment) {
                print '<figure class="commentDisplay">';

                // SQL query for getting the username
                $sql3 = 'SELECT fldUsername FROM tblUsers WHERE pmkUserId = ?';
                $data = array($comment['fpkUserId']);

                $users = $thisDatabaseReader->select($sql3, $data);

                if (is_array($users)) {
                    foreach ($users as $user) {
                        print '<h4>By ' . $user['fldUsername'] . ' on ' . $comment['fldDate'] . '</h4>';
                    }
                }
                print '<p id="commentContent' . $comment['pmkCommentId'] .'">' . $comment['fldComment'] . '</p>';

                // Delete confirmation for comments
                print '<form id="deleteCommentForm' . $comment['pmkCommentId'] . '" class="deleteCommentForm" method="POST">';
                if ((isset($_SESSION['loggedin'])) && $comment['fpkUserId'] == $_SESSION['id']) {
                    print '<input type="hidden" name="commentId" value="' . $comment['pmkCommentId'] .'">';
                }
                print '<p id="deleteCommentText' . $comment['pmkCommentId'] . '">Are you sure you want to delete this post?</p>';
                print '<button type="submit" class="modifyCommentButton" name="confirmDeleteComment">Delete</button>';
                print "<button type='button' class='modifyCommentButton' name='cancelDeleteComment' onclick='showDeleteComment({$comment['pmkCommentId']})'>Cancel</button>";
                print '</form>';

                // Comment editing textarea and form
                print '<form id="editCommentForm' . $comment['pmkCommentId'] . '" class="editCommentForm" method="POST">';
                if ((isset($_SESSION['loggedin'])) && $comment['fpkUserId'] == $_SESSION['id']) {
                    print '<input type="hidden" name="commentId" value="' . $comment['pmkCommentId'] .'">';
                }
                print '<textarea id="editCommentArea" name="editCommentArea">' . $comment['fldComment'] . '</textarea>';
                print '<button type="submit" class="modifyCommentButton" name="saveEditComment">Save</button>';
                print "<button type='button' class='modifyCommentButton' name='cancelEditComment' onclick='showEditComment({$comment['pmkCommentId']})'>Cancel</button>";
                print '</form>';

                // Upvote/downvote button form
                print '<form id="ratingCommentForm' . $comment['pmkCommentId'] . '" class="rate" method="POST">';
                print '<p id="ratingText' . $comment['pmkCommentId'] . '" class="rating_text">Rating: ' . $comment['fldRating'] . ' </p>';
                print "<image src='images/uparrow.png' alt='Upvote' class='rateButton' onclick='rateClick(1, {$comment['pmkCommentId']})' width='12' height='12'>";
                print "<image src='images/downarrow.png' alt='Downvote' class='rateButton' onclick='rateClick(0, {$comment['pmkCommentId']})' width='12' height='12'>";
                print '</form>';

                // Edit and delete buttons for the comment
                print('<form id="commentModifyButtons' . $comment['pmkCommentId'] . '" class="modify" method="POST">');
                if ((isset($_SESSION['loggedin'])) && $comment['fpkUserId'] == $_SESSION['id']) {
                    print '<input type="hidden" name="commentId" value="' . $comment['pmkCommentId'] .'">';
                    print "<button type='button' id='editCommentButton' class='deleteComment_button' name='btnEdit' onclick='showEditComment({$comment['pmkCommentId']})'>Edit</button>";
                    print "<button type='button' class='deleteComment_button' name='btnDelete' onclick='showDeleteComment({$comment['pmkCommentId']})'>Delete</button>";
                }
                print('</form>');
                print '</figure>';
            }
        }
    print '</main>';
    include 'footer.php';
    ?>