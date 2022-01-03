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

$siteEmail = "jtlai@uvm.edu";

$email = "example@gmail.com";
$username = "exampleusername";
$password = "examplepassword";

$shouldSave = true;
if (isset($_POST['btnSubmit'])) {
    $email = (string) filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $username = (string) getData("username");
    $password = (string) getData("password");

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        print('<p class="mistake">Input a valid email address.</p>');
        $shouldSave = false;
    }

    if ($email == "example@gmail.com") {
        print('<p class="mistake">Form email needs to be different than the sample.</p>');
        $shouldSave = false;
    }

    if ($username == "") {
        print('<p class="mistake">Input a valid username.</p>');
        $shouldSave = false;
    }

    if ($password == "") {
        print('<p class="mistake">Input a valid password.</p>');
        $shouldSave = false;
    }

    if ($shouldSave) {
        $sql = 'INSERT INTO tblUsers SET fldUsername = ?, fldPassword = ?, fldEmail = ?';

        $data = array();
        $data[] = $username;
        $data[] = $password;
        $data[] = $email;

        if ($thisDatabaseWriter->insert($sql, $data)) {
            print('<h2>Registration successful.</h2>');
            $to = $email;

            $subject = "Justin Lai Final Project Registration";

            $headers = "From: " . strip_tags($siteEmail) . "\r\n";
            $headers .= "Reply To: " . strip_tags($siteEmail) . "\r\n";
            $headers .= "CC: jtlai@uvm.edu\r\n";
            $headers .= "MIME-Version: 1.0\r\n";
            $headers .= "Content-Type: text/html; charset=utf-8\r\n";

            $message = "<html><body>";
            $message .= "<h1>Testing</h1>";
            $message .= "<p>You have registered successfully.</p>";
            $message .= "</body></html>";

            mail($to, $subject, $message, $headers);

            /** This doesn't exactly work as intended **/
            // But the form does update two tables per submission
            $sql2 = 'SELECT LAST_INSERT_ID()';
            $data2 = array();

            $lastId = $thisDatabaseReader->select($sql2, $data2);
            $insertedId = array();

            if (is_array($lastId)) {
                foreach ($lastId as $id) {
                    $insertedId = $id['LAST_INSERT_ID()'];
                }
            }
            /********************************************/


            $sql3 = 'INSERT INTO tblProfiles SET fpkUserId = ?, fldEmail = ?, fldCreateDate = ?';

            $data3 = array();
            $data3[] = $insertedId;
            $data3[] = $email;

            // Log the current time
            date_default_timezone_set('America/New_York');
            $data3[] = date("Y-m-d H:i:s");

            if ($thisDatabaseWriter->insert($sql3, $data3)) {
                print('<h2>Created profile.</h2>');
            }else{
                print('<h2>Profile creation failed.</h2>');
            }
        }else{
            print('<h2>Registration failed.</h2>');
        }
    }
}


?>

<form id="registerForm" name="registerForm" action="<?php print PHP_SELF; ?>" method="post" autocomplete="off">
    <h1>Register</h1>
    <fieldset class="register">  
        <label>Email:</label>
        <input type="search" name="email" value=''>
        <p id="emailFeedback" style="color:red"></p>
    </fieldset>

    <fieldset class="register">  
        <label>Username:</label>
        <input type="search" name="username" id="username" minlength="4" autocomplete="off" value=''>
        <p id="usernameFeedback" style="color:red"></p>
    </fieldset>

    <fieldset class="register">  
        <label>Password:</label>
        <input type="password" name="password" id="registerPassword" minlength="5" autocomplete="off" value=''>
        <p id="passwordFeedback" style="color:red"></p>
    </fieldset>

    <fieldset class="register">  
        <label>Confirm Password:</label>
        <input type="password" name="confirmPassword" id="confirmPassword" value=''>
    </fieldset>

    <fieldset class="register">
        <label id="passwordCheck" style="color:red"></label>
    </fieldset>

    <fieldset class="register">
        <button type="submit" name="btnSubmit">Register</button>
    </fieldset>
</form>
</main>
<?php
include 'footer.php';
?>