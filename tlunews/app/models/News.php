<?php
class News extends BaseModel
{

    // Lấy tất cả tin tức
    public function getAllNews()
    {
        $query = "SELECT * FROM posts ORDER BY created_at DESC";
        $result = mysqli_query($this->conn, $query);
        return mysqli_fetch_all($result, MYSQLI_ASSOC);
    }

    // Lấy chi tiết tin tức theo ID
    public function getNewsById($id)
    {
        $query = "SELECT * FROM posts WHERE id = {$id}";
        $result = mysqli_query($this->conn, $query);
        return mysqli_fetch_assoc($result);
    }

    // Tạo tin tức mới
    public function createNews($title, $content, $category_id, $image)
    {
        $query = "INSERT INTO posts (title, content, category_id, image, created_at) 
                  VALUES ('{$title}', '{$content}', {$category_id}, '{$image}', NOW())";
        mysqli_query($this->conn, $query);
    }
}
