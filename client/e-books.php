<?php
session_start();
require_once '../classes/books.php';
require_once '../classes/auth.php';

if ($role = Auth::checkLogin()) {
    if ($role == 1) {
        header("Location: ../authentication/login");
    }
}

if (isset($_GET['keyword'])) {
    $keyword = $_GET['keyword'];
    $books = Book::keywordEbooks($keyword);
} else if (isset($_GET['search'])) {
    $search = $_GET['search'];
    $books = Book::searchEbooks($search);
} else {
    $books = Book::getAllBooksEbooks();
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
                <li class="breadcrumb-item active" aria-current="page">All E-Books</li>
            </ol>
        </nav>
        <div class="d-flex align-items-md-center align-items-sm-start align-items-start  mb-3 flex-md-row flex-sm-column flex-column">
            <div class="h2 mb-0"><i class="far fa-file-pdf me-2"></i> All E-Books</div>
            <form action="" method="GET" class="ms-auto">
                <div class="input-group  search-bar">
                    <input type="text" class="form-control search-bar" name="search" placeholder="Search Book Title" aria-describedby="button-addon2">
                    <button class="btn btn-outline-secondary" type="submit" id="button-addon2"><i class="fas fa-search"></i></button>
                </div>
            </form>
        </div>

        <div class="d-flex justify-content-between mb-3 bg-light p-1 px-3 round-1">
            <a href="?keyword" class="text-decoration-none smalltxt">All</a>
            <?php
            for ($x = 'A'; $x <= 'Z'; $x++) {
                echo '<a href="?keyword=' . $x . '" class="text-decoration-none smalltxt">' . $x . '</a>';
                if ($x == 'Z') break;
            }
            ?>
        </div>
        <div class="row">

            <?php
            if ($books->num_rows > 0) {
                while ($row = $books->fetch_assoc()) {
            ?>
                    <div class="col-md-2 col-sm-4 col-4">
                        <a class="card border-0 mb-2 book-card text-decoration-none text-dark" href="view-ebooks?id=<?= $row['id'] ?>">
                            <div class="card border-0 mb-2 book-card">
                                <div class="card-body">
                                    <div class="text-center mb-2">
                                        <img src="../uploads/<?= $row['thumbnail'] ?>" alt="" class="book-cover">
                                    </div>
                                    <div class="book-title fw-bold">
                                        <marquee behavior="" direction="" scrollamount="3"><?= $row['title'] ?></marquee>
                                    </div>
                                    <div class="author"><?= $row['author'] . " , " . $row['year'] ?></div>
                                    <div class="badge bg-primary fw-light w-100">Available</div>
                                </div>
                            </div>
                        </a>
                    </div>

                <?php
                }
            } else {
                ?>
                <div class="text-center">No Books Founds</div>
            <?php
            }
            ?>

        </div>
    </div>


    <?php include_once '../templates/footer.php' ?>
</body>

</html>