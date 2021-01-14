<?php
include "top.php";

//Sanatize GET to know if user needs to sign in or sign up
$signUp = sanatizeGetData("SignUp");
$error = false;
$errorEmail = false;
$email = "";
$firstName = "";
$lastName = "";
$userId = "";

$mailed = false;
$to = '';
$cc = '';
$bcc = '';
$from = '';
$subject = '';
$message = '';
//Getting Email array
$query = "SELECT email "
    . "FROM tblUser";
$emails = '';

if ($thisDatabaseReader->querySecurityOk($query, 0)) {
    $query = $thisDatabaseReader->sanitizeQuery($query);
    $emails = $thisDatabaseReader->select($query, '');
}

if (DEBUG) {
    print '<p>Contents of the array Names<pre>';
    print_r($emails);
    print '</pre></p>';
}
//Sanatize POST Data
if ($signUp == 1) {
    if (isset($_POST["btnSubmit"])) {
        $firstName = sanatizePostData("txtFirstName");
        $lastName = sanatizePostData("txtLastName");
        $email = sanatizePostData("txtEmail");
        $password = sanatizePostData("txtPassword");
        //Validation
        if ($firstName == "") {
            $error = true;
        }
        if ($lastName == "") {
            $error = true;
        }
        if ($email == "") {
            $error = true;
        } else if (!verifyEmail($email)) {
            $error = true;
        }
        if ($password == "") {
            $error = true;
        }

        if (is_array($emails)) {
            foreach ($emails as $e) {
                if ($e["email"] == $email) {
                    $errorEmail = true;
                }
            }
        }

        if (!$error && !$errorEmail) {
            //Add information to database
            $insertQuery = 'INSERT INTO tblUser SET '
                . 'firstName = ?,'
                . 'lastName = ?,'
                . 'email = ?,'
                . 'password = ?,'
                . 'admin = ?';

            if ($thisDatabaseWriter->querySecurityOk($insertQuery, 0)) {
                $admin = 0;
                $insertQuery = $thisDatabaseWriter->sanitizeQuery($insertQuery);
                $insertData = array($firstName, $lastName, $email, $password, $admin);
                $result = $thisDatabaseWriter->insert($insertQuery, $insertData);
            }

            if ($result) {
                print"<p>Thank you for signing up! Your account is now made.</p>";
                $to = $email;
                $cc = '';
                $bcc = '';
                $from = "Bobby's Hobbies <zkiefer@uvm.edu>";
                $subject = 'Thanks for signing up!';
                $message = "Thank you " . $firstName . " for signing up! Keep in mind this is not a real store and there is no real"
                        . " transactions. Regardless, we hope you enjoy our site!</p>";
                $mailed = sendMail($to, $cc, $bcc, $from, $subject, $message);
            } else {
                print"<p>Sorry, something appears to have gone wrong. Please try again later</p>";
            }
            
            

        }
    }
} else {
    if (isset($_POST["btnSubmit"])) {
        $email = sanatizePostData("txtEmail");
        $password = sanatizePostData("txtPassword");

        //Validation
        if ($email == "") {
            $error = true;
        } else if (!verifyEmail($email)) {
            $error = true;
        }
        if ($password == "") {
            $error = true;
        }

        if (is_array($emails)) {
            foreach ($emails as $e) {
                if ($e["email"] == $email) {
                    $errorEmail = true;
                }
            }
            $errorEmail = !$errorEmail;
        }


        if (!$error && !$errorEmail) {
            $query = "SELECT * "
                . "FROM tblUser "
                . "WHERE `email` LIKE ?";
            $userCheck = '';


            if ($thisDatabaseReader->querySecurityOk($query)) {
                $query = $thisDatabaseReader->sanitizeQuery($query);
                $userCheck = $thisDatabaseReader->select($query, array($email));
            }

            if (DEBUG) {
                print '<p>Contents of the array userCheck<pre>';
                print_r($userCheck);
                print '</pre></p>';
            }

            if ($password == $userCheck[0]["password"]) {
                print"<p> You've successfully logged in.";

                $_SESSION["loggedIn"] = true;
                $_SESSION["firstName"] = $userCheck[0]["firstName"];
                $_SESSION["lastName"] = $userCheck[0]["lastName"];
                $_SESSION["email"] = $userCheck[0]["email"];
                $_SESSION["adminAccess"] = $userCheck[0]["admin"];
                $_SESSION["userId"] = $userCheck[0]["pmkUserId"];

                header("Location: index.php");
            } else {
                $error = true;
            }
        }

    }

}


//If the user needs to sign up
if ($signUp == 1) {
    print("<h3>Sign up</h3>");
    
    if ($error) {
        print("<p>There appears to be a problem</p>");
    }
    if ($errorEmail) {
        print("<p>That email is already in use</p>");
    }

    ?>

    <form id="SignUp" method='post'>
        <fieldset>
            <label for="txtFirstName">First Name</label>
            <input autofocus
                   type='text'
                   id='txtFirstName'
                   name='txtFirstName'
                   placeholder='First Name'
                   tabindex='100'
                   value='<?php print($firstName); ?>'>
            <label for="txtLastName">Last Name</label>
            <input autofocus
                   type='text'
                   id='txtLastName'
                   name='txtLastName'
                   placeholder='Last Name'
                   tabindex='200'
                   value='<?php print($lastName); ?>'>
            <label for="txtEmail">Email</label>
            <input autofocus
                   type='email'
                   id='txtEmail'
                   name='txtEmail'
                   placeholder='Email'
                   tabindex='300'
                   value='<?php print($email); ?>'>
        </fieldset>
        <fieldset>
            <p>This account is not for a real store. Please do not use your normal password for this site as it is not
                fully protected</p>
            <label for="txtPassword">Password</label>
            <input autofocus
                   type='password'
                   id='txtPassword'
                   name='txtPassword'
                   placeholder='Password'
                   tabindex='400'>
        </fieldset>
        <fieldset>
            <input class="button" id="btnSubmit" name="btnSubmit" tabindex="1000" type="submit" value="Submit">
        </fieldset>
    </form>

    <?php
} else if ($signUp == 2) {
    session_unset();
    ?>
    <p>Logged out successfully. We hope to see you soon!</p>
    <?php
    header("Location: index.php");
} else {
    print("<h3>Login</h3>");
    
    if ($error || $errorEmail) {
        print("<p>Please check your username and password, something appears to be incorrect</p>");
    }
    ?>
    
    <form id="Login" method='post'>
        <fieldset>
            <label for="txtEmail">Email</label>
            <input autofocus
                   type='email'
                   id="txtEmail"
                   name="txtEmail"
                   placeholder="Email"
                   tabindex="100"
                   value="<?php print($email); ?>">
            <label for="txtPassword">Password</label>
            <input autofocus
                   type='password'
                   id="txtPassword"
                   name="txtPassword"
                   placeholder="Password"
                   tabindex="200"
                   value="">
        </fieldset>
        <p>Don't have an account? <a href='login.php?SignUp=1'>Sign Up</a></p>
        <fieldset>
            <input class="button" id="btnSubmit" name="btnSubmit" tabindex="1000" type="submit" value="Login">
        </fieldset>
    </form>
    <?php
}

include "footer.php";

?>