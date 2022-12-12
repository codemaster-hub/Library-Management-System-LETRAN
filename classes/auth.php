<?php
require_once '../db/db_config.php';

class Auth
{
    public static function authenticate($email, $password)
    {
        global $conn;
        $sql = "SELECT * FROM users WHERE email = '$email'";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            if ($row['password'] == md5($password)) {
                // Success
                $_SESSION['user_login'] = true;
                $_SESSION['user_info'] = [
                    'full_name' => $row['first_name'] . ' ' . $row['last_name'],
                    'email' => $row['email'],
                    'role' => $row['role']
                ];
                $_SESSION['user_id'] = $row['id'];
                return ['is_authenticated' => true, 'message' => 'Success', 'data' => $row, 'role' => $row['role']];
            } else {
                // Incorrect pass
                return ['is_authenticated' => false, 'message' => 'Incorrect Password'];
            }
        } else {
            // Invalid User ID
            return ['is_authenticated' => false, 'message' => 'Account not found'];
        }
    }
    public static function checkLogin()
    {
        if (isset($_SESSION['user_login'])) {
            return $_SESSION['user_info']['role'];
        } else {
            return false;
        }
    }
}
