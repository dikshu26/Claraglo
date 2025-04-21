let cart = JSON.parse(localStorage.getItem('cart')) || [];

function saveCartToStorage() {
    localStorage.setItem('cart', JSON.stringify(cart));
}

function addToCart(productName, productPrice, productImage) {
    const existingProduct = cart.find(item => item.name === productName);

    if (existingProduct) {
        existingProduct.quantity++;
    } else {
        cart.push({ 
            name: productName, 
            price: productPrice, 
            quantity: 1, 
            image: productImage 
        });
    }

    saveCartToStorage();
    showCartMessage();
    updateCartSidebar();
}

function updateCartSidebar() {
    const cartItemsDiv = document.getElementById('cart-items');
    const totalPriceDiv = document.getElementById('total-price');

    cartItemsDiv.innerHTML = ''; 
    let total = 0;

    cart.forEach((item, index) => {
        const itemDiv = document.createElement('div');
        itemDiv.classList.add('cart-item');

        itemDiv.innerHTML = `
            <img src="${item.image}" alt="${item.name}">
            <div class="details">
                <p>${item.name}</p>
                <p>₹${item.price * item.quantity}</p>
            </div>
            <div class="quantity-controls">
                <button class="quantity-btn" onclick="changeQuantity(${index}, -1)">-</button>
                <span>${item.quantity}</span>
                <button class="quantity-btn" onclick="changeQuantity(${index}, 1)">+</button>
            </div>
            <button class="remove-btn" onclick="removeFromCart(${index})">Remove</button>
        `;

        cartItemsDiv.appendChild(itemDiv);
        total += item.price * item.quantity;
    });

    totalPriceDiv.innerHTML = `Total: ₹${total}`;
}

function changeQuantity(index, change) {
    if (cart[index].quantity + change <= 0) {
        cart.splice(index, 1); 
    } else {
        cart[index].quantity += change;
    }

    saveCartToStorage();
    updateCartSidebar();
}

function removeFromCart(index) {
    cart.splice(index, 1);
    saveCartToStorage();
    updateCartSidebar();
}

function openCartSidebar() {
    document.querySelector('.cart-sidebar').classList.add('active');
    document.querySelector('.overlay').classList.add('active');
    updateCartSidebar();  // Ensure cart updates when opening
}

function closeCartSidebar() {
    document.querySelector('.cart-sidebar').classList.remove('active');
    document.querySelector('.overlay').classList.remove('active');
}

// Cart Message
function showCartMessage() {
    const cartMessage = document.getElementById('cart-message');
    cartMessage.style.display = 'block';

    setTimeout(() => {
        cartMessage.style.display = 'none';
    }, 2000);
}

// Ensure cart loads on page load
window.onload = updateCartSidebar;
