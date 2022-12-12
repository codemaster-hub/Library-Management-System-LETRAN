<?php
session_start();
require_once '../db/db_config.php';

class User
{
    public static function getAllUsers()
    {
        global $conn;
        $sql = "SELECT * FROM users WHERE role != 1";
        $results = $conn->query($sql);
        return $results;
    }
    public static function getUser($id)
    {
        global $conn;
        $sql = "SELECT * FROM users WHERE id = $id";
        $results = $conn->query($sql)->fetch_array();
        echo json_encode($results);
    }
    public static function createUser($values)
    {
        global $conn;
        $sql = "INSERT INTO `users`(
                    `school_id`, 
                    `username`, 
                    `email`, 
                    `password`, 
                    `first_name`, 
                    `middle_name`, 
                    `last_name`, 
                    `role`, 
                    `address`, 
                    `birthday`, 
                    `gender`, 
                    `contact`, 
                    `status`
                    ) 
                VALUES (
                    '" . $values['school_id'] . "',
                    '" . $values['username'] . "',
                    '" . $values['email'] . "',
                    '" . $values['password'] . "',
                    '" . $values['first_name'] . "',
                    '" . $values['middle_name'] . "',
                    '" . $values['last_name'] . "',
                    '" . $values['role'] . "',
                    '" . $values['address'] . "',
                    '" . $values['birthday'] . "',
                    '" . $values['gender'] . "',
                    '" . $values['contact'] . "',
                    '1')";

        if ($conn->query($sql)) {
            return true;
        } else {
            return false;
        }
    }

    public static function updateUser($values)
    {
        global $conn;
        $sql =  "UPDATE users SET first_name = '" . $values['first_name'] . "', middle_name = '" . $values['middle_name'] . "', last_name = '" . $values['last_name'] . "', address = '" . $values['address'] . "',birthday = '" . $values['birthday'] . "', gender = '" . $values['gender'] . "', contact = '" . $values['contact'] . "' WHERE id = '" . $values['id'] . "' ";

        if ($conn->query($sql)) {
            return true;
        } else {
            return false;
        }
    }
    public static function delete($id)
    {
        global $conn;
        $sql = "DELETE FROM users WHERE id = $id";
        if ($conn->query($sql)) {
            echo true;
        } else {
            echo false;
        }
    }
}
