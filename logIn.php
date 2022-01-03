<?php
include 'top.php';

?>
<main>
<?php
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

$canLogin = true;

if (isset($_GET['logout']) && $_GET['logout'] == TRUE) {
    $_SESSION = array();
    session_destroy();

    header("location: index.php");
    exit;
}

if (isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true) {
    header("location: index.php");
    exit;
}

if (isset($_POST['btnSubmit'])) {
    $username = (string) getData('username');
    $password = (string) getData('password');

    if ($username == "") {
        print ('<h1>Please enter a username.</h1>');
        $canLogin = false;
    }

    if ($password == "") {
        print ('<h1>Please enter a password.</h1>');
        $canLogin = false;
    }
        
    if ($canLogin) {
        $sql = "SELECT pmkUserId, fldUsername, fldPassword, fldEmail FROM tblUsers WHERE fldUsername = ?";

        $data = array($username);

        $logged = $thisDatabaseReader->select($sql, $data);
        if (!empty($logged)) {
            foreach ($logged as $credentials) {
                if ($credentials['fldPassword'] == $password) {
                    print('<h1>Login successful.</h1>');  
                    $_SESSION['loggedin'] = true;
                    $_SESSION['id'] = $credentials['pmkUserId'];
                    $_SESSION['username'] = $credentials['fldUsername'];

                    header("location: index.php");
                }else{
                    print('<h1>Login failed. Incorrect password.</h1>');
                }
            }
        }else{
            print('<h1>Login failed. Check your username.</h1>');
        }
    }
}
?>
<form id="login" action="<?php print PHP_SELF; ?>" method="post">
    <h1>Log In</h1>
    <fieldset class="login">  
        <label>Username:</label>
        <input type="text" name="username" value="">
    </fieldset>

    <fieldset class="login">  
        <label>Password:</label>
        <input type="password" name="password" value="">
    </fieldset>

    <fieldset class="login">
        <button type="submit" name="btnSubmit">Login</button>
    </fieldset>
</form>

<p><a href="register.php">Create an Account</a><p>
</main>
<?php
include 'footer.php';
?>