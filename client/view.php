<?php
session_start();
require_once '../classes/request.php';
require_once '../classes/books.php';
require_once '../classes/auth.php';

if ($role = Auth::checkLogin()) {
    if ($role == 1) {
        header("Location: ../authentication/login");
    }
}

$user_id = $_SESSION['user_id'];
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

if (isset($_POST['request'])) {
    $due_date = $_POST['date_return'];

    $insert = array("duedate" => $due_date, 'user_id' => $user_id, 'book_id' => $id);
    if (Request::request_book($insert)) {
        $_SESSION['status'] = "Successfully Requested";
        $_SESSION['status_code'] = "success";
        $_SESSION['message'] = "Thank you!";
    }
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
                <li class="breadcrumb-item"><a href="books" class="text-decoration-none">All Books</a></li>
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
                    <div class="h6 fw-bold mt-3">Available Copies</div>
                    <div class="display-5">
                        <?= $row['quantity'] ?>
                    </div>
                    <div class="d-flex">
                        <button type="button" class="btn btn-dark w-100 mt-3 me-2" data-bs-toggle="modal" data-bs-target="#exampleModal">
                            Request
                        </button>
                        <a href="books" class="btn btn-outline-dark w-100 mt-3">Cancel</a>
                    </div>
                </div>


            </div>
            <div class="col-md-3">

            </div>
        </div>
    </div>

    <!-- Modal -->

    <!-- Modal -->
    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Request Book</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="" method="POST">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="" class="form-label">Date of Return: </label>
                            <input id="datepicker" type="date" name="date_return" class="form-control" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" name="request" class="btn btn-primary">Request</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <?php include_once '../templates/footer.php' ?>

    <?php
    if (isset($_SESSION['status']) && $_SESSION['status'] != '') {
    ?>

        <script>
            Swal.fire({
                title: "<?php echo $_SESSION['status'] ?>",
                icon: "<?php echo $_SESSION['status_code'] ?>",
                text: "<?php echo $_SESSION['message'] ?>",
                button: "Okay",
            });
        </script>

    <?php
        unset($_SESSION['status']);
    }
    ?>

    <script>
        var dateToday = new Date();
        $(function() {
            $("#datepicker").datepicker({
                numberOfMonths: 3,
                showButtonPanel: true,
                minDate: dateToday
            });
        });
    </script>

</body>

</html>