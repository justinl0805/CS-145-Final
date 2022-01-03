<?php
include 'top.php';

// Prevent the user from entering the page if they are not logged in
if (!isset($_SESSION["loggedin"])) {
    header("location: index.php");
    exit;
}

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
    $shouldSave = true;
    if (isset($_POST['btnSubmit'])) {
        $title = getData("title");
        $content = getData("content");

        if ($title == "") {
            print('<p class="mistake">Post title cannot be empty</p>');
            $shouldSave = false;
        }

        if ($content == "") {
            print('<p class="mistake">Post content cannot be empty</p>');
            $shouldSave = false;
        }

        if ($shouldSave) {
            $sql = 'INSERT INTO tblPosts SET fpkUserId = ?, fldPostTitle = ?, fldContent = ?, fldDate = ?';
            
            $data = array();

            $data[] = $_SESSION['id'];
            $data[] = $title;
            $data[] = $content;
            
            // Log the current time
            date_default_timezone_set('America/New_York');
            $data[] = date("Y-m-d H:i:s");

            if ($thisDatabaseWriter->insert($sql, $data)) {
                print('<h2>Post successful.</h2>');
            }else{
                print('<h2>Post failed.</h2>');
            }
        }
    }
    ?>
    <form method="POST" id="createComment">
        <h2>Create a Post</h2>
        <fieldset>  
            <label for="title">Post Title:</label>
            <input type="text" name="title" value="">
        </fieldset>
        <fieldset>
            <label for="writepost">Create a Post: </label>
            <textarea name="content"></textarea>
        </fieldset>
        <button type="submit" id="createComment_button" name="btnSubmit">Post</button>
    </form>
</main>
<?php
include 'footer.php';
?>