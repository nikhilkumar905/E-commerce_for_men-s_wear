document.addEventListener("DOMContentLoaded", () => {
    // Handling product click and storing data
    document.querySelectorAll(".pro").forEach(product => {
        product.addEventListener("click", function () {
            selectProduct(this);
        });
    });

    // Load selected product details in sproduct.html
    const sproductPage = document.querySelector("#sproduct");
    if (sproductPage) {
        let selectedProduct = JSON.parse(localStorage.getItem("selectedProduct"));

        if (selectedProduct) {
            document.querySelector("#sproduct-img").src = selectedProduct.imgSrc;
            document.querySelector("#sproduct-name").innerText = selectedProduct.name;
            document.querySelector("#sproduct-brand").innerText = selectedProduct.brand;
            document.querySelector("#sproduct-price").innerText = selectedProduct.price;
        }
    }

    // Handling cart display in cart.html
    const cartBody = document.querySelector("#cart tbody");
    if (cartBody) {
        let cart = JSON.parse(localStorage.getItem("cart")) || [];

        if (cart.length === 0) {
            cartBody.innerHTML = `<tr><td colspan="6" style="text-align:center;">Your cart is empty.</td></tr>`;
        } else {
            cartBody.innerHTML = "";
            cart.forEach((product, index) => {
                let price = parseFloat(product.price.replace("$", ""));
                let quantity = product.quantity || 1;
                cartBody.innerHTML += `
                    <tr class="cart-item">
                        <td><a href="#" class="remove-item" data-index="${index}"><i class="fa-solid fa-xmark"></i></a></td>
                        <td><img src="${product.image}" alt="${product.name}" width="50"></td>
                        <td>${product.name}</td>
                        <td class="item-price">$${price.toFixed(2)}</td>
                        <td><input type="number" value="${quantity}" min="1" class="item-quantity" data-index="${index}"></td>
                        <td class="item-subtotal">$${(price * quantity).toFixed(2)}</td>
                    </tr>
                `;
            });

            document.querySelectorAll(".remove-item").forEach(button => {
                button.addEventListener("click", (event) => {
                    event.preventDefault();
                    let index = event.target.closest(".remove-item").getAttribute("data-index");
                    cart.splice(index, 1);
                    localStorage.setItem("cart", JSON.stringify(cart));
                    location.reload();
                });
            });

            document.querySelectorAll(".item-quantity").forEach(input => {
                input.addEventListener("change", (event) => {
                    let index = event.target.getAttribute("data-index");
                    cart[index].quantity = Math.max(1, parseInt(event.target.value) || 1);
                    localStorage.setItem("cart", JSON.stringify(cart));
                    location.reload();
                });
            });
        }
    }
});

// Function to store selected product details and navigate to sproduct.html
function selectProduct(element) {
    let imgSrc = element.querySelector("img").src;
    let name = element.querySelector(".des h5").innerText;
    let price = element.querySelector(".des h4").innerText.replace("$", ""); // Ensure price is a number

    let product = { 
        image: imgSrc, 
        name: name, 
        price: parseFloat(price) // Convert price to a number
    };

    localStorage.setItem("selectedProduct", JSON.stringify(product));
    window.location.href = "sproduct.html";
}



// Function to add selected product to cart
function addToCart() {
    let selectedProduct = JSON.parse(localStorage.getItem("selectedProduct"));
    if (!selectedProduct) return;

    let cart = JSON.parse(localStorage.getItem("cart")) || [];
    let existingProduct = cart.find(product => product.name === selectedProduct.name);

    if (existingProduct) {
        existingProduct.quantity += 1;
    } else {
        selectedProduct.quantity = 1;
        cart.push(selectedProduct);
    }

    localStorage.setItem("cart", JSON.stringify(cart));
    alert("Product added to cart!");
}


