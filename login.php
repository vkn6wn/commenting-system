<?php require_once("config.php");?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1"> 

    <meta name="author" content="Katie Nguyen">
    <meta name="description" content="Login">
    <meta name="keywords" content="article-blogspot-login"> 
    
    <title>Login</title>

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
                <form action="" method="POST">
                    <div class="form-group">
                        <label>Email</label>
                        <input type="text" class="form-control" placeholder="Email" name="login_var" required>
                    </div>
                    <div class="form-group">
                        <label>Password</label>
                        <input type="password" class="form-control" placeholder="Password" name="password" required>
                    </div>
                        <div class="form-group form-check">
                    </div>
                    <button type="submit" class="btn btn-warning" name="login_submit">Login</button>
                    <a href="register.php" class="btn btn-warning" role="button" id="create_account">Create An Account</a>
                </form>

                <?php 
                // check if login form data was submitted
                if (isset($_POST['login_submit'])) {
                    if ( !isset($_POST['login_var'], $_POST['password']) ) {
                        // display error message if submitted without both email and password
                        echo "<div class='alert alert-danger error_message'>Please fill both the email and password fields</div>";
                        exit();
                    } else {
                        // determine number of users with the same entered email log in
                        $stmt = $pdo->prepare('SELECT COUNT(*) as total FROM users WHERE email = ?');
                        $stmt->execute([ $_POST['login_var'] ]);
                        $count = $stmt->fetchColumn();

                        // check to see if user exists before verifying entered password
                        if($count>0) {
                            // verify if entered password is correct for the corresponding email log in
                            $count=$pdo->prepare("SELECT id,username,password FROM users WHERE email= ?");
                            $count->execute([ $_POST['login_var'] ]);
                            $row = $count->fetch();

                            if(password_verify($_POST['password'],$row['password'])) {
                                $_SESSION["login_session"]="1";
                                $_SESSION["userid"]=$row['id'];
                                $_SESSION['username'] = $row['username'];
                                header("location:index.php");
                            } else {
                                echo "<div class='alert alert-danger error_message'>Wrong Password</div>";
                            }
                        } else {
                            echo "<div class='alert alert-danger error_message'>Invalid Username or Password</div>";
                        }

                    }
                }
                ?>

            </div>
            <div class="col-sm-3"></div>
        </div>
    </div>
</body>
</html>