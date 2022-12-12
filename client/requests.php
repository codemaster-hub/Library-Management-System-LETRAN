<?php
session_start();
require_once '../classes/request.php';
require_once '../classes/auth.php';

if ($role = Auth::checkLogin()) {
    if ($role == 1) {
        header("Location: ../authentication/login");
    }
}
$books = Request::pending_books();

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
        <div class="mt-4 d-flex align-items-md-center align-items-sm-start align-items-start  mb-3 flex-md-row flex-sm-column flex-column">
            <div class="h2 mb-0">Requested Books</div>
        </div>
        <div class="row">

            <?php
            if ($books->num_rows > 0) {
                while ($row = $books->fetch_assoc()) {

                    $date1 = new DateTime($row['duedate']);
                    $date2 = new DateTime(date('Y-m-d'));
                    $days  = $date2->diff($date1)->format('%r%a');
                    if ($days < 0 && $row['transaction_status'] == 2) {
                        $str = '<span class="badge text-bg-danger w-100">Overdue</span>';
                    } else if ($days == 0  && $row['transaction_status'] == 2) {
                        $str = '<span class="badge text-bg-warning w-100">Due</span>';
                    } else {
                        $str = '<div class="smalltxt fw-bold mt-2">Duedate</div><div class="smalltxt">' . date('M d, Y', strtotime($row['duedate'])) . '</div>';
                    }
            ?>
                    <div class="col-md-2 col-sm-4 col-4">
                        <a class="card border-0 mb-2 book-card text-decoration-none text-dark" href="view?id=<?= $row['book_id'] ?>">
                            <div class="card-body">
                                <div class="text-center mb-2">
                                    <img src="../uploads/<?= $row['thumbnail'] ?>" alt="" class="book-cover">
                                </div>
                                <div class="book-title fw-bold">
                                    <marquee behavior="" direction="" scrollamount="3"><?= $row['title'] ?></marquee>
                                </div>
                                <div class="author"><?= $row['author'] . " , " . $row['year'] ?></div>
                                <?php
                                if ($row['transaction_status'] == 1) {
                                ?>
                                    <div class="badge bg-warning fw-light w-100">Pending </div>
                                <?php
                                } elseif ($row['transaction_status'] == 2) {
                                ?>
                                    <div class="badge bg-success fw-light w-100">Issued </div>
                                <?php
                                } elseif ($row['transaction_status'] == 3) {
                                ?>
                                    <div class="badge bg-primary fw-light w-100">Returned </div>
                                <?php
                                } elseif ($row['transaction_status'] == 4) {
                                ?>
                                    <div class="badge bg-danger fw-light w-100">Declined </div>
                                <?php
                                }
                                ?>

                                <?= $str ?>
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