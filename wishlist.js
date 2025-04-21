document.addEventListener('DOMContentLoaded', function () {
    const hearts = document.querySelectorAll('.wishlist-heart');

    hearts.forEach(heart => {
        const product = JSON.parse(heart.getAttribute('data-product'));
        const wishlist = JSON.parse(localStorage.getItem('wishlist')) || [];
        const isInWishlist = wishlist.some(item => item.title === product.title);

        // Maintain correct heart color on page load
        heart.innerHTML = isInWishlist ? '❤️' : '🤍';

        heart.addEventListener('click', function () {
            let wishlist = JSON.parse(localStorage.getItem('wishlist')) || [];
            const index = wishlist.findIndex(item => item.title === product.title);

            if (index === -1) {
                wishlist.push(product);
                this.innerHTML = '❤️';
                showMessage('Added to Wishlist');
            } else {
                wishlist.splice(index, 1);
                this.innerHTML = '🤍';
                showMessage('Removed from Wishlist');
            }

            localStorage.setItem('wishlist', JSON.stringify(wishlist));
        });
    });
});

function showMessage(message) {
    const msgDiv = document.createElement('div');
    msgDiv.textContent = message;
    msgDiv.style.cssText = `
        position: fixed;
        top: 20px;
        right: 20px;
        background-color: #4CAF50;
        color: #fff;
        padding: 10px 15px;
        border-radius: 5px;
        z-index: 1000;
    `;
    document.body.appendChild(msgDiv);

    setTimeout(() => msgDiv.remove(), 2000);
}