<?php

require_once '../db/db_config.php';

class Request
{
    //1=pending/request
    //2=approved/request
    //3=returned/request
    //4=declined/request

    public static function request_book($insert)
    {

        global $conn;
        $sql = "INSERT INTO `transactions`(`duedate`,`book_id`, `user_id`, `status`) VALUES ('" . $insert['duedate'] . "','" . $insert['book_id'] . "','" . $insert['user_id'] . "','1')";

        if ($conn->query($sql)) {
            return true;
        } else {
            return false;
        }
    }

    public static function pending_books()
    {

        global $conn;
        $sql = "SELECT a.id AS transaction_id, a.status AS transaction_status, b.id AS book_id, c.id AS user_id, c.first_name, c.middle_name,c.last_name, b.year, b.title, b.thumbnail, b.quantity, b.author, a.duedate FROM transactions AS a JOIN books AS b ON b.id = a.book_id JOIN users AS c ON a.user_id = c.id WHERE c.id = " . $_SESSION['user_id'] . " AND a.status = 1 OR a.status = 2";

        $results = $conn->query($sql);
        return $results;
    }

    public static function history_books()
    {

        global $conn;
        $sql = "SELECT a.id AS transaction_id, a.status AS transaction_status, b.id AS book_id, c.id AS user_id, c.first_name, c.middle_name,c.last_name, b.year, b.title, b.thumbnail, b.quantity, b.author, a.date_returned, a.date_declined, a.duedate FROM transactions AS a JOIN books AS b ON b.id = a.book_id JOIN users AS c ON a.user_id = c.id WHERE c.id = " . $_SESSION['user_id'] . " AND a.status = 3 OR a.status = 4";

        $results = $conn->query($sql);
        return $results;
    }

    public static function request_books_admin($type)
    {
        global $conn;
        $sql = "SELECT a.id AS transaction_id, a.date_request,a.status AS transaction_status, b.id AS book_id, c.id AS user_id, c.email, c.first_name, c.middle_name,c.last_name, b.year, b.title, b.thumbnail, b.quantity, b.author , a.duedate, a.date_returned, a.date_declined, a.date_issued FROM transactions AS a JOIN books AS b ON b.id = a.book_id JOIN users AS c ON a.user_id = c.id WHERE a.status = $type";

        $results = $conn->query($sql);
        return $results;
    }


    public static function approved_book($tr_id, $book_id)
    {
        global $conn;
        $date = date('Y-m-d');
        $sql = "UPDATE `transactions` SET `status`= '2', `date_issued` = '$date' WHERE id = $tr_id";

        if ($conn->query($sql)) {
            $sql_update = "UPDATE books SET quantity=quantity-1 WHERE id='$book_id'";
            mysqli_query($conn, $sql_update);
            echo 1;
        } else {
            echo 2;
        }
    }

    public static function declined_book($tr_id)
    {
        global $conn;
        $date = date('Y-m-d');
        $sql = "UPDATE `transactions` SET `status`= '4', `date_declined` = '$date' WHERE id = $tr_id";

        if ($conn->query($sql)) {
            echo 1;
        } else {
            echo 2;
        }
    }

    public static function returned_book($tr_id, $book_id)
    {
        global $conn;
        $date = date('Y-m-d');
        $sql = "UPDATE `transactions` SET `status`= '3', `date_returned` = '$date' WHERE id = $tr_id";

        if ($conn->query($sql)) {
            $sql_update = "UPDATE books SET quantity=quantity+1 WHERE id='$book_id'";
            mysqli_query($conn, $sql_update);
            echo 1;
        } else {
            echo 2;
        }
    }
}
