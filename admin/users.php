<?php
require_once '../classes/user.php';
require_once '../classes/auth.php';

if ($role = Auth::checkLogin()) {
    if ($role != 1) {
        header("Location: ../authentication/login");
    }
}

if (isset($_POST['submit'])) {

    if (md5($_POST['password']) != md5($_POST['confirm_password'])) {
        $_SESSION['status'] = "Password is doesn't match";
        $_SESSION['status_code'] = "error";
        $_SESSION['message'] = "Thank you!";
    } else {
        $school_id = uniqid();
        $email = testInput($_POST['email']);
        $username = testInput($_POST['username']);
        $password = md5($_POST['password']);
        $first_name = testInput($_POST['first_name']);
        $middle_name = testInput($_POST['middle_name']);
        $last_name = testInput($_POST['last_name']);
        $contact = testInput($_POST['contact']);
        $address = testInput($_POST['address']);
        $birthday = $_POST['birthday'];
        $gender = $_POST['gender'];
        $role = $_POST['role'];



        $insert = array("school_id" => $school_id, "email" => $email, "username" => $username, "password" => $password, "first_name" => $first_name, "middle_name" => $middle_name, "last_name" => $last_name, "contact" => $contact, "address" => $address, "birthday" => $birthday, "gender" => $gender, "role" => $role);

        if (User::createUser($insert)) {
            $_SESSION['status'] = "Successfully Add";
            $_SESSION['status_code'] = "success";
            $_SESSION['message'] = "Thank you!";
        }
    }
}

if (isset($_POST['edit_submit'])) {
    $id = $_POST['id'];
    $first_name = testInput($_POST['first_name']);
    $middle_name = testInput($_POST['middle_name']);
    $last_name = testInput($_POST['last_name']);
    $contact = testInput($_POST['contact']);
    $address = testInput($_POST['address']);
    $birthday = $_POST['birthday'];
    $gender = $_POST['gender'];

    $update = array("id" => $id, "first_name" => $first_name, "middle_name" => $middle_name, "last_name" => $last_name, "contact" => $contact, "address" => $address, "birthday" => $birthday, "gender" => $gender);

    if (User::updateUser($update)) {
        $_SESSION['status'] = "Successfully Updated";
        $_SESSION['status_code'] = "success";
        $_SESSION['message'] = "Thank you!";
    }
}

function testInput($data)
{
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}
$usersResult = User::getAllUsers();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <?php include_once '../templates/header.php' ?>
    <title>Admin</title>
    <link rel="stylesheet" href="admin.css?">
</head>

