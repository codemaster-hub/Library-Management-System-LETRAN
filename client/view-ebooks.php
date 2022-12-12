<?php
session_start();
require_once '../classes/books.php';
require_once '../classes/auth.php';

if ($role = Auth::checkLogin()) {
    if ($role == 1) {
        header("Location: ../authentication/login");
    }
}

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $books = Book::viewBook($id);
    if ($books->num_rows == 0) {
        header('location:books');
    } else {
        $row = $books->fetch_assoc();
    }
} else {
    // redirect
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <?php include_once '../templates/header.php' ?>
    <title>Client</title>
    <link rel="stylesheet" href="client.css?d=<?= time() ?>">
</head>

<body>
    <?php include_once '../templates/client_nav.php' ?>

    <div class="container">
        <nav aria-label="breadcrumb" class="mt-3">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="index" class="text-decoration-none">Home</a></li>
                <li class="breadcrumb-item"><a href="e-books" class="text-decoration-none">All E-Books</a></li>
                <li class="breadcrumb-item active" aria-current="page"><?= $row['title'] ?></li>
            </ol>
        </nav>
        <div class="d-flex align-items-md-center align-items-sm-start align-items-start  mb-3 flex-md-row flex-sm-column flex-column">
            <div class="h2 mb-0"><?= $row['title'] ?></div>
        </div>


        <div class="row">
            <div class="col-md-4">
                <img src="../uploads/<?= $row['thumbnail'] ?>" alt="" class="view-book-img">
            </div>
            <div class="col-md-5">
                <div class="my-3">
                    <div class="h6 mb-0 fw-bold">Author</div>
                    <div class="h5 mb-3"><?= $row['author'] ?></div>
                    <div class="h6 fw-bold">ISBN</div>
                    <div class="h5 mb-3"><?= $row['isbn'] ?></div>
                    <div class="h6 fw-bold">Desription</div>
                    <div class="h6 lh-base"><?= $row['description'] ?></div>

                    <div class="d-flex">
                        <a href="../uploads/pdf/<?= $row['e_books'] ?>" class="btn btn-dark w-100 mt-3 me-2">Download E-Book</a>
                    </div>
                </div>


            </div>
            <div class="col-md-3">

            </div>
        </div>
    </div>
    <?php include_once '../templates/footer.php' ?>
</body>

</html>