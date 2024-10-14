const createXHRObject = () => {
    return window.XMLHttpRequest ? new XMLHttpRequest() 
                : (window.ActiveXObject 
                ? new ActiveXObject("Microsoft.XMLHTTP") 
                : null)
}

let errors = [];
var xHRObject = createXHRObject();
let cart = {};

function updateShoppingCatalog() {
    var url = 'buying.php?action=get_catalog';
    isAsynchronous = true;
    xHRObject.open('GET', url, isAsynchronous);
    xHRObject.onreadystatechange = function() {
        if (xHRObject.readyState === 4 && xHRObject.status === 200) {
            document.getElementById('shopping-catalog').innerHTML = xHRObject.responseText;
            updateCartDisplay();
            attachAddToCartListeners();
            attachPurchaseButtonListeners();
        }
    };
    xHRObject.send(null);
}

function attachAddToCartListeners() {
    const addButtons = document.querySelectorAll('.add-to-cart');
    addButtons.forEach(button => {
        button.addEventListener('click', function() {
            const itemNumber = this.getAttribute('data-item-number');
            addToCart(itemNumber);
        });
    });
}

function addToCart(itemNumber) {
    var url = `buying.php?action=add_to_cart&item_number=${itemNumber}`;
    xHRObject.open('GET', url, true);
    xHRObject.onreadystatechange = function() {
        if (xHRObject.readyState === 4 && xHRObject.status === 200) {
            const response = xHRObject.responseText;
            console.log("Add to cart response:", response); // Debug log
            if (response.startsWith("Success")) {
                const [_, price] = response.split("|");
                if (cart[itemNumber]) {
                    cart[itemNumber].quantity++;
                } else {
                    cart[itemNumber] = {
                        quantity: 1,
                        price: parseFloat(price)
                    };
                }
                updateCartDisplay();
                updateShoppingCatalog();
            } else {
                alert(response);
            }
        }
    };
    xHRObject.send(null);
}

function updateCartDisplay() {
    console.log("Updating cart display. Current cart:", cart); // Debug log
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
}

function attachRemoveFromCartListeners() {
    const removeButtons = document.querySelectorAll('.remove-from-cart');
    removeButtons.forEach(button => {
        button.addEventListener('click', function() {
            const itemNumber = this.getAttribute('data-item-number');
            removeFromCart(itemNumber);
        });
    });
}

function removeFromCart(itemNumber) {
    var url = `buying.php?action=remove_from_cart&item_number=${itemNumber}&quantity=1`;
    xHRObject.open('GET', url, true);
    xHRObject.onreadystatechange = function() {
        if (xHRObject.readyState === 4 && xHRObject.status === 200) {
            const response = xHRObject.responseText;
            console.log("Remove from cart response:", response); // Debug log
            if (response === "Success") {
                if (cart[itemNumber].quantity > 1) {
                    cart[itemNumber].quantity--;
                } else {
                    delete cart[itemNumber];
                }
                updateCartDisplay();
                updateShoppingCatalog();
            } else {
                alert(response);
            }
        }
    };
    xHRObject.send(null);
}

function confirmPurchase() {
    var url = `buying.php?action=confirm_purchase&cart=${JSON.stringify(cart)}`;
    xHRObject.open('GET', url, true);
    xHRObject.onreadystatechange = function() {
        if (xHRObject.readyState === 4 && xHRObject.status === 200) {
            const response = xHRObject.responseText;
            console.log("Confirm purchase response:", response);
            alert(response);
            cart = {};
            updateCartDisplay();
            updateShoppingCatalog();
        }
    };
    xHRObject.send(null);
}

function cancelPurchase() {
    var url = `buying.php?action=cancel_purchase&cart=${JSON.stringify(cart)}`;
    xHRObject.open('GET', url, true);
    xHRObject.onreadystatechange = function() {
        if (xHRObject.readyState === 4 && xHRObject.status === 200) {
            const response = xHRObject.responseText;
            console.log("Cancel purchase response:", response); // Debug log
            alert(response);
            cart = {};
            updateCartDisplay();
            updateShoppingCatalog();
        }
    };
    xHRObject.send(null);
}

function attachPurchaseButtonListeners() {
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