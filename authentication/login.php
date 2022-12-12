<?php
session_start();
require_once '../classes/auth.php';

$message = '';
$email = "";

// echo md5(123);

if ($role = Auth::checkLogin()) {
    if ($role == 1) {
        header("Location: ../admin/index");
    } elseif ($role == 2) {
        header("Location: ../client/index");
    } elseif ($role == 3) {
        header("Location: ../client/index");
    }
}

if (isset($_POST['submit'])) {
    $message = '<i class="fas fa-exclamation-circle"></i> ';
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $pswd = $_POST['password'];

    $res = Auth::authenticate($email, $pswd);
    if ($res['is_authenticated']) {
        if ($res['role'] == 1) {
            header("Location: ../admin/index");
        } elseif ($res['role'] == 2) {
            header("Location: ../client/index");
        } elseif ($res['role'] == 3) {
            header("Location: ../client/index");
        }
    } else
        $message .= $res['message'];
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <?php include_once '../templates/header.php' ?>
    <title>Login</title>
    <style>
        .login-left {
            background-image: linear-gradient(to right, rgba(134, 207, 243, 1), rgba(250, 250, 250, 0)), url("https://www.eschoolnews.com/files/2022/07/school-libraries-school-librarians.jpeg");
            background-size: cover;
            background-position: center;
            clip-path: polygon(0 0, 75% 0%, 100% 50%, 75% 100%, 0 100%, 0% 50%);
        }

        .login-form {
            min-width: 400px;
        }

        #login-title {
            border-left: 5px solid #86cff3;
            padding-left: 10px;
        }

        #logo {
            border: 7px solid white;
            border-radius: 100%;
        }

        .btn {
            background-image: linear-gradient(to right, rgba(134, 207, 243, 1), rgba(0, 0, 250, 0.3));
            border: none;
            transition: all 0.2s ease;
        }

        .btn:hover {
            background-image: linear-gradient(to right, rgba(134, 207, 243, 0.5), rgba(0, 0, 250, 0.3));
            transform: scale(95%);
            border: none;
        }

        @media only screen and (max-width: 768px) {
            .login-left {
                /* background-color: #eee; */
                clip-path: none;
                margin-bottom: 40px;
                padding: 20px 0;
            }

            #logo {
                border: 3px solid white;
                height: 80px;
                margin-bottom: 20px;
            }
        }
    </style>
</head>

<body>
    <div class="vh-100 d-flex flex-md-row flex-sm-column flex-column align-items-stretch justify-content-stretch">
        <div class=" w-100 login-left d-flex align-items-center justify-content-center">
            <div class="text-center">
                <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcR9JehUoF8VzpKeYzIyTXYgka--AlsEC5SNe5tAGzlteQEYTwm-EBLY5yO2yqmYFdxTOXU&usqp=CAU" height="200" alt="" id="logo" class="mb-md-4 md-sm-2 md-2">
                <div style="text-shadow: 2px 3px 9px rgba(0,0,0,0.45)">
                    <div class="display-2 fw-bolder text-white">LIBRARY</div>
                    <div class="display-2 text-white">Management<br>System</div>
                </div>

            </div>
        </div>
        <div class=" w-100 d-flex align-items-center justify-content-center">
            <div class="p-3 login-form">
                <div id="login-title">
                    <div class="h2 fw-bold">Account<br>Login</div>
                </div>
                <hr>
                <div class="h6 text-danger ">
                    <?= $message; ?>
                </div>
                <form action="" method="POST">
                    <input type="text" class="form-control mb-3" placeholder="Email" name="email" value="<?= $email ?>" required>
                    <input type="password" class="form-control  mb-5" placeholder="Password" name="password" required>
                    <button type="submit" name="submit" class="btn btn-lg w-100 text-white btn shadow">Log In <i class="fas fa-arrow-right float-end mt-1"></i></button>
                </form>
            </div>
        </div>
    </div>
    <?php include_once '../templates/footer.php' ?>
</body>

</html>