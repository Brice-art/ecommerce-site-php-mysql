<?php

class Order
{
    private $db;

    public function __construct()
    {
        $this->db = new Database();
    }
    public function generateOrderNumber()
    {
        return 'ORD-' . date('Ymd') . '-' . str_pad(mt_rand(1, 999999), 6, '0', STR_PAD_LEFT);
    }

    public function createOrder($userId, $orderNumber, $status, $subtotal, $tax, $shippingCost, $discount, $total, $shippingFirstName, $shippingLastName, $shippingEmail, $shippingPhone, $shippingAddressLine1, $shippingAddressLine2, $shippingCity, $shippingState, $shippingPostalCode, $shippingCountry, $billingFirstName, $billingLastName, $billingEmail, $billingPhone, $billingAddressLine1, $billingAddressLine2, $billingCity, $billingState, $billingPostalCode, $billingCountry, $trackingNumber, $notes, $createdAt, $updatedAt)
    {
        $orderNumber = $this->generateOrderNumber();
        $status = 'pending';
        $subtotal = 0;
        $tax = 0;
        $shippingCost = 0;
        $discount = 0;
        $total = 0;
        $shippingFirstName = '';
        $shippingLastName = '';
        $shippingEmail = '';
        $shippingPhone = '';
        $shippingAddressLine1 = '';
        $shippingAddressLine2 = '';
        $shippingCity = '';
        $shippingState = '';
        $shippingPostalCode = '';
        $shippingCountry = '';
        $billingFirstName = '';
        $billingLastName = '';
        $billingEmail = '';
        $billingPhone = '';
        $billingAddressLine1 = '';
        $billingAddressLine2 = '';
        $billingCity = '';
        $billingState = '';
        $billingPostalCode = '';
        $billingCountry = '';
        $trackingNumber = '';
        $notes = '';
        $createdAt = date('Y-m-d H:i:s');
        $updatedAt = date('Y-m-d H:i:s');

        $this->db->query('INSERT INTO orders (user_id, order_number, status, subtotal, tax, shipping_cost, discount, total, shipping_first_name, shipping_last_name, shipping_email, shipping_phone, shipping_address_line1, shipping_address_line2, shipping_city, shipping_state, shipping_postal_code, shipping_country, billing_first_name, billing_last_name, billing_email, billing_phone, billing_address_line1, billing_address_line2, billing_city, billing_state, billing_postal_code, billing_country, tracking_number, notes, created_at, updated_at) VALUES (:user_id, :order_number, :status, :subtotal, :tax, :shipping_cost, :discount, :total, :shipping_first_name, :shipping_last_name, :shipping_email, :shipping_phone, :shipping_address_line1, :shipping_address_line2, :shipping_city, :shipping_state, :shipping_postal_code, :shipping_country, :billing_first_name, :billing_last_name, :billing_email, :billing_phone, :billing_address_line1, :billing_address_line2, :billing_city, :billing_state, :billing_postal_code, :billing_country, :tracking_number, :notes, :created_at, :updated_at)');
        $this->db->bind(':user_id', $userId);
        $this->db->bind(':order_number', $orderNumber);
        $this->db->bind(':status', $status);
        $this->db->bind(':subtotal', $subtotal);
        $this->db->bind(':tax', $tax);
        $this->db->bind(':shipping_cost', $shippingCost);
        $this->db->bind(':discount', $discount);
        $this->db->bind(':total', $total);
        $this->db->bind(':shipping_first_name', $shippingFirstName);
        $this->db->bind(':shipping_last_name', $shippingLastName);
        $this->db->bind(':shipping_email', $shippingEmail);
        $this->db->bind(':shipping_phone', $shippingPhone);
        $this->db->bind(':shipping_address_line1', $shippingAddressLine1);
        $this->db->bind(':shipping_address_line2', $shippingAddressLine2);
        $this->db->bind(':shipping_city', $shippingCity);
        $this->db->bind(':shipping_state', $shippingState);
        $this->db->bind(':shipping_postal_code', $shippingPostalCode);
        $this->db->bind(':shipping_country', $shippingCountry);
        $this->db->bind(':billing_first_name', $billingFirstName);
        $this->db->bind(':billing_last_name', $billingLastName);
        $this->db->bind(':billing_email', $billingEmail);
        $this->db->bind(':billing_phone', $billingPhone);
        $this->db->bind(':billing_address_line1', $billingAddressLine1);
        $this->db->bind(':billing_address_line2', $billingAddressLine2);
        $this->db->bind(':billing_city', $billingCity);
        $this->db->bind(':billing_state', $billingState);
        $this->db->bind(':billing_postal_code', $billingPostalCode);
        $this->db->bind(':billing_country', $billingCountry);
        $this->db->bind(':tracking_number', $trackingNumber);
        $this->db->bind(':notes', $notes);
        $this->db->bind(':created_at', $createdAt);
        $this->db->bind(':updated_at', $updatedAt);
        if ($this->db->execute()) {
            return $this->db->lastInsertId();
        }
        return false;
    }

