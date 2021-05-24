<?php
require_once 'tools.php';

function register_user($email, $username, $password) {
    global $lambda_client, $REGISTER_URL;

    $body = [
        'email' => $email,
        'username' => $username,
        'password' => $password
    ];

    $result = $lambda_client->invoke([
        'FunctionName' => 'arn:aws:lambda:ap-southeast-2:299197477071:function:register-user',
        'Payload' => json_encode($body)
    ]);

    $result_code = $result['Payload']['statusCode'];
    if ($result_code == 409) {
        echo 'User already exists</br>';
    } else if ($result_code == 201) {
        echo 'Created<br>';
    } else {
        echo 'Missing attributes: ' . $result['Payload']['body'];
    }
}

?>

<html>
    <head>
        <title>Register</title>
    </head>
    <body>
    <?php
        if (isset($_POST['submit'])) {
            register_user($_POST['email'], $_POST['username'], $_POST['password']);
        }
    ?>
    <form name="register-form" method="post" action="register.php">
        <h3>Email</h3>
        <input type="email" name="email">
        <br/>

        <h3>Username</h3>
        <input type="text" name="username" required>
        <br/>

        <h3>Password</h3>
        <input type="password" name="password" required>
        <br/>

        <input type="submit" value="Register" name="submit">
        <p><a href="login.php">Already registered? Log in here.</a></p>
    </form>
    </body>
</html>
