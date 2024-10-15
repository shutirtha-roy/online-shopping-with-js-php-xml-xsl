const createXHRObject = () => {
    return window.XMLHttpRequest ? new XMLHttpRequest() 
                : (window.ActiveXObject 
                ? new ActiveXObject("Microsoft.XMLHTTP") 
                : null)
}

let errors = [];
var xHRObject = createXHRObject();
let cart = JSON.parse(localStorage.getItem('cart')) || {};

const saveCart = () => {
    localStorage.setItem('cart', JSON.stringify(cart));
}

const updateShoppingCatalog = () => {
    var url = 'buying.php?action=get_catalog';
    isAsynchronous = true;
    xHRObject.open('GET', url, isAsynchronous);
    xHRObject.onreadystatechange = function() {
        if (xHRObject.readyState === 4 && xHRObject.status === 200) {
            document.getElementById('shopping-catalog').innerHTML = xHRObject.responseText;
            updateCartDisplay();
            attachAddToCartListeners();
            attachPurchaseButtonListeners();
            console.log(cart);
        }
    };
    xHRObject.send(null);
}

const attachAddToCartListeners = () => {
    const addButtons = document.querySelectorAll('.add-to-cart');
    addButtons.forEach(button => {
        button.addEventListener('click', function() {
            const itemNumber = this.getAttribute('data-item-number');
            addToCart(itemNumber);
        });
    });
}

const addToCart = (itemNumber) => {
    var url = `buying.php?action=add_to_cart&item_number=${itemNumber}`;
    xHRObject.open('GET', url, true);
    xHRObject.onreadystatechange = function() {
        if (xHRObject.readyState === 4 && xHRObject.status === 200) {
            const response = xHRObject.responseText;
            console.log("Add to cart response:", response);
            if (response.startsWith("Success")) {
                const [_, price, availableQuantity] = response.split("|");
                if (cart[itemNumber]) {
                    cart[itemNumber].quantity++;
                } else {
                    cart[itemNumber] = {
                        quantity: 1,
                        price: parseFloat(price)
                    };
                }
                updateCartDisplay();
                
                if (parseInt(availableQuantity) < 0) {
                    alert("Sorry, this item is not available for sale");
                    const addButton = document.querySelector(`.add-to-cart[data-item-number="${itemNumber}"]`);
                    if (addButton) {
                        addButton.disabled = true;
                        addButton.textContent = "Out of Stock";
                    }
                }
                
                updateShoppingCatalog();
            } else {
                alert(response);
            }
        }
    };
    xHRObject.send(null);
}

const updateCartDisplay = () => {
    console.log("Updating cart display. Current cart:", cart);
    const cartBody = document.getElementById('cartBody');
    cartBody.innerHTML = '';
    let total = 0;

    for (let itemNumber in cart) {
        const item = cart[itemNumber];
        const row = cartBody.insertRow();
        row.innerHTML = `
            <td>${itemNumber}</td>
            <td>${item.quantity}</td>
            <td>$${(item.price * item.quantity).toFixed(2)}</td>
            <td><button class="btn btn-danger remove-from-cart" data-item-number="${itemNumber}">Remove one from cart</button></td>
        `;
        total += item.price * item.quantity;
    }

    document.getElementById('cartTotal').textContent = `$${total.toFixed(2)}`;
    attachRemoveFromCartListeners();
    saveCart();
}

const attachRemoveFromCartListeners = () => {
    const removeButtons = document.querySelectorAll('.remove-from-cart');
    removeButtons.forEach(button => {
        button.addEventListener('click', function() {
            const itemNumber = this.getAttribute('data-item-number');
            removeFromCart(itemNumber);
        });
    });
}

const removeFromCart = (itemNumber) => {
    var url = `buying.php?action=remove_from_cart&item_number=${itemNumber}&quantity=1`;
    xHRObject.open('GET', url, true);
    xHRObject.onreadystatechange = function() {
        if (xHRObject.readyState === 4 && xHRObject.status === 200) {
            const response = xHRObject.responseText;
            console.log("Remove from cart response:", response);
            if (response === "Success") {
                if (cart[itemNumber].quantity > 1) {
                    cart[itemNumber].quantity--;
                } else {
                    delete cart[itemNumber];
                }
                saveCart();
                updateCartDisplay();
                updateShoppingCatalog();
            } else {
                alert(response);
            }
        }
    };
    xHRObject.send(null);
}

const confirmPurchase = () => {
    var url = `buying.php?action=confirm_purchase&cart=${JSON.stringify(cart)}`;
    xHRObject.open('GET', url, true);
    xHRObject.onreadystatechange = function() {
        if (xHRObject.readyState === 4 && xHRObject.status === 200) {
            const response = xHRObject.responseText;
            console.log("Confirm purchase response:", response);
            alert(response);
            cart = {};
            saveCart();
            updateCartDisplay();
            updateShoppingCatalog();
        }
    };
    xHRObject.send(null);
}

const cancelPurchase = () => {
    var url = `buying.php?action=cancel_purchase&cart=${JSON.stringify(cart)}`;
    xHRObject.open('GET', url, true);
    xHRObject.onreadystatechange = function() {
        if (xHRObject.readyState === 4 && xHRObject.status === 200) {
            const response = xHRObject.responseText;
            console.log("Cancel purchase response:", response);
            alert(response);
            cart = {};
            saveCart();
            updateCartDisplay();
            updateShoppingCatalog();
        }
    };
    xHRObject.send(null);
}

const attachPurchaseButtonListeners = () => {
    const confirmButton = document.getElementById('confirmPurchase');
    const cancelButton = document.getElementById('cancelPurchase');
    
    if (confirmButton) {
        confirmButton.addEventListener('click', confirmPurchase);
    }
    
    if (cancelButton) {
        cancelButton.addEventListener('click', cancelPurchase);
    }
}

document.addEventListener('DOMContentLoaded', function() {
    updateShoppingCatalog();
    setInterval(updateShoppingCatalog, 10000);
});