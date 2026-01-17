<?php

/**
 * Category Model
 * 
 * Handles all database operations related to categories
 */

class Category
{
    private $db;

    public function __construct()
    {
        $this->db = new Database();
    }

    public function getAllCategories()
    {
        $this->db->query("SELECT * FROM categories WHERE is_active = :active ORDER BY id ASC");

        $this->db->bind(':active', 1);

        return $this->db->resultSet();
    }

    public function getCategoryById($id)
    {
        $this->db->query("SELECT * FROM categories WHERE id = :id");

        $this->db->bind(':id', $id);

        return $this->db->single();
    }

    public function getCategoryBySlug($slug)
    {
        $this->db->query("SELECT * FROM categories WHERE slug = :slug");

        $this->db->bind(':slug', $slug);

        return $this->db->single();
    }

    public function createCategory($data)
    {
        $name = $data["name"];
        $slug = $data["slug"];
        $description = $data["description"] ?? null;
        $parent_id = $data["parent_id"] ?? null;
        $image_url = $data["image_url"] ?? null;
        $this->db->query("INSERT INTO categories (name, slug, description, parent_id, image_url) VALUES (:name, :slug, :description, :parent_id, :image_url)");

        $this->db->bind(':name', $name);
        $this->db->bind(':slug', $slug);
        $this->db->bind(':description', $description);
        $this->db->bind(':parent_id', $parent_id);
        $this->db->bind(':image_url', $image_url);

        if ($this->db->execute()) {
            return $this->db->lastInsertId();
        }

        return false;
    }

    public function updateCategory($id, $data)
    {

        $name = $data["name"];
        $slug = $data["slug"];
        $description = $data["description"];
        $parent_id = $data["parent_id"];
        $image_url = $data["image_url"];
        $is_active = $data['is_active'];
        $this->db->query("UPDATE categories SET name = :name, slug = :slug, description = :description, parent_id = :parent_id, image_url = :image_url, is_active = :is_active WHERE id = :id");

        $this->db->bind(':id', $id);
        $this->db->bind(':name', $name);
        $this->db->bind(':slug', $slug);
        $this->db->bind(':description', $description);
        $this->db->bind(':parent_id', $parent_id);
        $this->db->bind(':image_url', $image_url);
        $this->db->bind(':is_active', $is_active);

        return $this->db->execute();
    }

    public function deleteCategory($id)
    {
        $this->db->query("UPDATE categories SET is_active = :is_active WHERE id = :id");

        $this->db->bind(':id', $id);
        $this->db->bind(':is_active', 0);

        return $this->db->execute();
    }

    public function getCategoriesWithProductCount()
    {
        $this->db->query(
            "SELECT c.*, COUNT(p.id) AS product_count
        FROM categories c
        LEFT JOIN products p ON p.category_id = c.id AND p.is_active = 1
        WHERE c.is_active = 1
        GROUP BY c.id
        ORDER BY c.name ASC"
        );

        return $this->db->resultSet();
    }

    public function getSubcategories($parentId)
    {
        $this->db->query("
        SELECT * FROM categories 
        WHERE parent_id = :parent_id AND is_active = :active
        ORDER BY name ASC
    ");

        $this->db->bind(':parent_id', $parentId);
        $this->db->bind(':active', 1);

        return $this->db->resultSet();
    }
}