    public function getOrderById($id, $userId)
    {
        $this->db->query('SELECT * FROM orders WHERE id = :id AND user_id = :user_id');
        $this->db->bind(':id', $id);
        $this->db->bind(':user_id', $userId);
        return $this->db->single();
    }

    public function getOrdersByUserId($userId)
    {
        $this->db->query('SELECT * FROM orders WHERE user_id = :userId ORDER BY created_at DESC');
        $this->db->bind(':userId', $userId);
        return $this->db->resultSet();
    }

    public function updateOrder($id, $data)
    {
        $this->db->query('UPDATE orders SET status = :status, subtotal = :subtotal, tax = :tax, shipping_cost = :shipping_cost, discount = :discount, total = :total, shipping_first_name = :shipping_first_name, shipping_last_name = :shipping_last_name, shipping_email = :shipping_email, shipping_phone = :shipping_phone, shipping_address_line1 = :shipping_address_line1, shipping_address_line2 = :shipping_address_line2, shipping_city = :shipping_city, shipping_state = :shipping_state, shipping_postal_code = :shipping_postal_code, shipping_country = :shipping_country, billing_first_name = :billing_first_name, billing_last_name = :billing_last_name, billing_email = :billing_email, billing_phone = :billing_phone, billing_address_line1 = :billing_address_line1, billing_address_line2 = :billing_address_line2, billing_city = :billing_city, billing_state = :billing_state, billing_postal_code = :billing_postal_code, billing_country = :billing_country, tracking_number = :tracking_number, notes = :notes, updated_at = :updated_at WHERE id = :id');
        $this->db->bind(':id', $id);
        $this->db->bind(':status', $data['status']);
        $this->db->bind(':subtotal', $data['subtotal']);
        $this->db->bind(':tax', $data['tax']);
        $this->db->bind(':shipping_cost', $data['shipping_cost']);
        $this->db->bind(':discount', $data['discount']);
        $this->db->bind(':total', $data['total']);
        $this->db->bind(':shipping_first_name', $data['shipping_first_name'] ?? '');
        $this->db->bind(':shipping_last_name', $data['shipping_last_name'] ?? '');
        $this->db->bind(':shipping_email', $data['shipping_email'] ?? '');
        $this->db->bind(':shipping_phone', $data['shipping_phone'] ?? '');
        $this->db->bind(':shipping_address_line1', $data['shipping_address_line1'] ?? '');
        $this->db->bind(':shipping_address_line2', $data['shipping_address_line2'] ?? '');
        $this->db->bind(':shipping_city', $data['shipping_city'] ?? '');
        $this->db->bind(':shipping_state', $data['shipping_state'] ?? '');
        $this->db->bind(':shipping_postal_code', $data['shipping_postal_code'] ?? '');
        $this->db->bind(':shipping_country', $data['shipping_country'] ?? '');
        $this->db->bind(':billing_first_name', $data['billing_first_name'] ?? '');
        $this->db->bind(':billing_last_name', $data['billing_last_name'] ?? '');
        $this->db->bind(':billing_email', $data['billing_email'] ?? '');
        $this->db->bind(':billing_phone', $data['billing_phone'] ?? '');
        $this->db->bind(':billing_address_line1', $data['billing_address_line1'] ?? '');
        $this->db->bind(':billing_address_line2', $data['billing_address_line2'] ?? '');
        $this->db->bind(':billing_city', $data['billing_city'] ?? '');
        $this->db->bind(':billing_state', $data['billing_state'] ?? '');
        $this->db->bind(':billing_postal_code', $data['billing_postal_code'] ?? '');
        $this->db->bind(':billing_country', $data['billing_country'] ?? '');
        $this->db->bind(':tracking_number', $data['tracking_number'] ?? '');
        $this->db->bind(':notes', $data['notes'] ?? '');
        $this->db->bind(':updated_at', date('Y-m-d H:i:s'));
        return $this->db->execute();
    }

