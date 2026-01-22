<?php

class OrderItem
{
    private $db;

    public function __construct()
    {
        $this->db = new Database();
    }

    public function createOrderItem($orderId, $productId, $quantity, $price, $productName = '', $productSku = '')
    {
        $subtotal = $quantity * $price;
        
        $this->db->query('INSERT INTO order_items (order_id, product_id, quantity, price, product_name, product_sku, subtotal) VALUES (:order_id, :product_id, :quantity, :price, :product_name, :product_sku, :subtotal)');
        
        $this->db->bind(':order_id', $orderId);
        $this->db->bind(':product_id', $productId);
        $this->db->bind(':quantity', $quantity);
        $this->db->bind(':price', $price);
        $this->db->bind(':product_name', $productName);
        $this->db->bind(':product_sku', $productSku);
        $this->db->bind(':subtotal', $subtotal);
        
        return $this->db->execute();
    }
}
