<?php

require_once '../db/db_config.php';

class Book
{

    public static function getAllBooks()
    {
        global $conn;
        $sql = "SELECT * FROM books WHERE e_books IS NULL";
        $results = $conn->query($sql);
        return $results;
    }
    public static function getAllEbooks()
    {
        global $conn;
        $sql = "SELECT * FROM books WHERE e_books IS NOT NULL";
        $results = $conn->query($sql);
        return $results;
    }
    public static function viewBook($id)
    {
        global $conn;
        $sql = "SELECT * FROM books WHERE id = $id";
        $results = $conn->query($sql);
        return $results;
    }

    public static function keyword($keyword)
    {
        global $conn;
        $keyword = $conn->real_escape_string($keyword);
        $sql = "SELECT * FROM books WHERE e_books IS NULL AND title LIKE '%$keyword%'";
        $results = $conn->query($sql);
        return $results;
    }

    public static function search($search)
    {
        global $conn;
        $search = $conn->real_escape_string($search);
        $sql = "SELECT * FROM books WHERE e_books IS NULL AND title LIKE '%$search%'";
        $results = $conn->query($sql);
        return $results;
    }

    public static function getAllBooksEbooks()
    {
        global $conn;
        $sql = "SELECT * FROM books WHERE e_books IS NOT NULL";
        $results = $conn->query($sql);
        return $results;
    }

    public static function keywordEbooks($keyword)
    {
        global $conn;
        $keyword = $conn->real_escape_string($keyword);
        $sql = "SELECT * FROM books WHERE e_books IS NOT NULL AND title LIKE '$keyword%'";
        $results = $conn->query($sql);
        return $results;
    }

    public static function searchEbooks($search)
    {
        global $conn;
        $search = $conn->real_escape_string($search);
        $sql = "SELECT * FROM books WHERE e_books IS NOT NULL AND title LIKE '$search%'";
        $results = $conn->query($sql);
        return $results;
    }
    public static function getBook($id)
    {
        global $conn;
        $sql = "SELECT * FROM books WHERE id = $id";
        $results = $conn->query($sql)->fetch_array();
        echo json_encode($results);
    }
    public static function addBooks($values, $pdf = false)
    {
        global $conn;

        if ($pdf) {
            $sql = "INSERT INTO `books`(
                `isbn`,   
                `thumbnail`,
                `title`,
                `description`,
                 `series`, 
                 `year`,
                 `quantity`, 
                 `author`, 
                 `e_books`,
                 `category_id`
                ) 
            VALUES (
               '" . mysqli_real_escape_string($conn, $values['isbn']) . "',
               '" . mysqli_real_escape_string($conn,  $values['thumbnail']) . "',
               '" . mysqli_real_escape_string($conn, $values['title']) . "', 
               '" . mysqli_real_escape_string($conn, $values['description']) . "',
               '" . mysqli_real_escape_string($conn, $values['series']) . "',
               '" . mysqli_real_escape_string($conn,  $values['year']) . "',
               '" . mysqli_real_escape_string($conn,  $values['quantity']) . "',
               '" . mysqli_real_escape_string($conn, $values['author']) . "',
               '" . mysqli_real_escape_string($conn,  $pdf) . "',
               '" . $values['category_id'] . "'
            )";
        } else {
            $sql = "INSERT INTO `books`(
                `isbn`,   
                `thumbnail`,
                `title`,
                `description`,
                 `series`, 
                 `year`,
                 `quantity`, 
                 `author`, 
                 `category_id`
                ) 
            VALUES (
               '" . $values['isbn'] . "',
               '" . $values['thumbnail'] . "',
               '" . $values['title'] . "', 
               '" . $values['description'] . "',
               '" . $values['series'] . "',
               '" . $values['year'] . "',
               '" . $values['quantity'] . "',
               '" . $values['author'] . "',
               '" . $values['category_id'] . "'
            )";
        }


        if ($conn->query($sql)) {
            return true;
        } else {
            return false;
        }
    }


    public static function delete($id)
    {
        global $conn;
        $sql = "DELETE FROM books WHERE id = $id";
        if ($conn->query($sql)) {
            echo true;
        } else {
            echo false;
        }
    }

    public static function getDashBoardAdmin()
    {
        global $conn;

        $sql1 = "SELECT COUNT(e_books) AS count
        FROM books WHERE e_books IS NOT NULL";
        $ebook_count = $conn->query($sql1)->fetch_assoc();


        $sql2 = "SELECT COUNT(*) AS count FROM `books` WHERE e_books IS NULL";
        $book_count = $conn->query($sql2)->fetch_assoc();

        $sql3 = "SELECT COUNT(`status`)  AS count FROM transactions WHERE `status` = 1";
        $pending_count = $conn->query($sql3)->fetch_assoc();

        $sql4 = "SELECT COUNT(`status`) AS count FROM transactions WHERE `status` = 2";
        $issued_count = $conn->query($sql4)->fetch_assoc();

        return array($ebook_count['count'], $book_count['count'], $pending_count['count'], $issued_count['count']);
    }

    public static function getDashBoardClient()
    {
        global $conn;

        $sql1 = "SELECT COUNT(e_books) AS count
        FROM books WHERE e_books IS NOT NULL";
        $ebook_count = $conn->query($sql1)->fetch_assoc();


        $sql2 = "SELECT COUNT(*) AS count FROM `books` WHERE e_books IS NULL";
        $book_count = $conn->query($sql2)->fetch_assoc();

        $sql3 = "SELECT COUNT(`status`)  AS count FROM transactions WHERE `status` = 2";
        $borrowed_count = $conn->query($sql3)->fetch_assoc();

        return array($ebook_count['count'], $book_count['count'], $borrowed_count['count']);
    }
}
