// Get elements
const cartButton = document.querySelector(".cart-button");
const cartBadge = document.querySelector(".cart-badge");
const modal = document.querySelector(".modal");
const modalClose = document.querySelector(".close");
const buyButton = document.querySelector(".buy-btn");
const cartItemsList = document.querySelector(".cart-items");
const cartTotal = document.querySelector(".cart-total");
const itemsGrid = document.querySelector(".items-grid");
const addToCartButtons = document.querySelectorAll(".add-to-cart-btn");

let items = [
  {
    id: 1,
    name: "Apple",
    price: 0.99,
    quantity: 0,
  },
  {
    id: 2,
    name: "Banana",
    price: 10,
    quantity: 0,
  },
  {
    id: 3,
    name: "Orange",
    price: 1.99,
    quantity: 0,
  },
  {
    id: 4,
    name: "Grapes",
    price: 5.99,
    quantity: 0,
  },
  {
    id: 5,
    name: "Watermelon",
    price: 3.49,
    quantity: 0,
  },
  {
    id: 6,
    name: "Pineapple",
    price: 2.99,
    quantity: 0,
  },
  {
    id: 7,
    name: "Strawberry",
    price: 1.49,
    quantity: 0,
  },
  {
    id: 8,
    name: "Mango",
    price: 4.99,
    quantity: 0,
  },
  {
    id: 9,
    name: "Cherry",
    price: 2.49,
    quantity: 0,
  },
  {
    id: 10,
    name: "Pear",
    price: 1.99,
    quantity: 0,
  },
  {
    id: 11,
    name: "Kiwi",
    price: 3.99,
    quantity: 0,
  },
  {
    id: 12,
    name: "Lemon",
    price: 0.79,
    quantity: 0,
  },
  {
    id: 13,
    name: "Lime",
    price: 0.85,
    quantity: 0,
  },
  {
    id: 14,
    name: "Peach",
    price: 2.79,
    quantity: 0,
  },
  {
    id: 15,
    name: "Plum",
    price: 1.49,
    quantity: 0,
  },
  {
    id: 16,
    name: "Mango",
    price: 3.99,
    quantity: 0,
  },
];

let cart = [];

// An example function that creates HTML elements using the DOM.
function fillItemsGrid() {
  for (const item of items) {
    let itemElement = document.createElement("div");
    itemElement.classList.add("item");
    itemElement.innerHTML = `
            <div class="item-img">
              <img src="https://picsum.photos/200/300?random=${item.id}" alt="${item.name}">
            </div>
            <h2>${item.name}</h2>
            <p>$${item.price}</p>
            <button class="add-to-cart-btn" data-id="${item.id}">Add to cart</button>
        `;
    itemsGrid.appendChild(itemElement);
  }

  const addToCartButtons = document.querySelectorAll(".add-to-cart-btn");

  addToCartButtons.forEach((button) => {
    button.addEventListener("click", (event) => {
      addToCart(parseInt(event.target.dataset.id));
    });
  });

  const searchInput = document.getElementById("search-input");

  searchInput.addEventListener("keyup", () => {
    const searchTerm = searchInput.value.toLowerCase();
    const filteredItems = items.filter((item) =>
      item.name.toLowerCase().startsWith(searchTerm)
    );
    itemsGrid.innerHTML = "";
    for (const item of filteredItems) {
      let itemElement = document.createElement("div");
      itemElement.classList.add("item");
      itemElement.innerHTML = `
            <div class="item-img">
              <img src="https://picsum.photos/200/300?random=${item.id}" alt="${item.name}">
            </div>
            <h2>${item.name}</h2>
            <p>$${item.price}</p>
            <button class="add-to-cart-btn" data-id="${item.id}">Add to cart</button>
        `;
      itemsGrid.appendChild(itemElement);
    }

    const addToCartButtons = document.querySelectorAll(".add-to-cart-btn");
    addToCartButtons.forEach((button) => {
      button.addEventListener("click", (event) => {
        addToCart(parseInt(event.target.dataset.id));
      });
    });
  });
}

function addToCart(itemId) {
  const item = items.find((item) => item.id === itemId);
  const cartItem = cart.find((item) => item.id === itemId);
  if (cartItem) {
    cartItem.quantity += 1;
  } else {
    cart.push({ ...item, quantity: 1 });
  }
  cartBadge.textContent = cart.length;
}

function removeFromCart(itemId, event) {
  const index = cart.findIndex((item) => item.id === itemId);
  if (index !== -1) {
    if (cart[index].quantity > 1) {
      cart[index].quantity -= 1;
      event.querySelector(
        "h3"
      ).textContent = `${cart[index].name} x${cart[index].quantity}`;
      event.querySelector("p").textContent = `$${
        cart[index].price * cart[index].quantity
      }`;
    } else {
      cart.splice(index, 1);
      event.remove();
    }
  }
  const totalElement = document.querySelector(".cart-total");
  totalElement.textContent = `$${calculateTotal().toFixed(2)}`;
  cartBadge.textContent = cart.length;
}

function calculateTotal() {
  return cart.reduce((total, item) => total + item.price * item.quantity, 0);
}

function purchaseItems() {
  if (cart.length === 0) {
    alert(
      "Your cart is empty. Please add items to your cart before purchasing."
    );
    return;
  }

  alert("Purchase successful!");
  cart.length = 0;
  cartBadge.textContent = cart.length;
  toggleModal();
}

document.querySelector(".buy-btn").addEventListener("click", purchaseItems);

function toggleModal() {
  modal.classList.toggle("show-modal");
  const cartItemsElement = document.querySelector(".cart-items");
  cartItemsElement.innerHTML = "";
  for (const item of cart) {
    let itemElement = document.createElement("div");
    itemElement.classList.add("cart-item");
    itemElement.innerHTML = `
        <h3>${item.name} x${item.quantity}</h3>
        <p>$${item.price * item.quantity}</p>
        <button class="remove-from-cart-btn" data-id="${
          item.id
        }">Remove</button>
        
      `;
    cartItemsElement.appendChild(itemElement);
  }

  const removeItemButtons = document.querySelectorAll(".remove-from-cart-btn");
  removeItemButtons.forEach((button) => {
    button.addEventListener("click", (event) => {
      removeFromCart(
        parseInt(event.target.dataset.id),
        event.target.parentNode
      );
    });
  });

  const totalElement = document.querySelector(".cart-total");
  totalElement.textContent = ` $${calculateTotal().toFixed(2)}`;
}

// Call fillItemsGrid function when page loads
fillItemsGrid();

// Example of DOM methods for adding event handling
cartButton.addEventListener("click", toggleModal);
modalClose.addEventListener("click", toggleModal);
