<?php

require_once '../db/db_config.php';

class Category
{
    public static function getAllCategories()
    {
        global $conn;
        $sql = "SELECT * FROM categories";
        $results = $conn->query($sql);
        return $results;
    }

    public static function createCategory($desc)
    {
        global $conn;
        $sql = "INSERT INTO `categories`(
            `description`
            ) 
        VALUES (
           '" . $desc . "');";


        if ($conn->query($sql)) {
            $last_inserted = $conn->insert_id;
            echo json_encode(['status' => true, 'id' => $last_inserted]);
        } else {
            echo json_encode(['status' => false]);
        }
    }


    public static function delete($id)
    {
        global $conn;
        $sql = "DELETE FROM categories WHERE id = $id";
        if ($conn->query($sql)) {
            echo true;
        } else {
            echo false;
        }
    }
}
