<?php
include './top.php';

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
    if(isset($_POST['insert'])){
        $title = getData("postName");
        $type = getData("type");
        $content = getData("content");

        if ($title == "") {
            print('<p>Post title cannot be empty</p>');
            $shouldSave = false;
        }

        if ($type == "") {
            print('<p>Post type cannot be empty</p>');
            $shouldSave = false;
        }

        if ($content == "") {
            print('<p>Post content cannot be empty</p>');
            $shouldSave = false;
        }

        if ($shouldSave) {
            $sql = 'INSERT INTO tblPosts SET fpkUserId = ?, fldPostTitle = ?, fldContent = ?, fldType = ?, fldDate = ?';
            
            $data = array();

            // All admin posts are attributed to this user dubbed 'ADMIN'
            // This might be a bad idea
            $data[] = 4;
            $data[] = $title;
            $data[] = $content;
            $data[] = $type;
            
            // Log the current time
            date_default_timezone_set('America/New_York');
            $data[] = date("Y-m-d H:i:s");

            if ($thisDatabaseWriter->insert($sql, $data)) {
                print('<h2>Admin post successful.</h2>');
            }else{
                print('<h2>Admin post failed.</h2>');
            }
        }
    }

    print('<form action="'. PHP_SELF . '" id="update" method="post">');
    print('<h2>Inserting a Record</h2>');
    print('<fieldset>');
    print('<label>Post Name: </label>');
    print('<input type="text" name="postName" value="">');
    print('</fieldset>');
    print('<fieldset>');
    print('<label>Type: </label>');
    print('<select name="type">');
    print('<option value="pinned">Pinned</option>');
    print('<option value="normal">Normal</option>');
    print('</select>');
    print('</fieldset>');
    print('<fieldset>');
    print('<label>Post Content: </label>');
    print('<textarea name="content"></textarea>');
    print('</fieldset>');
    print('<input type="hidden" id="wildlifeId" name="wildlifeId" value="">');
    print('<fieldset>');
    print('<button type="submit" name="insert">Insert</button>');
    print('</fieldset>');
    print('</form>');
    ?>
</main>