<?php

class NewsModel
{
    public static function getAllNews()
    {
        $db = Database::getConnection();
        $sql = "SELECT * FROM news";
        $result = $db->query($sql);
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public static function getNewsById($id)
    {
        $db = Database::getConnection();
        $sql = "SELECT * FROM news WHERE id = ?";
        $stmt = $db->prepare($sql);
        $stmt->bind_param('i', $id);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }
}
