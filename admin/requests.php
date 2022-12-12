<?php
session_start();
require_once '../classes/request.php';
require_once '../classes/auth.php';

if ($role = Auth::checkLogin()) {
    if ($role != 1) {
        header("Location: ../authentication/login");
    }
}

$user_id = $_SESSION['user_id'];

if (isset($_GET['type'])) {
    $type = $_GET['type'];
} else {
    $type = 1;
}

$requests = Request::request_books_admin($type);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <?php include_once '../templates/header.php' ?>
    <title>Admin</title>
    <link rel="stylesheet" href="admin.css?">
    <style>
        .thumbnail {
            width: 70px;
            aspect-ratio: 3/4;
            object-fit: cover;
            border-radius: 5px;
        }
    </style>
</head>

<body>
    <div id="a-wrapper">
        <?php include_once '../templates/admin_sidebar.php' ?>
        <div id="a-main">
            <?php include_once '../templates/admin_nav.php' ?>
            <div id="content">


                <!-- Button trigger modal -->
                <div class="d-flex justify-content-between align-items-center ">
                    <div class="h5 fw-bold mb-0">Book Requests</div>
                    <div class="d-flex jsutify-content-end align-items-center">
                        <div class="h6 mb-0 me-3">
                            <i class="fas fa-filter"></i> Filter
                        </div>
                        <div>
                            <select name="" id="" class="form-control" onchange="window.location.href = '?type=' + $(this).val()">
                                <option value="1" <?= ($type == 1) ? 'selected' : '' ?>>Pending</option>
                                <option value="2" <?= ($type == 2) ? 'selected' : '' ?>>Issued</option>
                                <option value="3" <?= ($type == 3) ? 'selected' : '' ?>>Returned</option>
                                <option value="4" <?= ($type == 4) ? 'selected' : '' ?>>Declined</option>
                            </select>
                        </div>
                    </div>

                </div>
                <hr>
                <div class="row">
                    <div class="col-md-12">
                        <table id="example" class="table table-sm table-striped" style="width:100%">
                            <thead>
                                <tr valign="top">
                                    <th>#</th>
                                    <th>Cover</th>
                                    <th>Title</th>
                                    <th>Available Copies</th>
                                    <th>Requestor</th>
                                    <?php if ($type == 1) { ?> <th>Date Requested</th><?php } ?>
                                    <?php if ($type == 2) { ?> <th>Date Issued</th><?php } ?>
                                    <?php if ($type == 4) { ?> <th>Date Declined</th><?php } ?>
                                    <?php if ($type == 3) { ?> <th>Date Returned</th><?php } ?>
                                    <?php if ($type == 2 || $type == 1) { ?><th>Duedate<br><span class="smalltxt fw-light text-nowrap">Return Date</span></th><?php } ?>
                                    <?php if ($type == 1 || $type == 2) : ?> <th>Actions</th><?php endif ?>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                if ($requests->num_rows > 0) {
                                    $count = 0;
                                    while ($row = $requests->fetch_assoc()) {

                                        $date1 = new DateTime($row['duedate']);
                                        $date2 = new DateTime(date('Y-m-d'));
                                        $days  = $date2->diff($date1)->format('%r%a');
                                        if ($days < 0 && $type != 1) {
                                            $str = '<span class="badge text-bg-danger">Overdue</span>';
                                            $notifyLink = '<li><a class="dropdown-item" data-name="' . $row['first_name'] . ' ' .  $row['last_name'] . '" data-email="' . $row['email'] . '" onclick="notify($(this))">Notify</a></li>';
                                        } else if ($days == 0  && $type != 1) {
                                            $str = '<span class="badge text-bg-warning">Due</span>';
                                            $notifyLink = '<li><a class="dropdown-item">Notify</a></li>';
                                        } else {
                                            $str = date('M d, Y', strtotime($row['duedate']));
                                            $notifyLink = '';
                                        }
                                ?>
                                        <tr valign="middle">
                                            <td><?= ++$count ?></td>
                                            <td><img src="../uploads/<?= $row['thumbnail'] ?>" alt="" class="thumbnail"></td>
                                            <td><?= $row['title'] ?></td>
                                            <td><?= $row['quantity'] ?></td>
                                            <td><?= $row['first_name'] . " " . $row['middle_name'] . " " . $row['last_name'] ?></td>
                                            <?php if ($type == 1) { ?> <td><?= date('M d, Y', strtotime($row['date_request'])) ?></td><?php } ?>
                                            <?php if ($type == 2) { ?> <td><?= date('M d, Y', strtotime($row['date_issued'])) ?></td><?php } ?>
                                            <?php if ($type == 4) { ?> <td><?= date('M d, Y', strtotime($row['date_declined'])) ?></td><?php } ?>
                                            <?php if ($type == 3) { ?> <td><?= date('M d, Y', strtotime($row['date_returned'])) ?></td><?php } ?>
                                            <?php if ($type == 2 || $type == 1) { ?> <td><?= $str ?></td><?php } ?>
                                            <?php if ($type == 1 || $type == 2) : ?>
                                                <td>
                                                    <div class="dropdown">
                                                        <button class="btn py-0 " type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                                            <i class="fas fa-ellipsis-v"></i>
                                                        </button>
                                                        <ul class="dropdown-menu">
                                                            <?= $notifyLink ?>
                                                            <li class="<?= ($type == 2 || $type == 3 || $type == 4) ? 'd-none' : '' ?>"><a onclick="approved_book('approved-book', <?= $row['transaction_id'] ?>,'<?= $row['book_id'] ?>')" class="dropdown-item">Issue</a></li>
                                                            <li><a class=" <?= ($type == 1) ? '' : 'd-none' ?> dropdown-item" onclick="declined_book('declined-book', <?= $row['transaction_id'] ?>)">Decline</a></li>
                                                            <li><a class=" <?= ($type == 2) ? '' : 'd-none' ?> dropdown-item" onclick="returned_book('returned-book', <?= $row['transaction_id'] ?>,'<?= $row['book_id'] ?>')">Return</a></li>
                                                        </ul>
                                                    </div>
                                                </td>
                                            <?php endif ?>

                                        </tr>

                                <?php
                                    }
                                }
                                ?>
                            </tbody>

                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>



    <?php include_once '../templates/footer.php' ?>
    <script>
        $(document).ready(function() {
            $('#example').DataTable();
        });

        const notify = (e) => {
            Swal.fire({
                title: 'Notify User?',
                text: 'Email will be sent to user.',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes'
            }).then((result) => {
                if (result.isConfirmed) {
                    //SA confirm notify?
                    let name = e.attr('data-name');
                    let email = e.attr('data-email');
                    let type = 'notify';
                    $.ajax({
                        url: '../router/web',
                        type: 'POST',
                        data: {
                            type,
                            name,
                            email
                        },
                        beforeSend: function() {
                            Swal.fire(
                                'Sending Email',
                                'Any moment now...',
                                'info'
                            )
                        },
                        success: function(response) {
                            if (response) {
                                Swal.fire({
                                    position: 'top-end',
                                    icon: 'success',
                                    title: 'Notification sent to ' + name,
                                    showConfirmButton: false,
                                    timer: 1500
                                })
                            }
                        }
                    });
                }
            })

        }

        approved_book = (type, tr_id, book_id) => {
            Swal.fire({
                title: 'Are you sure to approve request?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.post('../router/web', {
                        type,
                        tr_id,
                        book_id,
                    }, function(response) {
                        if (response == 1) {
                            Swal.fire({
                                    title: 'Successfully Approved',
                                    icon: 'success',
                                    showCancelButton: false,
                                    confirmButtonColor: '#3085d6',
                                    cancelButtonColor: '#d33',
                                    confirmButtonText: 'Okay'
                                })
                                .then((result) => {
                                    if (result.isConfirmed) {
                                        location.reload()
                                    }
                                })
                        }
                    });
                }
            })
        }

        returned_book = (type, tr_id, book_id) => {
            Swal.fire({
                title: 'Is book returned?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.post('../router/web', {
                        type,
                        tr_id,
                        book_id,
                    }, function(response) {
                        if (response == 1) {
                            Swal.fire({
                                    title: 'Successfully Returned',
                                    icon: 'success',
                                    showCancelButton: false,
                                    confirmButtonColor: '#3085d6',
                                    cancelButtonColor: '#d33',
                                    confirmButtonText: 'Okay'
                                })
                                .then((result) => {
                                    if (result.isConfirmed) {
                                        location.reload()
                                    }
                                })
                        }
                    });
                }
            })
        }


        declined_book = (type, tr_id) => {
            Swal.fire({
                title: 'Are you sure to decline request?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.post('../router/web', {
                        type,
                        tr_id,
                    }, function(response) {
                        if (response == 1) {
                            Swal.fire({
                                    title: 'Successfully Declined',
                                    icon: 'success',
                                    showCancelButton: false,
                                    confirmButtonColor: '#3085d6',
                                    cancelButtonColor: '#d33',
                                    confirmButtonText: 'Okay'
                                })
                                .then((result) => {
                                    if (result.isConfirmed) {
                                        location.reload()
                                    }
                                })
                        }
                    });
                }
            })
        }
    </script>

    <?php
    if (isset($_SESSION['status']) && $_SESSION['status'] != '') {
    ?>

        <script>
            Swal.fire({
                title: " <?php echo $_SESSION['status'] ?>",
                icon: "<?php echo $_SESSION['status_code'] ?>",
                text: "<?php echo $_SESSION['message'] ?>",
                button: "Okay",
            });
        </script>

    <?php
        unset($_SESSION['status']);
    }
    ?>
</body>

</html>