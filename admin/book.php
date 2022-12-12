<?php
session_start();
require_once '../classes/category.php';
require_once '../classes/books.php';
require_once '../classes/auth.php';

if ($role = Auth::checkLogin()) {
    if ($role != 1) {
        header("Location: ../authentication/login");
    }
}

if (isset($_GET['type'])) {
    $type = $_GET['type'];
} else {
    $type = 1;
}

$imageError = '';
$pdfError = '';

if (isset($_POST['submit'])) {

    // ==================================================
    $target_dir = "../uploads/";
    $imgId = uniqid();
    $target_file = $target_dir . $imgId . basename($_FILES["fileToUpload"]["name"]);
    $uploadOk = 1;
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

    // Check if image file is a actual image or fake image
    if (isset($_POST["fileToUpload"])) {
        $check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
        if ($check !== false) {
            // echo "File is an image - " . $check["mime"] . ".";
            $uploadOk = 1;
        } else {
            $imageError = "File is not an image.";
            $uploadOk = 0;
        }
    }
    if (file_exists($target_file)) {
        $imageError = "Sorry, file already exists.";
        $uploadOk = 0;
    }
    if (
        $imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
        && $imageFileType != "gif"
    ) {
        $imageError = "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
        $uploadOk = 0;
    }

    // Check if $uploadOk is set to 0 by an error
    if ($uploadOk == 0) {
        $imageError = "Sorry, your file was not uploaded.";
        // if everything is ok, try to upload file
    }

    if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
        // echo "The file " . htmlspecialchars(basename($_FILES["fileToUpload"]["name"])) . " has been uploaded.";
    } else {
        $imageError = "Sorry, there was an error uploading your file.";
    }

    $withPDF = false;

    if (!empty($_FILES["pdf"]['name'])) {
        $withPDF = true;
        $target_dire = "../uploads/pdf/";
        $imgIds = uniqid();
        $target_file1 = $target_dire . $imgIds . basename($_FILES["pdf"]["name"]);
        $pdfOk = 1;
        $imageFileType = strtolower(pathinfo($target_file1, PATHINFO_EXTENSION));

        if (
            $imageFileType != "pdf" && $imageFileType != "docx"
        ) {
            $pdfError = "Sorry, only pdf and docx are allowed.";
            $pdfOk = 0;
        }

        // Check if $uploadOk is set to 0 by an error
        if ($pdfOk == 0) {
            $pdfError = "Sorry, your file was not uploaded.";
            // if everything is ok, try to upload file
        }

        if (move_uploaded_file($_FILES["pdf"]["tmp_name"], $target_file1)) {
            // echo "The file " . htmlspecialchars(basename($_FILES["fileToUpload"]["name"])) . " has been uploaded.";
        } else {
            $pdfError = "Sorry, there was an error uploading your file.";
        }
    }

    $title = testInput($_POST['title']);
    $isbn = testInput($_POST['isbn']);
    $year = testInput($_POST['year']);
    $series = testInput($_POST['series']);
    $author = testInput($_POST['author']);
    $quantity = testInput($_POST['quantity']);
    $category_id = $_POST['category_id'];
    $description = testInput($_POST['description']);

    $insert = array("title" => $title, "isbn" => $isbn, "thumbnail" => $imgId . basename($_FILES["fileToUpload"]["name"]), "year" => $year, "quantity" => $quantity, "description" => $description, "series" => $series, "author" => $author, "category_id" => $category_id);


    if ($withPDF) {
        if (Book::addBooks($insert, $imgIds . basename($_FILES["pdf"]["name"]))) {
            $_SESSION['status'] = "EBook Successfully Add";
            $_SESSION['status_code'] = "success";
            $_SESSION['message'] = "Thank you!";
        }
    } else {
        if (Book::addBooks($insert)) {
            $_SESSION['status'] = "Successfully Add";
            $_SESSION['status_code'] = "success";
            $_SESSION['message'] = "Thank you!";
        }
    }
}

function testInput($data)
{
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}
$categoryResult = Category::getAllCategories();
$categoryResult2 = Category::getAllCategories();
$categoryResult4 = Category::getAllCategories();

