<?php
include './top.php';

$sql = 'SELECT pmkUserId, fldUsername, fldEmail ';
if(isset($_POST['submit'])){
    $sql .= 'FROM tblUsers ';
    $sql .= 'WHERE pmkUserId = ?';
}else{
    $sql .= 'FROM tblUsers';
}

$data = isset($_POST['submit']) ? array($_POST['submit']) : '';
$users = $thisDatabaseReader->select($sql, $data);

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
    if(isset($_POST['delete'])){
        $id = getData("userId");
    
        $sql = 'DELETE FROM tblUsers ';
        $sql .= 'WHERE pmkUserId = ?';
    
        $data = array();
        $data[] = $id;
    
        if($thisDatabaseWriter->delete($sql, $data)){
            print('<h2>User has been deleted.</h2>');
             // Refresh the page after sucessful query
             header("Refresh:0");
        }else{
            print('<h2>User deletion has failed.</h2>');
        }
    }

    if (isset($_POST['submit'])) {
        if (is_array($users)) {
            foreach ($users as $user) {
                print('<form action="'. PHP_SELF . '" method="post">');
                print('<h3>Are you sure you want to delete: ' . $user['fldUsername'] . '?</h3>');
                print('<input type="hidden" name="userId" value="' . $user['pmkUserId'] . '">');
                print('<fieldset>');
                print('<button type="submit" name="delete">Delete</button>');
                print('</fieldset>');
                print('</form>');
            }
        }
    }else{
        print('<form class="modify" action="'. PHP_SELF . '" method="post">');
        print('<table>');
        print('<tr>');
        print('<th>User ID</th>');
        print('<th>User Name</th>');
        print('<th>User Email</th>');
        print('<th>Delete</th>');
        print('</tr>');
        if (is_array($users)) {
            foreach ($users as $user) {
                print('<tr>');
                print('<td>');
                print('<p>' . $user['pmkUserId'] . '</p>');
                print('</td>');
                print('<td>');
                print '<p>' . $user['fldUsername'] . '</p>';
                print('</td>');
                print('<td>');
                print('<p>' . $user['fldEmail'] . '</p>');
                print('</td>');
                print('<td>');
                print('<button type="submit" name="submit" value="' . $user['pmkUserId'] . '">Delete</button>');
                print('</td>');
                print('</tr>');
            }
        }
    }
    print('</table>');
    print('</form>');
    ?>
</main>