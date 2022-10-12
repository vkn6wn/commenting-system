<?php require_once("config.php");?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1"> 

    <meta name="author" content="Katie Nguyen">
    <meta name="description" content="Register">
    <meta name="keywords" content="article-blogspot-register"> 
    
    <title>Register</title>

    <link href="styles/style.css" rel="stylesheet" type="text/css">
    <link href="styles/comments.css" rel="stylesheet" type="text/css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" 
    integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
</head>
<body>
    <nav class="navtop">
        <div>
            <h1><a href="index.php" id="website_header">BLOGSPOT ARTICLES</a></h1>
        </div>
    </nav>

    <div class="container">
        <br>
        <div class="row"> 
            <div class="col-sm-3"></div>
            <div class="col-sm-6"> 

                <!-- Account registration form -->
                <form name="registerForm" id="registerForm" action="" method="POST">
                    <div class="form-group">
                        <label>Name</label>
                        <input type="text" class="form-control" placeholder="Name" id="reg_name" name="reg_name" required>
                    </div>
                    <div class="form-group">
                        <label>Email</label>
                        <input type="email" class="form-control" placeholder="Email" id="reg_email" name="reg_email" required>
                    </div>
                    <div class="form-group">
                        <label>Password</label>
                        <input type="password" class="form-control" placeholder="Password" id="reg_pwd" name="reg_pwd" required>
                    </div>
                    <div class="form-group">
                        <label>Confirm Password</label>
                        <input type="password" class="form-control" placeholder="Confirm Password" id="reg_confirm" name="reg_confirm" required>
                    </div>
                    <div class="form-group form-check">
                    </div>
                    <button type="submit" class="btn btn-warning" name="registration_submit">Create Account</button>
                    <a href="login.php" class="btn btn-warning" role="button" id="create_account">Back to Log In</a>
                </form>

                <?php 
                //check if username only has letters and spaces
                $userReg = "/^[A-Za-z\s]+$/";

                if (isset($_POST["reg_name"])) {
                    if (preg_match($userReg, $_POST["reg_name"])) {
                        //check if user already exists
                        $result=$pdo->prepare("SELECT COUNT(*) as total FROM users WHERE email = ?");
                        $result->execute([ $_POST["reg_email"] ]);
                        $count = $result->fetchColumn();

                        if ($count>0) {
                            echo "<div class='alert alert-danger error_message'>User already exists</div>";
                        } else {
                            // check if entered passwords match
                            if ($_POST["reg_pwd"]===$_POST["reg_confirm"]) {
                                $hashed_password = password_hash($_POST['reg_pwd'], PASSWORD_DEFAULT);
                                $insert = $pdo->prepare('INSERT INTO users (username, email, password) values (?, ?, ?)');
                                $insert->execute([ $_POST["reg_name"], $_POST['reg_email'], $hashed_password ]);
                                
                                // if account could not be created set an error message
                                if ($insert === false) {
                                    echo "<div class='alert alert-danger error_message'>Account could not be created</div>";
                                } else {
                                    $_SESSION["user_email"] = $_POST["reg_email"];
                                    header("Location: login.php");
                                }
                            } else {
                                echo "<div class='alert alert-danger error_message'>Passwords do not match</div>";
                            }
                        }
                    } else {
                        echo "<div class='alert alert-danger error_message'>Username may only contain letters and numbers</div>";
                    }
                }
                ?>

            </div>
            <div class="col-sm-3"></div>
        </div>
    </div>

</body>
</html>