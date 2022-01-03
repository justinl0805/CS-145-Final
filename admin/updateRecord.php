<?php
include './top.php';

$sql = 'SELECT pmkPostId, fpkUserId, fldPostTitle, fldContent, fldRating, fldType ';
if(isset($_POST['submit'])){
    $sql .= 'FROM tblPosts ';
    $sql .= 'WHERE pmkPostId = ?';
}else{
    $sql .= 'FROM tblPosts';
}

$data = isset($_POST['submit']) ? array($_POST['submit']) : '';
$posts = $thisDatabaseReader->select($sql, $data);


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
if (isset($_POST['update'])) {
    $title = getData("postTitle");
    $content = getData("content");
    $postId = getData("postId");
    $type = getData("type");

    if ($shouldSave) {
        $sql = 'UPDATE tblPosts SET fldPostTitle = ?, fldContent = ?, fldType = ? WHERE pmkPostId = ?';

        $data = array();
        $data[] = $title;
        $data[] = $content;
        $data[] = $type;
        $data[] = $postId;

        if ($thisDatabaseWriter->update($sql, $data)) {
            print('<h2>Post has been updated.</h2>');
        }else{
            print('<h2>Post failed to update.</h2>');
        }
    }
}


if (isset($_POST['submit'])) {
    if (is_array($posts)){
        foreach ($posts as $post) {
            print('<h2>Updating Post ID: ' . $post['pmkPostId'] . '</h2>');
            print('<form action="'. PHP_SELF . '" id="update" method="post">');
            print('<fieldset>');
            print('<label>Post Name: </label>');
            print('<input type="text" name="postTitle" value="'. $post['fldPostTitle'] . '">');
            print('</fieldset>');
            print('<fieldset>');
            print('<label>Type: </label>');
            print('<select name="type">');
            ?>
            <option value="pinned" <?php
                print($post['fldType'] == "pinned" ? "selected" : "");
                ?>>Pinned</option>
            <option value="normal" <?php
                print($post['fldType'] == "normal" ? "selected" : "");
                ?>>Normal</option>
            <?php
            print('</select>');
            print('</fieldset>');
            print('<fieldset>');
            print('<label>Post Content: </label>');
            print('<textarea name="content">' . $post['fldContent'] . '</textarea>');
            print('</fieldset>');
            print('<input type="hidden" name="postId" value="' . $post['pmkPostId'] . '">');
            print('<fieldset>');
            print('<button type="submit" name="update">Update</button>');
            print('</fieldset>');
            print('</form>');
        }
    }
}else{
    print('<form class="modify" action="'. PHP_SELF . '" method="post">');
    print('<table>');
    print('<tr>');
    print('<th>Post Title</th>');
    print('<th>Post Author</th>');
    print('<th>Post Rating</th>');
    print('<th>Update</th>');
    print('</tr>');
    if (is_array($posts)) {
        foreach ($posts as $post) {
            print('<tr>');
            print('<td>');
            print('<p>' . $post['fldPostTitle'] . '</p>');
            print('</td>');
            print('<td>');
            // SQL query for getting the username
            $sql3 = 'SELECT fldUsername FROM tblUsers WHERE pmkUserId = ?';
            $data = array($post['fpkUserId']);

            $users = $thisDatabaseReader->select($sql3, $data);

            if (is_array($users)) {
                foreach ($users as $user) {
                    print '<p>' . $user['fldUsername'] . '</p>';
                }
            }
            print('</td>');
            print('<td>');
            print('<p>' . $post['fldRating'] . '</p>');
            print('</td>');
            print('<td>');
            print('<button type="submit" name="submit" value="' . $post['pmkPostId'] . '">Update</button>');
            print('</td>');
            print('</tr>');
        }
    }
    print('</table>');
    print('</form>');
}
?>
</main>