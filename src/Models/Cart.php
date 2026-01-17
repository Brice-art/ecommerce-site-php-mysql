<?php

class Cart
{
    private $db;

    public function __construct()
    {
        $this->db = new Database();
    }

    public function addToCart($userId, $sessionId, $productId, $quantity)
    {
        $this->db->query('INSERT INTO cart (user_id, session_id, product_id, quantity) VALUES (:user_id, :session_id, :product_id, :quantity);');

        $this->db->bind(':user_id', $userId);
        $this->db->bind(':session_id', $sessionId);
        $this->db->bind(':product_id', $productId);
        $this->db->bind(':quantity', $quantity);

        if ($this->db->execute()) {
            return $this->db->lastInsertId();
        }

        return false;
    }

    public function getCartItems($userId, $sessionId)
    {
        $this->db->query('
        SELECT 
            c.*,
            p.name,
            p.price,
            p.main_image,
            p.quantity as stock_quantity,
            (c.quantity * p.price) as subtotal
        FROM cart c
        LEFT JOIN products p ON c.product_id = p.id
        WHERE (c.user_id = :user_id OR c.session_id = :session_id)
        ORDER BY c.created_at DESC
    ');

        $this->db->bind(':user_id', $userId);
        $this->db->bind(':session_id', $sessionId);

        return $this->db->resultSet();
    }

    public function updateQuantity($cartItemId, $quantity)
    {
        $this->db->query('UPDATE cart SET quantity = :quantity WHERE id = :id;');

        $this->db->bind(':id', $cartItemId);
        $this->db->bind(':quantity', $quantity);

        return $this->db->execute();
    }

    public function removeItem($cartItemId)
    {
        $this->db->query('DELETE FROM cart WHERE id = :id;');

        $this->db->bind(':id', $cartItemId);

        $this->db->execute();
    }

    public function clearCart($userId, $sessionId)
    {
        $this->db->query('DELETE FROM cart WHERE user_id = :user_id OR session_id = :session_id;');

        $this->db->bind(':user_id', $userId);
        $this->db->bind(':session_id', $sessionId);

        $this->db->execute();
    }

    public function getCartTotal($userId, $sessionId)
    {
        $this->db->query(
            'SELECT
                    SUM(c.quantity * p.price) AS total
                FROM
                    cart c
                LEFT JOIN products p ON
                    c.product_id = p.id
                WHERE
                    c.user_id = :user_id
                OR 
                    c.session_id = :session_id;'
        );

        $this->db->bind(':user_id', $userId);
        $this->db->bind(':session_id', $sessionId);

        return $this->db->single();
    }
}
