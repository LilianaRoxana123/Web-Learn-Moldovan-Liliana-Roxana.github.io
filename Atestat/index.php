<?php
session_start();
?>
<html>

<head>
    <link rel="stylesheet" href="ss.css">
</head>

<body>
    <?php
    if (isset($_POST['username'])) {
        $username = $_POST['username'];
        $password = $_POST['password'];

        $users = file('users.txt');
        foreach ($users as $user) {
            $user = rtrim($user);
            list($stored_username, $stored_password) = explode(':', $user);
            if ($username == $stored_username && $password == $stored_password) {
                $_SESSION['usr'] = $username;
                echo '<script>window.location.replace("profile.php");</script>';
            }
        }
        $error = 'Invalid username or password.';
    }

    ?>

    <div class="wrapper fadeInDown">
        <div id="formContent">
            <!-- Tabs Titles -->

            <!-- Icon -->
            <div class="fadeIn first">
                <img src="img\user_icon.jpg" id="icon" alt="User Icon" />
            </div>

            <!-- Login Form -->
            <form method="POST" target="index.php">
                <input type="text" id="login" class="fadeIn second" name="username" placeholder="username">
                <input type="text" id="password" class="fadeIn third" name="password" placeholder="password">
                <input type="submit" class="fadeIn fourth" value="Log In">

                <?php if (isset($error)) { ?>
                    <p>
                        <?php echo $error; ?>
                    </p>
                <?php } ?>
            </form>

            <!-- welcome note -->
            <div id="formFooter">
                <h1>Welcome to the Universe!</h1>
            </div>

        </div>
    </div>
</body>

</html>