    public function deleteOrder($id)
    {
        $this->db->query('DELETE FROM orders WHERE id = :id');
        $this->db->bind(':id', $id);
        return $this->db->execute();
    }

    public function getOrderItemsByOrderId($orderId)
    {
        $this->db->query('
            SELECT 
                oi.*, 
                p.name, 
                p.main_image, 
                p.price as product_price
            FROM order_items oi
            LEFT JOIN products p ON oi.product_id = p.id
            WHERE oi.order_id = :orderId
            ORDER BY oi.id ASC
        ');
        $this->db->bind(':orderId', $orderId);
        return $this->db->resultSet();
    }

    public function getOrderTotal($orderId)
    {
        $this->db->query('SELECT SUM(quantity * price) as total FROM order_items WHERE order_id = :orderId');
        $this->db->bind(':orderId', $orderId);
        return $this->db->single()['total'];
    }

    public function getOrderTotalByUserId($userId)
    {
        $this->db->query('SELECT SUM(total) as total FROM orders WHERE user_id = :userId');
        $this->db->bind(':userId', $userId);
        return $this->db->single()['total'];
    }

    /**
     * Create order with data array - simpler method
     */
    public function createOrderFromData($userId, $orderData)
    {
        $orderNumber = $this->generateOrderNumber();
        $status = $orderData['status'] ?? 'pending';
        $subtotal = $orderData['subtotal'] ?? 0;
        $tax = $orderData['tax'] ?? 0;
        $shippingCost = $orderData['shipping_cost'] ?? 0;
        $discount = $orderData['discount'] ?? 0;
        $total = $orderData['total'] ?? 0;
        
        // Shipping info
        $shippingFirstName = $orderData['shipping_first_name'] ?? '';
        $shippingLastName = $orderData['shipping_last_name'] ?? '';
        $shippingEmail = $orderData['shipping_email'] ?? '';
        $shippingPhone = $orderData['shipping_phone'] ?? '';
        $shippingAddressLine1 = $orderData['shipping_address_line1'] ?? '';
        $shippingAddressLine2 = $orderData['shipping_address_line2'] ?? '';
        $shippingCity = $orderData['shipping_city'] ?? '';
        $shippingState = $orderData['shipping_state'] ?? '';
        $shippingPostalCode = $orderData['shipping_postal_code'] ?? '';
        $shippingCountry = $orderData['shipping_country'] ?? '';
        
        // Billing info (use shipping if not provided)
        $billingFirstName = $orderData['billing_first_name'] ?? $shippingFirstName;
        $billingLastName = $orderData['billing_last_name'] ?? $shippingLastName;
        $billingEmail = $orderData['billing_email'] ?? $shippingEmail;
        $billingPhone = $orderData['billing_phone'] ?? $shippingPhone;
        $billingAddressLine1 = $orderData['billing_address_line1'] ?? $shippingAddressLine1;
        $billingAddressLine2 = $orderData['billing_address_line2'] ?? $shippingAddressLine2;
        $billingCity = $orderData['billing_city'] ?? $shippingCity;
        $billingState = $orderData['billing_state'] ?? $shippingState;
        $billingPostalCode = $orderData['billing_postal_code'] ?? $shippingPostalCode;
        $billingCountry = $orderData['billing_country'] ?? $shippingCountry;
        
        $trackingNumber = $orderData['tracking_number'] ?? '';
        $notes = $orderData['notes'] ?? '';
        $createdAt = date('Y-m-d H:i:s');
        $updatedAt = date('Y-m-d H:i:s');

        $this->db->query('INSERT INTO orders (user_id, order_number, status, subtotal, tax, shipping_cost, discount, total, shipping_first_name, shipping_last_name, shipping_email, shipping_phone, shipping_address_line1, shipping_address_line2, shipping_city, shipping_state, shipping_postal_code, shipping_country, billing_first_name, billing_last_name, billing_email, billing_phone, billing_address_line1, billing_address_line2, billing_city, billing_state, billing_postal_code, billing_country, tracking_number, notes, created_at, updated_at) VALUES (:user_id, :order_number, :status, :subtotal, :tax, :shipping_cost, :discount, :total, :shipping_first_name, :shipping_last_name, :shipping_email, :shipping_phone, :shipping_address_line1, :shipping_address_line2, :shipping_city, :shipping_state, :shipping_postal_code, :shipping_country, :billing_first_name, :billing_last_name, :billing_email, :billing_phone, :billing_address_line1, :billing_address_line2, :billing_city, :billing_state, :billing_postal_code, :billing_country, :tracking_number, :notes, :created_at, :updated_at)');
        
        $this->db->bind(':user_id', $userId);
        $this->db->bind(':order_number', $orderNumber);
        $this->db->bind(':status', $status);
        $this->db->bind(':subtotal', $subtotal);
        $this->db->bind(':tax', $tax);
        $this->db->bind(':shipping_cost', $shippingCost);
        $this->db->bind(':discount', $discount);
        $this->db->bind(':total', $total);
        $this->db->bind(':shipping_first_name', $shippingFirstName);
        $this->db->bind(':shipping_last_name', $shippingLastName);
        $this->db->bind(':shipping_email', $shippingEmail);
        $this->db->bind(':shipping_phone', $shippingPhone);
        $this->db->bind(':shipping_address_line1', $shippingAddressLine1);
        $this->db->bind(':shipping_address_line2', $shippingAddressLine2);
        $this->db->bind(':shipping_city', $shippingCity);
        $this->db->bind(':shipping_state', $shippingState);
        $this->db->bind(':shipping_postal_code', $shippingPostalCode);
        $this->db->bind(':shipping_country', $shippingCountry);
        $this->db->bind(':billing_first_name', $billingFirstName);
        $this->db->bind(':billing_last_name', $billingLastName);
        $this->db->bind(':billing_email', $billingEmail);
        $this->db->bind(':billing_phone', $billingPhone);
        $this->db->bind(':billing_address_line1', $billingAddressLine1);
        $this->db->bind(':billing_address_line2', $billingAddressLine2);
        $this->db->bind(':billing_city', $billingCity);
        $this->db->bind(':billing_state', $billingState);
        $this->db->bind(':billing_postal_code', $billingPostalCode);
        $this->db->bind(':billing_country', $billingCountry);
        $this->db->bind(':tracking_number', $trackingNumber);
        $this->db->bind(':notes', $notes);
        $this->db->bind(':created_at', $createdAt);
        $this->db->bind(':updated_at', $updatedAt);
        
        if ($this->db->execute()) {
            return $this->db->lastInsertId();
        }
        
        return false;
    }

}
