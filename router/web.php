<?php

require_once '../classes/user.php';
require_once '../classes/category.php';
require_once '../classes/books.php';
require_once '../classes/request.php';
require_once '../sendmail.php';

// get request
$type = $_POST['type'];

if ($type == 'delete-user') {
    $id = $_POST['id'];
    return User::delete($id);
}

if ($type == 'delete-books') {
    $id = $_POST['id'];
    return Book::delete($id);
}


if ($type == 'delete-categ') {
    $id = $_POST['id'];
    return Category::delete($id);
}

if ($type == 'add-categ') {
    $description = $_POST['desc'];
    return Category::createCategory($description);
}

if ($type == 'get-user-edit') {
    $id = $_POST['id'];
    return User::getUser($id);
}

if ($type == 'get-book-edit') {
    $id = $_POST['id'];
    return Book::getBook($id);
}

if ($type == 'approved-book') {
    $tr_id = $_POST['tr_id'];
    $book_id = $_POST['book_id'];
    return Request::approved_book($tr_id, $book_id);
}

if ($type == 'declined-book') {
    $tr_id = $_POST['tr_id'];
    return Request::declined_book($tr_id);
}


if ($type == 'returned-book') {
    $tr_id = $_POST['tr_id'];
    $book_id = $_POST['book_id'];
    return Request::returned_book($tr_id, $book_id);
}


if ($type == 'notify') {
    $name = $_POST['name'];
    $sub = 'Book Borrowed is Due';
    $body = 'Hello ' . $name . ',<br><br>You are receiving this email to notify you that your borrowed book is already due. Please return to the library as soon as possible.<br><br>Thank You.';
    $email = $_POST['email'];

    if (setData($sub, $body, $email, $name)) {
        echo 1;
    } else echo 2;
}
