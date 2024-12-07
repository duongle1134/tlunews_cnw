<?php

class UserModel
{
    public static function getAllUsers()
    {
        $db = Database::getConnection();
        $sql = "SELECT * FROM users";
        $result = $db->query($sql);
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public static function getUserById($id)
    {
        $db = Database::getConnection();
        $sql = "SELECT * FROM users WHERE id = ?";
        $stmt = $db->prepare($sql);
        $stmt->bind_param('i', $id);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }
}
