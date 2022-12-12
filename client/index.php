<?php
session_start();
include_once '../extras/quotes.php';
require_once '../classes/auth.php';
require_once '../classes/books.php';

if ($role = Auth::checkLogin()) {
    if ($role == 1) {
        header("Location: ../authentication/login");
    }
}

$q = randomizer();
$d_count = Book::getDashBoardAdmin();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <?php include_once '../templates/header.php' ?>
    <title>Client</title>
    <link rel="stylesheet" href="client.css?">
</head>

<body>
    <?php include_once '../templates/client_nav.php' ?>

    <div class="container">
        <div class="row mt-4">
            <div class="col-md-12">
                <div class="card round-2 mb-3 border-0 banner shadow-sm">
                    <div class="card-body  p-4">
                        <div class="h5">
                            Welcome,
                        </div>
                        <div class="h3 fw-bold"><?= $_SESSION['user_info']['full_name'] ?></div>
                        <hr>
                        <div class="h6 fw-light fst-italic">
                            "<?= $q['quote'] ?>"
                        </div>
                        <div class="h6 text-end text-white ">
                            - <?= $q['author'] ?>
                        </div>
                    </div>
                </div>

            </div>
            <div class="col-md-4">
                <div class="card round-2 mb-3 shadow-sm border">
                    <div class="card-body">
                        <div class="d-flex justify-content-between ">
                            <div class="h3">Books</div>
                            <div class="h1"><i class="fas fa-swatchbook"></i></div>
                        </div>
                        <div class="display-4">
                            <?= $d_count[1] ?>
                        </div>
                    </div>
                </div>

            </div>
            <div class="col-md-4">
                <div class="card round-2 mb-3 shadow-sm border">
                    <div class="card-body">
                        <div class="d-flex justify-content-between ">
                            <div class="h3">E-Books</div>
                            <div class="h1"><i class="far fa-file-pdf"></i></div>
                        </div>
                        <div class="display-4">
                            <?= $d_count[0] ?>
                        </div>
                    </div>
                </div>

            </div>
            <div class="col-md-4">
                <div class="card round-2 mb-3 shadow-sm border">
                    <div class="card-body">
                        <div class="d-flex justify-content-between ">
                            <div class="h3">Borrowed Books</div>
                            <div class="h1"><i class="fas fa-hands-helping"></i></div>
                        </div>
                        <div class="display-4">
                            <?= $d_count[2] ?>
                        </div>
                    </div>
                </div>

            </div>
        </div>

    </div>


    <?php include_once '../templates/footer.php' ?>
</body>

</html>