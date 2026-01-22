<?php

class CheckoutController
{
    private $orderModel;
    private $cartModel;
    private $orderItemModel;

    public function __construct()
    {
        $this->orderModel = new Order();
        $this->cartModel = new Cart();
        $this->orderItemModel = new OrderItem();
    }

    /**
     * Check if user is logged in and cart has items
     */
    private function validateCheckout()
    {
        if (!isset($_SESSION['user_id'])) {
            header('Location: index.php?page=login');
            exit;
        }

        $userId = $_SESSION['user_id'];
        $sessionId = session_id();
        $cartItems = $this->cartModel->getCartItems($userId, $sessionId);

        if (empty($cartItems)) {
            header('Location: index.php?page=cart');
            exit;
        }

        return ['userId' => $userId, 'sessionId' => $sessionId, 'cartItems' => $cartItems];
    }

    /**
     * Calculate order totals
     */
    private function calculateTotals($cartItems)
    {
        $subtotal = 0;
        foreach ($cartItems as $item) {
            $subtotal += ($item['price'] * $item['quantity']);
        }

        $shippingCost = 10.00; // Fixed shipping
        $tax = $subtotal * 0.08; // 8% tax
        $discount = 0; // No discount for now
        $total = $subtotal + $shippingCost + $tax - $discount;

        return [
            'subtotal' => $subtotal,
            'shipping_cost' => $shippingCost,
            'tax' => $tax,
            'discount' => $discount,
            'total' => $total
        ];
    }

    /**
     * Shipping step - GET: show form, POST: process and go to payment
     */
    public function shipping()
    {
        $checkoutData = $this->validateCheckout();
        $userId = $checkoutData['userId'];
        $sessionId = $checkoutData['sessionId'];
        $cartItems = $checkoutData['cartItems'];

        // If POST, process shipping form
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Validate required fields
            $errors = [];
            
            if (empty(trim($_POST['shipping_first_name'] ?? ''))) {
                $errors[] = 'First name is required';
            }
            if (empty(trim($_POST['shipping_last_name'] ?? ''))) {
                $errors[] = 'Last name is required';
            }
            if (empty(trim($_POST['shipping_email'] ?? ''))) {
                $errors[] = 'Email is required';
            } elseif (!filter_var($_POST['shipping_email'], FILTER_VALIDATE_EMAIL)) {
                $errors[] = 'Invalid email format';
            }
            if (empty(trim($_POST['shipping_address_line1'] ?? ''))) {
                $errors[] = 'Address is required';
            }
            if (empty(trim($_POST['shipping_city'] ?? ''))) {
                $errors[] = 'City is required';
            }
            if (empty(trim($_POST['shipping_postal_code'] ?? ''))) {
                $errors[] = 'Postal code is required';
            }

            if (empty($errors)) {
                // Store shipping data in session
                $_SESSION['checkout']['shipping'] = [
                    'shipping_first_name' => trim($_POST['shipping_first_name']),
                    'shipping_last_name' => trim($_POST['shipping_last_name']),
                    'shipping_email' => trim($_POST['shipping_email']),
                    'shipping_phone' => trim($_POST['shipping_phone'] ?? ''),
                    'shipping_address_line1' => trim($_POST['shipping_address_line1']),
                    'shipping_address_line2' => trim($_POST['shipping_address_line2'] ?? ''),
                    'shipping_city' => trim($_POST['shipping_city']),
                    'shipping_state' => trim($_POST['shipping_state'] ?? ''),
                    'shipping_postal_code' => trim($_POST['shipping_postal_code']),
                    'shipping_country' => trim($_POST['shipping_country'] ?? 'US'),
                    'same_as_billing' => isset($_POST['same_as_billing'])
                ];

                // If same as billing, copy shipping to billing
                if (isset($_POST['same_as_billing'])) {
                    $_SESSION['checkout']['billing'] = $_SESSION['checkout']['shipping'];
                }

                // Redirect to payment
                header('Location: index.php?page=checkout&action=payment');
                exit;
            }

            // Show form again with errors
            $totals = $this->calculateTotals($cartItems);
            $cartItems = (new Cart())->getCartItems($userId, $sessionId);
            
            $this->view('checkout/shipping', [
                'cartItems' => $cartItems,
                'totals' => $totals,
                'errors' => $errors,
                'title' => 'Shipping Information',
                'count' => count($cartItems)
            ]);
            return;
        }

        // GET request - show shipping form
        $totals = $this->calculateTotals($cartItems);
        $cartItems = (new Cart())->getCartItems($userId, $sessionId);
        
