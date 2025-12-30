// ordering.js - Updated to work with PHP backend

let cart = [];
let totalPrice = 0;

// Add item to cart
document.querySelectorAll('.add-btn').forEach(button => {
    button.addEventListener('click', function() {
        const card = this.closest('.card');
        const name = card.querySelector('h3').textContent;
        const price = parseFloat(this.getAttribute('data-price'));
        
        // Add to cart
        cart.push({ name, price });
        totalPrice += price;
        
        // Update display
        updateCart();
        
        // Visual feedback
        this.textContent = 'Added!';
        this.style.background = '#28a745';
        setTimeout(() => {
            this.textContent = 'Add';
            this.style.background = '';
        }, 1000);
    });
});

// Update cart display
function updateCart() {
    const orderList = document.getElementById('order-list');
    const totalElement = document.getElementById('total-price');
    
    // Clear current list
    orderList.innerHTML = '';
    
    // Group items by name and count quantities
    const groupedCart = {};
    cart.forEach(item => {
        if (groupedCart[item.name]) {
            groupedCart[item.name].quantity++;
            groupedCart[item.name].totalPrice += item.price;
        } else {
            groupedCart[item.name] = {
                name: item.name,
                price: item.price,
                quantity: 1,
                totalPrice: item.price
            };
        }
    });
    
    // Display items
    Object.values(groupedCart).forEach(item => {
        const itemDiv = document.createElement('div');
        itemDiv.style.cssText = 'display: flex; justify-content: space-between; padding: 10px; border-bottom: 1px solid #eee;';
        itemDiv.innerHTML = `
            <span>${item.name} (x${item.quantity})</span>
            <span style="color: #667eea; font-weight: bold;">${item.totalPrice.toFixed(2)} EGP</span>
        `;
        orderList.appendChild(itemDiv);
    });
    
    // Update total
    totalElement.textContent = totalPrice.toFixed(2);
    
    // Show/hide order box
    const orderBox = document.querySelector('.order-box');
    if (cart.length > 0) {
        orderBox.style.display = 'block';
    }
}

// Handle submit button
document.querySelector('.exit').addEventListener('click', function(e) {
    e.preventDefault();
    
    if (cart.length === 0) {
        alert('Your cart is empty! Please add items first.');
        return;
    }
    
    // Create form to send data to PHP
    const form = document.createElement('form');
    form.method = 'POST';
    form.action = '../PHP/place_order.php';
    
    // Add cart data
    const cartInput = document.createElement('input');
    cartInput.type = 'hidden';
    cartInput.name = 'order_data';
    cartInput.value = JSON.stringify(cart);
    form.appendChild(cartInput);
    
    // Add total price
    const totalInput = document.createElement('input');
    totalInput.type = 'hidden';
    totalInput.name = 'total_price';
    totalInput.value = totalPrice;
    form.appendChild(totalInput);
    
    // Submit form
    document.body.appendChild(form);
    form.submit();
});

// Initialize - hide order box if empty
if (cart.length === 0) {
    document.querySelector('.order-box').style.display = 'none';
}