switch ($type) {
    case 1:
        $books = Book::getAllBooks();
        break;
    case 2:
        $books = Book::getAllEbooks();
        break;
    default:
        $books = Book::getAllBooks();
        break;
}


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
                <div class="d-flex justify-content-end align-items-center">
                    <div class="h5 fw-bold me-auto mb-0">Book Management</div>
                    <select name="" id="" class="form-select w-auto me-2" onchange="window.location.href = '?type=' + $(this).val()">
                        <option value="1" <?= ($type == 1) ? 'selected' : '' ?>>Books</option>
                        <option value="2" <?= ($type == 2) ? 'selected' : '' ?>>E-Books</option>
                    </select>
                    <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#manageCategories">
                        Manage Categories
                    </button>

                    <button type="button" class="btn btn-primary  ms-2" data-bs-toggle="modal" data-bs-target="#exampleModal">
                        Add Books <i class="fas fa-plus-circle ms-2"></i>
                    </button>
                    <hr>
                </div>
                <hr>
                <div class="row">
                    <div class="col-md-12">
                        <table id="example" class="table table-sm table-striped" style="width:100%">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Cover</th>
                                    <th>Title</th>
                                    <th>ISBN</th>
                                    <th>Year</th>
                                    <th>Author</th>
                                    <th>Available Copies</th>

                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php

                                if ($books->num_rows > 0) {
                                    $count = 0;
                                    while ($row = $books->fetch_assoc()) {
                                ?>
                                        <tr valign="middle" id="book-<?= $row['id'] ?>">
                                            <td><?= ++$count ?></td>
                                            <td><img src="../uploads/<?= $row['thumbnail'] ?>" alt="" class="thumbnail"></td>
                                            <td><?= $row['title'] ?></td>
                                            <td><?= $row['isbn'] ?></td>
                                            <td><?= $row['year'] ?></td>
                                            <td><?= $row['author'] ?></td>
                                            <td><?= $row['quantity'] ?></td>
                                            <td>
                                                <div class="dropdown">
                                                    <button class="btn py-0 " type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                                        <i class="fas fa-ellipsis-v"></i>
                                                    </button>
                                                    <ul class="dropdown-menu">
                                                        <li><a class="dropdown-item" data-bs-toggle="modal" data-bs-target="#view" onclick="viewRecord('get-user-edit', <?= $row['id'] ?>)" href="alumni-view?id=<?= $row['id'] ?>">View</a></li>
                                                        <li><a class="dropdown-item" data-bs-toggle="modal" data-bs-target="#editBooks" onclick="editRecord('get-book-edit', <?= $row['id'] ?>)" href="#">Edit</a></li>
                                                        <li><a class="dropdown-item" onclick="deleteBooks('delete-books', <?= $row['id'] ?>)">Delete</a></li>
                                                    </ul>
                                                </div>
                                            </td>
                                        </tr>
                                <?php }
                                } ?>
                            </tbody>

                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <!-- Modal -->
    <div class="modal fade" id="manageCategories" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-md">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Manage Categories</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <div class="row">
                        <form action="?type=<?= $type ?>" method="POST" id="add_categ_form">
                            <div class="mb-3">
                                <label for="" class="form-label">Add Category</label>
                                <input type="text" id="desc" name="description" class="form-control" placeholder="Enter here" required>
                            </div>
                            <div class="d-flex justify-content-end">
                                <button type="submit" name="add_categ" class="btn btn-md btn-success">Submit</button>
                            </div>
                        </form>

                        <div class="p-2 mt-4">
                            <div class="h6 ms-1 fw-bold">
                                Categories:
                            </div>
                            <ul class="list-group" id="categ-wrapper">

                                <?php
                                if ($categoryResult->num_rows > 0) {
                                    while ($row = $categoryResult->fetch_assoc()) {

                                ?>
                                        <li class="list-group-item" id="categ-<?= $row['id'] ?>">
                                            <div class="d-flex justify-content-between">
                                                <div>
                                                    <?= $row['description'] ?>
                                                </div>
                                                <div>
                                                    <i class="fas fa-trash text-danger" onclick="delete_category('delete-categ', '<?= $row['id'] ?>')" style="cursor: pointer;"></i>
                                                </div>
                                            </div>
                                        </li>
                                    <?php
                                    }
                                } else {
                                    ?>

                                <?php
                                }
                                ?>


                            </ul>
                        </div>
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
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Add Book</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="" method="POST" enctype="multipart/form-data">
                    <div class="modal-body">
                        <div class="row">

                            <div class="h4 mb-4 fw-bold text-muted">
                                Book Information
                            </div>
                            <hr>
                            <div class="col-md-6 py-2">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" role="switch" id="flexSwitchCheckDefault" onclick="$('.e-book-input').toggleClass('d-none')">
                                    <label class="form-check-label" for="flexSwitchCheckDefault">E-Book</label>
                                </div>
                            </div>
                            <div class="col-md-6 ">
                                <div class="mb-4 d-none e-book-input" id="upload-pdf">
                                    <label class="form-label">PDF File</label>
                                    <input type="file" name="pdf" accept="" class="form-control">
                                </div>

                            </div>
                            <div class="col-md-6">
                                <div class="mb-4">
                                    <label class="form-label">Book name:</label>
                                    <input type="text" name="title" class="form-control lg" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-4">
                                    <label class="form-label">ISBN</label>
                                    <input type="text" name="isbn" class="form-control lg" required>
                                </div>
                            </div>



                            <div class="col-md-6">
                                <div class="mb-4">
                                    <label class="form-label">Cover</label>
                                    <input type="file" name="fileToUpload" class="form-control lg">
                                </div>
                            </div>



                            <div class="col-md-6">

                                <div class="mb-4">
                                    <label class="form-label">Year</label>
                                    <select name="year" class="form-select  mb-3" aria-label=". example">

                                        <?php
                                        $years = range(1800, strftime("%Y", time()));
                                        foreach ($years as $year) : ?>
                                            <option value="<?php echo $year; ?>"><?php echo $year; ?></option>
                                        <?php endforeach; ?>
                                    </select>


                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-4">
                                    <label class="form-label">Series</label>
                                    <input type="number" name="series" class="form-control lg" required>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="mb-4">
                                    <label class="form-label">Author</label>
                                    <input type="text" name="author" class="form-control lg" required>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="mb-4">
                                    <label class="form-label">Category</label>
                                    <select name="category_id" class="form-select  mb-3" aria-label="form-select-lg example">
                                        <?php
                                        if ($categoryResult2->num_rows > 0) {
                                            while ($row = $categoryResult2->fetch_assoc()) {

                                        ?>
                                                <option value="<?= $row['id'] ?>"><?= $row['description'] ?></option>
                                        <?php
                                            }
                                        }
                                        ?>
                                    </select>


                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-4  e-book-input">
                                    <label class="form-label">Quantity</label>
                                    <input type="name" name="quantity" class="form-control lg">
                                </div>
                            </div>


                            <div class="col-md-12">
                                <div class="form-floating">
                                    <textarea class="form-control" name="description" placeholder="Leave a comment here" id="floatingTextarea2" style="height: 100px"></textarea>
                                    <label for="floatingTextarea2">Description</label>
                                </div>
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
    <div class="modal fade" id="editBooks" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Edit Book</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="" method="POST" enctype="multipart/form-data">
                    <div class="modal-body">
                        <div class="row">
                            <div class="h4 mb-4 fw-bold text-muted">
                                Book Information
                            </div>
                            <hr>
                            <input type="hidden" id="book_id">
                            <div class="col-md-6">
                                <div class="mb-4">
                                    <label class="form-label">Book name:</label>
                                    <input id="title" type="text" name="title" class="form-control lg" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-4">
                                    <label class="form-label">ISBN</label>
                                    <input id="isbn" type="text" name="isbn" class="form-control lg" required>
                                </div>
                            </div>

                            <div class="col-md-6">

                                <div class="mb-4">
                                    <label class="form-label">Year</label>
                                    <select id="year" name="year" class="form-select  mb-3" aria-label=".form-select-lg example">

                                        <?php
                                        $years = range(1800, strftime("%Y", time()));
                                        foreach ($years as $year) : ?>
                                            <option value="<?php echo $year; ?>"><?php echo $year; ?></option>
                                        <?php endforeach; ?>
                                    </select>


                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-4">
                                    <label class="form-label">Series</label>
                                    <input id="series" type="number" name="series" class="form-control lg" required>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="mb-4">
                                    <label class="form-label">Quantity</label>
                                    <input id="quantity" type="number" name="quantity" class="form-control lg" required>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="mb-4">
                                    <label class="form-label">Author</label>
                                    <input id="author" type="text" name="author" class="form-control lg" required>
                                </div>
                            </div>
                            <!-- 
                            <div class="col-md-6">
                                <div class="mb-4">
                                    <label class="form-label">Author</label>
                                    <input id="categ_id" type="text" name="author" class="form-control lg" required>
                                </div>
                            </div> -->

                            <div class="col-md-4">
                                <div class="mb-4">
                                    <label class="form-label">Category</label>
                                    <select id="categ_id" name="category_id" class="form-select  mb-3" aria-label="form-select-lg example">
                                        <?php
                                        if ($categoryResult4->num_rows > 0) {
                                            while ($row = $categoryResult4->fetch_assoc()) {

                                        ?>
                                                <option value="<?= $row['id'] ?>"><?= $row['description'] ?></option>
                                        <?php
                                            }
                                        }
                                        ?>
                                    </select>


                                </div>
                            </div>


                            <div class="col-md-12">
                                <div class="form-floating">
                                    <textarea id="description" class="form-control" name="description" placeholder="Leave a comment here" id="floatingTextarea2" style="height: 100px"></textarea>
                                    <label for="floatingTextarea2">Description</label>
                                </div>
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



    <?php include_once '../templates/footer.php' ?>
    <script>
        $(document).ready(function() {
            $('#example').DataTable();
        });

        function delete_category(type, id) {

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
                            $('#categ-' + id).remove();
                            Swal.fire({
                                title: 'Successfully Deleted',
                                icon: 'success',
                                showCancelButton: false,
                                confirmButtonColor: '#3085d6',
                                cancelButtonColor: '#d33',
                                confirmButtonText: 'Okay'
                            })
                            if ($('#categ-wrapper li').length == 0) {
                                $('#categ-wrapper').html(`<div class="text-center" id="no-record">No record Found</div>`);
                            }
                        }
                    });
                }
            })
        }

        function deleteBooks(type, id) {
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
                            $('#book-' + id).remove();
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

        $('#add_categ_form').submit(function(e) {
            e.preventDefault();

            $.post('../router/web', {
                type: "add-categ",
                desc: $('#desc').val()
            }, function(response) {
                var result = JSON.parse(response);
                if (result.status == true) {
                    var a =
                        `  <li class="list-group-item" id="categ-${result.id}">
                                            <div class="d-flex justify-content-between">
                                                <div>
                                                     ${$('#desc').val()}
                                                </div>
                                                <div>
                                                    <i class="fas fa-trash text-danger" onclick="delete_category('delete-categ', '${result.id}')" style="cursor: pointer;"></i>
                                                </div>
                                            </div>
                                        </li>`

                    $('#categ-wrapper').append(a);
                    Swal.fire({
                        title: 'Successfully Added',
                        icon: 'success',
                        showCancelButton: false,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Okay'
                    })

                    $('#desc').val("");
                }
                console.log(response)
            })
        })


        function editRecord(type, id) {
            $.post('../router/web.php', {
                type,
                id
            }, function(response) {
                var result = JSON.parse(response);
                $('#book_id').val(result.id);
                $('#title').val(result.title);
                $('#isbn').val(result.isbn);
                $('#year').val(result.year);
                $('#quantity').val(result.quantity);
                $('#series').val(result.series);
                $('#author').val(result.author);
                $('#categ_id').val(result.category_id);
                $('#description').val(result.description);



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