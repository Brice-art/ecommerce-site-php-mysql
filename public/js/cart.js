// Simple helper to add product to cart via POST and then go to cart page
function addToCart(productId, quantity = 1) {
    const form = new FormData();
    form.append('product_id', productId);
    form.append('quantity', quantity);

    fetch('index.php?page=cart&action=add', {
        method: 'POST',
        body: form,
        credentials: 'same-origin'
    })
    .then(response => {
        if (response.ok) {
            // Redirect to cart after successful add
            window.location.href = 'index.php?page=cart';
        } else {
            return response.text().then(t => { throw new Error(t || 'Add failed'); });
        }
    })
    .catch(err => {
        console.error('Add to cart error:', err);
        alert('Could not add item to cart. Please try again.');
    });
}