        $this->view('checkout/shipping', [
            'cartItems' => $cartItems,
            'totals' => $totals,
            'title' => 'Shipping Information',
            'count' => count($cartItems)
        ]);
    }

    /**
     * Payment step - GET: show form, POST: process and create order
     */
    public function payment()
    {
        $checkoutData = $this->validateCheckout();
        $userId = $checkoutData['userId'];
        $sessionId = $checkoutData['sessionId'];
        $cartItems = $checkoutData['cartItems'];

        // Check if shipping info exists
        if (!isset($_SESSION['checkout']['shipping'])) {
            header('Location: index.php?page=checkout&action=shipping');
            exit;
        }

        // If POST, process payment and create order
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Validate payment
            $errors = [];
            
            if (empty(trim($_POST['payment_method'] ?? ''))) {
                $errors[] = 'Payment method is required';
            }

            // If not same as billing, validate billing
            if (!isset($_SESSION['checkout']['billing'])) {
                if (empty(trim($_POST['billing_first_name'] ?? ''))) {
                    $errors[] = 'Billing first name is required';
                }
                if (empty(trim($_POST['billing_last_name'] ?? ''))) {
                    $errors[] = 'Billing last name is required';
                }
                if (empty(trim($_POST['billing_address_line1'] ?? ''))) {
                    $errors[] = 'Billing address is required';
                }
                if (empty(trim($_POST['billing_city'] ?? ''))) {
                    $errors[] = 'Billing city is required';
                }
                if (empty(trim($_POST['billing_postal_code'] ?? ''))) {
                    $errors[] = 'Billing postal code is required';
                }

                if (empty($errors)) {
                    $_SESSION['checkout']['billing'] = [
                        'billing_first_name' => trim($_POST['billing_first_name']),
                        'billing_last_name' => trim($_POST['billing_last_name']),
                        'billing_email' => trim($_POST['billing_email'] ?? ''),
                        'billing_phone' => trim($_POST['billing_phone'] ?? ''),
                        'billing_address_line1' => trim($_POST['billing_address_line1']),
                        'billing_address_line2' => trim($_POST['billing_address_line2'] ?? ''),
                        'billing_city' => trim($_POST['billing_city']),
                        'billing_state' => trim($_POST['billing_state'] ?? ''),
                        'billing_postal_code' => trim($_POST['billing_postal_code']),
                        'billing_country' => trim($_POST['billing_country'] ?? 'US')
                    ];
                }
            }

            if (empty($errors)) {
                // Store payment method
                $_SESSION['checkout']['payment'] = [
                    'payment_method' => trim($_POST['payment_method'])
                ];

                // Create order
                $orderId = $this->processOrder($userId, $sessionId, $cartItems);
                
                if ($orderId) {
                    // Clear cart
                    $this->cartModel->clearCart($userId, $sessionId);
                    
                    // Get order details for confirmation
                    $order = $this->orderModel->getOrderById($orderId, $userId);
                    
                    // Clear checkout session data
                    unset($_SESSION['checkout']);
                    
                    // Redirect to confirmation
                    header('Location: index.php?page=checkout&action=confirmation&order_id=' . $orderId);
                    exit;
                } else {
                    $errors[] = 'Failed to create order. Please try again.';
                }
            }

            // Show form again with errors
            $totals = $this->calculateTotals($cartItems);
            $shipping = $_SESSION['checkout']['shipping'] ?? [];
            $billing = $_SESSION['checkout']['billing'] ?? [];
            
            $this->view('checkout/payment', [
                'cartItems' => $cartItems,
                'totals' => $totals,
                'shipping' => $shipping,
                'billing' => $billing,
                'errors' => $errors,
                'title' => 'Payment',
                'count' => count($cartItems)
            ]);
            return;
        }

        // GET request - show payment form
        $totals = $this->calculateTotals($cartItems);
        $shipping = $_SESSION['checkout']['shipping'];
        $billing = $_SESSION['checkout']['billing'] ?? null;
        
        $this->view('checkout/payment', [
            'cartItems' => $cartItems,
            'totals' => $totals,
            'shipping' => $shipping,
            'billing' => $billing,
            'title' => 'Payment',
            'count' => count($cartItems)
        ]);
    }

    /**
     * Process order creation
     */
    private function processOrder($userId, $sessionId, $cartItems)
    {
        // Calculate totals
        $totals = $this->calculateTotals($cartItems);
        
        // Get shipping and billing data from session
        $shipping = $_SESSION['checkout']['shipping'] ?? [];
        $billing = $_SESSION['checkout']['billing'] ?? $shipping;
        
        // Prepare order data
        $orderData = array_merge($totals, $shipping, $billing);
        $orderData['status'] = 'pending';
        
        // Create order
        $orderId = $this->orderModel->createOrderFromData($userId, $orderData);
        
        if ($orderId) {
            // Create order items
            foreach ($cartItems as $item) {
                $this->orderItemModel->createOrderItem(
                    $orderId,
                    $item['product_id'],
                    $item['quantity'],
                    $item['price'],
                    $item['name'] ?? '',
                    $item['sku'] ?? ''
                );
            }
            
            return $orderId;
        }
        
        return false;
    }

    /**
     * Confirmation page
     */
    public function confirmation()
    {
        // Check if user is logged in
        if (!isset($_SESSION['user_id'])) {
            header('Location: index.php?page=login');
            exit;
        }

        $userId = $_SESSION['user_id'];
        $orderId = $_GET['order_id'] ?? null;

        if (!$orderId) {
            header('Location: index.php?page=orders');
            exit;
        }

        // Get order and verify ownership
        $order = $this->orderModel->getOrderById($orderId, $userId);
        
        if (!$order) {
            header('Location: index.php?page=orders');
            exit;
        }

        // Get order items
        $orderItems = $this->orderModel->getOrderItemsByOrderId($orderId);
        
        $cartItems = (new Cart())->getCartItems($userId, session_id());
        
        $this->view('checkout/confirmation', [
            'order' => $order,
            'orderItems' => $orderItems,
            'title' => 'Order Confirmation',
            'count' => count($cartItems)
        ]);
    }

    private function view($view, $data = [])
    {
        extract($data);
        require_once '../src/Views/layout/header.php';
        require_once "../src/Views/{$view}.php";
        require_once '../src/Views/layout/footer.php';
    }
}
