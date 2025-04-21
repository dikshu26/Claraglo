document.addEventListener("DOMContentLoaded", function () {
  const cart = JSON.parse(localStorage.getItem("cart")) || [];
  const summaryList = document.getElementById("summaryList");
  const totalPrice = document.getElementById("totalPrice");
  const paymentDetails = document.getElementById("paymentDetails");
  const methodSelect = document.getElementById("paymentMethod");

  let total = 0;

  cart.forEach(item => {
    const li = document.createElement("li");
    li.innerHTML = `
      <div style="display:flex; gap:10px; align-items:center; margin-bottom:10px;">
        <img src="${item.image}" style="width:50px; height:50px; border-radius:6px;">
        <div style="flex: 1;">
          <strong>${item.name}</strong> x${item.quantity}<br>
          ₹${item.price * item.quantity}
        </div>
      </div>
    `;
    summaryList.appendChild(li);
    total += item.price * item.quantity;
  });

  totalPrice.innerHTML = `<strong>Total: ₹${total}</strong>`;

  // Dynamic Payment Fields
  function updatePaymentFields() {
    const method = methodSelect.value;
    paymentDetails.innerHTML = "";

    if (method === "card") {
      paymentDetails.innerHTML = `
        <div class="section">
          <input type="text" name="cardNumber" placeholder="Card Number" required>
        <div class="row">
          <input type="text" name="expiry" placeholder="MM/YY" required>
          <input type="text" name="cvv" placeholder="CVV" required>
        </div>
        </div>
      `;
    } else if (method === "upi") {
      paymentDetails.innerHTML = `
        <input type="text" name="upiId" placeholder="Enter UPI ID" required>
      `;
    }
  }

  methodSelect.addEventListener("change", updatePaymentFields);
  updatePaymentFields(); // initialize on load
});

// Form submission
document.getElementById("paymentForm").addEventListener("submit", function (e) {
  e.preventDefault();

  const formData = new FormData(this);
  const data = Object.fromEntries(formData.entries());
  data.cart = JSON.parse(localStorage.getItem("cart")) || [];

  fetch("process.php", {
    method: "POST",
    headers: { "Content-Type": "application/json" },
    body: JSON.stringify(data)
  })
    .then(res => res.text())
    .then(msg => {
      document.getElementById("message").innerText = msg;
      localStorage.removeItem("cart");
      this.reset();
      document.getElementById("summaryList").innerHTML = "";
      document.getElementById("totalPrice").innerHTML = "<strong>Total: ₹0</strong>";
      document.getElementById("paymentDetails").innerHTML = "";
    });
});