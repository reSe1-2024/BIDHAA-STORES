let cart = [];
let total = 0;

function addToCart(name, price) {
    cart.push({ name, price });
    total += price;

    updateCart();
}

function updateCart() {
    const cartList = document.getElementById("cart-items");
    const totalDisplay = document.getElementById("total");

    cartList.innerHTML = "";

    cart.forEach(item => {
        const li = document.createElement("li");
        li.textContent = item.name + " - $" + item.price;
        cartList.appendChild(li);
    });

    totalDisplay.textContent = total;
}

const toggleBtn = document.getElementById('theme-toggle');

if (toggleBtn) {
    toggleBtn.addEventListener('click', () => {
        document.body.classList.toggle('dark');

        if (document.body.classList.contains('dark')) {
            document.cookie = "theme=dark; path=/; max-age=" + 60 * 60 * 24 * 30;
        } else {
            document.cookie = "theme=light; path=/; max-age=" + 60 * 60 * 24 * 30;
        }
    });

    window.onload = function () {
        if (document.cookie.includes("theme=dark")) {
            document.body.classList.add('dark');
        }
    };
}

const form = document.getElementById('checkout-form');
const error = document.getElementById('error');

if (form) {
    form.addEventListener('submit', function (e) {
        let name = document.getElementById('name').value;
        let email = document.getElementById('email').value;
        let quantity = document.getElementById('quantity').value;

        if (name.trim() === "") {
            e.preventDefault();
            error.textContent = "Name is required";
            return;
        }

        if (!email.includes("@")) {
            e.preventDefault();
            error.textContent = "Enter a valid email";
            return;
        }

        if (quantity <= 0) {
            e.preventDefault();
            error.textContent = "Quantity must be at least 1";
            return;
        }

        error.textContent = "";
    });
}

const cookieBox = document.getElementById('cookie-consent');
const acceptBtn = document.getElementById('accept-cookie');

if (cookieBox && acceptBtn) {
    if (document.cookie.includes("cookie_consent=true")) {
        cookieBox.style.display = "none";
    }

    acceptBtn.addEventListener('click', () => {
        document.cookie = "cookie_consent=true; path=/; max-age=" + 60 * 60 * 24 * 365;
        cookieBox.style.display = "none";
    });
}