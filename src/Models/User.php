<?php

/**
 * Product Model
 * 
 * Handles all database operations related to products
 */

class User
{
    private $db;

    public function __construct()
    {
        $this->db = new Database();
    }

    public function getUserByEmail($email)
    {
        $this->db->query('SELECT * FROM users WHERE email = :email;');

        $this->db->bind(':email', $email);

        return $this->db->single();
    }

    public function getUserById($id)
    {
        $this->db->query('SELECT * FROM users WHERE id = :id;');

        $this->db->bind(':id', $id);

        return $this->db->single();
    }

    public function createUser($data)
    {
        $email = $data['email'];
        $password = $data['password'];
        $first_name = $data['first_name'];
        $last_name = $data['last_name'];
        $phone = $data['phone'] ?? null;
        $role = $data['role'];

        // Store a hashed password
        $hash = password_hash($password, PASSWORD_DEFAULT);

        $this->db->query('INSERT INTO users (email, password, first_name, last_name, phone, role) VALUES (:email, :password, :first_name, :last_name, :phone, :role)');

        $this->db->bind(':email', $email);
        $this->db->bind(':password', $hash);
        $this->db->bind(':first_name', $first_name);
        $this->db->bind(':last_name', $last_name);
        $this->db->bind(':phone', $phone);
        $this->db->bind(':role', $role);

        if ($this->db->execute()) {
            return $this->db->lastInsertId();
        }

        return false;
    }

    public function updateUser($id, $data)
    {
        $email = $data['email'];
        $first_name = $data['first_name'];
        $last_name = $data['last_name'];
        $phone = $data['phone'] ?? null;
        $role = $data['role'];

        $this->db->query('UPDATE users SET email = :email, first_name = :first_name, last_name = :last_name, phone = :phone, role = :role WHERE id = :id;');

        $this->db->bind(':email', $email);
        $this->db->bind(':first_name', $first_name);
        $this->db->bind(':last_name', $last_name);
        $this->db->bind(':phone', $phone);
        $this->db->bind(':role', $role);
        $this->db->bind(':id', $id);

        return $this->db->execute();
    }

    public function updatePassword($id, $newPassword)
    {
        $hash = password_hash($newPassword, PASSWORD_DEFAULT);

        $this->db->query('UPDATE users SET password = :password WHERE id = :id');

        $this->db->bind(':password', $hash);
        $this->db->bind(':id', $id);

        return $this->db->execute();
    }

    public function verifyPassword($email, $password)
    {
        $this->db->query('SELECT * FROM users WHERE email = :email;');

        $this->db->bind(':email', $email);

        $user = $this->db->single();
        if (!$user) {
            return false;
        }

        return password_verify($password, $user["password"]);
    }

    public function getUserOrders($userId)
    {
        $this->db->query('
        SELECT * FROM orders 
        WHERE user_id = :user_id 
        ORDER BY created_at DESC
        ');

        $this->db->bind(':user_id', $userId);

        return $this->db->resultSet();
    }

    public function emailExists($email)
    {
        $this->db->query('SELECT id FROM users WHERE email = :email');
        $this->db->bind(':email', $email);

        return $this->db->single() !== false;
    }
}