<body>
    <div id="a-wrapper">
        <?php include_once '../templates/admin_sidebar.php' ?>
        <div id="a-main">
            <?php include_once '../templates/admin_nav.php' ?>
            <div id="content">
                <div class="d-flex justify-content-between align-items-center">
                    <div class="h5 fw-bold mb-0">User Management</div>
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal">
                        Add Account <i class="fas fa-plus-circle ms-2"></i>
                    </button>
                </div>
                <hr>
                <div class="row">
                    <div class="col-md-12">
                        <table id="example" class="table table-sm table-striped" style="width:100%">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Full Name</th>
                                    <th>Email</th>
                                    <th>Contact No.</th>
                                    <th>Role</th>
                                    <th>Gender</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>

                                <?php

                                if ($usersResult->num_rows > 0) {
                                    $count = 0;
                                    while ($row = $usersResult->fetch_assoc()) {
                                        switch ($row['status']) {
                                            case '1':
                                                $status = '<span class="badge rounded-pill text-bg-success fw-normal">Active</span>';
                                                break;
                                            case '2':
                                                $status = '<span class="badge rounded-pill text-bg-danger fw-normal">Inactive</span>';
                                                break;
                                        }

                                        switch ($row['role']) {
                                            case '1':
                                                $status1 = '<span class="badge rounded-pill text-bg-success fw-normal">Administrator</span>';
                                                break;
                                            case '2':
                                                $status1 = '<span class="badge rounded-pill text-bg-warning fw-normal">Staff</span>';
                                                break;
                                            case '3':
                                                $status1 = '<span class="badge rounded-pill text-bg-danger fw-normal">Student</span>';
                                                break;
                                        }

                                        switch ($row['gender']) {
                                            case '1':
                                                $status2 = '<span class="badge rounded-pill text-bg-primary fw-normal">Male</span>';
                                                break;
                                            case '2':
                                                $status2 = '<span class="badge rounded-pill text-bg-danger fw-normal">Female</span>';
                                                break;
                                        }
                                ?>
                                        <tr valign="middle" id="user-<?= $row['id'] ?>">
                                            <td><?= ++$count ?></td>
                                            <td><?= $row['first_name'] . ' ' . $row['last_name'] ?></td>

                                            <td><?= $row['email'] ?></td>
                                            <td><?= $row['contact'] ?></td>
                                            <td><?= $status1 ?></td>

                                            <td><?= $status2 ?></td>
                                            <td><?= $status ?></td>
                                            <td>
                                                <div class="dropdown">
                                                    <button class="btn py-0 " type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                                        <i class="fas fa-ellipsis-v"></i>
                                                    </button>
                                                    <ul class="dropdown-menu">
                                                        <li><a class="dropdown-item" data-bs-toggle="modal" data-bs-target="#view" onclick="viewRecord('get-user-edit', <?= $row['id'] ?>)" href="alumni-view?id=<?= $row['id'] ?>">View</a></li>
                                                        <li><a class="dropdown-item" data-bs-toggle="modal" data-bs-target="#editUser" onclick="editRecord('get-user-edit', <?= $row['id'] ?>)" href="#">Edit</a></li>
                                                        <li><a class="dropdown-item" onclick="deleteRecord('delete-user', <?= $row['id'] ?>)">Delete</a></li>
                                                    </ul>
                                                </div>
                                            </td>
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


    <!-- Modal -->
    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Add User</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="" method="POST">
                    <div class="modal-body">
                        <div class="row">
                            <div class="h4 mb-4 fw-bold text-muted">
                                Account Information
                            </div>
                            <hr>
                            <div class="col-md-6">
                                <div class="mb-4">
                                    <label class="form-label">Email:</label>
                                    <input type="email" name="email" class="form-control lg" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-4">
                                    <label class="form-label">Username:</label>
                                    <input type="text" name="username" class="form-control lg" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-4">
                                    <label class="form-label">Password:</label>
                                    <input type="text" name="password" class="form-control lg" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-4">
                                    <label class="form-label">Confirm Password:</label>
                                    <input type="text" name="confirm_password" class="form-control lg" required>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <label class="form-label">Role: </label>
                                <select name="role" class="form-select form-select-lg mb-3" aria-label=".form-select-lg example">
                                    <option selected>---</option>
                                    <option value="1">Admin/Librarian</option>
                                    <option value="2">Staff</option>
                                    <option value="3">Student</option>
                                </select>
                            </div>

                            <div class="h4 mb-4 fw-bold text-muted">
                                User Information
                            </div>
                            <hr>
                            <div class="col-md-4">
                                <div class="mb-4">
                                    <label class="form-label">Firstname:</label>
                                    <input type="text" name="first_name" class="form-control lg" required>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-4">
                                    <label class="form-label">Middlename:</label>
                                    <input type="text" name="middle_name" class="form-control lg" required>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-4">
                                    <label class="form-label">Lastname:</label>
                                    <input type="text" name="last_name" class="form-control lg" required>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Birthday: </label>
                                <input type="date" name="birthday" class="form-control lg" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Gender: </label>
                                <select name="gender" class="form-select  mb-3" aria-label="example">
                                    <option selected>---</option>
                                    <option value="1">Male</option>
                                    <option value="2">Female</option>
                                </select>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Contact:</label>
                                <input type="number" name="contact" class="form-control lg" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Address </label>
                                <input type="text" name="address" class="form-control lg" required>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" name="submit" class="btn btn-primary">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="editUser" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Edit User</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="" method="POST">
                    <div class="modal-body">
                        <div class="row">
                            <div class="h4 mb-4 fw-bold text-muted">
                                Account Information
                            </div>
                            <hr>
                            <div class="col-md-6">
                                <div class="mb-4">
                                    <label class="form-label">Email:</label>
                                    <input id="email" type="email" name="email" class="form-control lg" disabled>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-4">
                                    <label class="form-label">Username:</label>
                                    <input type="text" id="username" name="username" class="form-control lg" disabled>
                                </div>
                            </div>

                            <div class="h4 mb-4 fw-bold text-muted">
                                User Information
                            </div>
                            <hr>
                            <input type="hidden" id="id" name="id">
                            <div class="col-md-4">
                                <div class="mb-4">
                                    <label class="form-label">Firstname:</label>
                                    <input id="firstname" type="text" name="first_name" class="form-control lg" required>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-4">
                                    <label class="form-label">Middlename:</label>
                                    <input id="middlename" type="text" name="middle_name" class="form-control lg" required>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-4">
                                    <label class="form-label">Lastname:</label>
                                    <input id="lastname" type="text" name="last_name" class="form-control lg" required>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Birthday: </label>
                                <input id="birthday" type="date" name="birthday" class="form-control lg" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Gender: </label>
                                <select name="gender" id="gender" class="form-select mb-3" aria-label=".form-select-lg example">
                                    <option value="1">Male</option>
                                    <option value="2">Female</option>
                                </select>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Contact:</label>
                                <input id="contact" type="number" name="contact" class="form-control lg" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Address </label>
                                <input id="address" type="text" name="address" class="form-control lg" required>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" name="edit_submit" class="btn btn-primary">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="view" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">View Record</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="" method="POST">
                    <div class="modal-body">
                        <div class="row">
                            <div class="h4 mb-4 fw-bold text-muted">
                                Account Information
                            </div>
                            <hr>
                            <div class="col-md-6">
                                <div class="mb-4">
                                    <label class="form-label">Email:</label>
                                    <input id="email1" type="email" name="email" class="form-control lg" disabled>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-4">
                                    <label class="form-label">Username:</label>
                                    <input type="text" id="username1" name="username" class="form-control lg" disabled>
                                </div>
                            </div>

                            <div class="h4 mb-4 fw-bold text-muted">
                                User Information
                            </div>
                            <hr>

                            <div class="col-md-4">
                                <div class="mb-4">
                                    <label class="form-label">Firstname:</label>
                                    <input id="firstname1" type="text" name="first_name" class="form-control lg" disabled>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-4">
                                    <label class="form-label">Middlename:</label>
                                    <input id="middlename1" type="text" name="middle_name" class="form-control lg" disabled>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-4">
                                    <label class="form-label">Lastname:</label>
                                    <input id="lastname1" type="text" name="last_name" class="form-control lg" disabled>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Birthday: </label>
                                <input id="birthday1" type="date" name="birthday" class="form-control lg" disabled>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Gender: </label>
                                <input id="gender1" type="text" name="gender" class="form-control lg" disabled>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Contact:</label>
                                <input id="contact1" type="number" name="contact" class="form-control lg" disabled>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Address </label>
                                <input id="address1" type="text" name="address" class="form-control lg" disabled>
                            </div>
                        </div>
                    </div>

                </form>
            </div>
        </div>
    </div>

    <?php include_once '../templates/footer.php' ?>
    <script>
        $(document).ready(function() {
            $('#example').DataTable();
        });

        function deleteRecord(type, id) {
            // alert($type);
            // type = 'get-user-edit';
            Swal.fire({
                title: 'Are you sure?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.post('../router/web', {
                        type,
                        id
                    }, function(response) {
                        if (response == 1) {
                            $('#user-' + id).remove();
                        }
                        Swal.fire({
                            title: 'Successfully Deleted',
                            icon: 'success',
                            showCancelButton: false,
                            confirmButtonColor: '#3085d6',
                            cancelButtonColor: '#d33',
                            confirmButtonText: 'Okay'
                        })
                    });
                }
            })
        }

        function editRecord(type, id) {
            $.post('../router/web', {
                type,
                id
            }, function(response) {
                var result = JSON.parse(response);
                $('#id').val(result.id);
                $('#email').val(result.email);
                $('#username').val(result.username);
                $('#firstname').val(result.first_name);
                $('#gender').val(result.gender);
                $('#lastname').val(result.last_name);
                $('#middlename').val(result.middle_name);
                $('#birthday').val(result.birthday);
                $('#address').val(result.address);
                $('#contact').val(result.contact);
            })
        }

        function viewRecord(type, id) {
            $.post('../router/web', {
                type,
                id
            }, function(response) {
                var result = JSON.parse(response);

                var gender_txt = "";


                $('#email1').val(result.email);
                $('#username1').val(result.username);
                $('#firstname1').val(result.first_name);

                $('#lastname1').val(result.last_name);
                $('#middlename1').val(result.middle_name);
                $('#birthday1').val(result.birthday);
                $('#address1').val(result.address);
                $('#contact1').val(result.contact);

                if (result.gender) {
                    gender_txt = "Male";
                } else {
                    gender_txt = "Female";
                }
                $('#gender1').val(gender_txt);
            })
        }
        if (window.history.replaceState) {
            window.history.replaceState(null, null, window.location.href);
        }
    </script>

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
</body>

</html>