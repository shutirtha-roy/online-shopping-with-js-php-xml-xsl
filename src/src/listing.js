const createXHRObject = () => {
    return window.XMLHttpRequest ? new XMLHttpRequest() 
                : (window.ActiveXObject 
                ? new ActiveXObject("Microsoft.XMLHTTP") 
                : null)
}

let errors = [];
var xHRObject = createXHRObject();

const showSuccessMessage = (message) => {
    const alertDiv = document.createElement('div');
    alertDiv.className = 'alert alert-success alert-dismissible fade show';
    alertDiv.setAttribute('role', 'alert');
    alertDiv.innerHTML = `
        ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    `;

    const container = document.getElementById('login_message');
    container.innerHTML = '';

    container.appendChild(alertDiv);

    const duration = 3000;
    setTimeout(() => {
        alertDiv.classList.remove('show');
        setTimeout(() => alertDiv.remove(), 150);
    }, duration);
}

const showErrorMessage = (message) => {
    const alertDiv = document.createElement('div');
    alertDiv.className = 'alert alert-danger alert-dismissible fade show';
    alertDiv.setAttribute('role', 'alert');
    alertDiv.innerHTML = `
        ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    `;

    const container = document.getElementById('login_message');
    container.innerHTML = '';

    container.appendChild(alertDiv);

    const duration = 3000;
    setTimeout(() => {
        alertDiv.classList.remove('show');
        setTimeout(() => alertDiv.remove(), 150);
    }, duration);
}

const validateNotEmpty = (fieldName, value) => {
    if (!value) {
        errors.push(`${fieldName} is required.`);
    }
}

const validateNumericalInput = (fieldName, value) => {
    if (!isNaN(value)) {
        errors.push(`${fieldName} is invalid.`);
    }
}

const validateIfEmpty = (itemName, itemPrice, itemQuantity, itemDescription) => {
    validateNotEmpty("Item Name", itemName);
    validateNotEmpty("Item Description", itemDescription);
    return errors.length === 0;
}

const validateNumericalInputs = (itemPrice, itemQuantity) => {
    validateNumericalInput("Item Price", itemPrice);
    validateNumericalInput("Item Quantity", itemQuantity);
}

const validateInputs = (itemName, itemPrice, itemQuantity, itemDescription) => {
    errors = [];

    if(!validateIfEmpty(itemName, itemPrice, itemQuantity, itemDescription)
        || validateNumericalInputs(itemPrice, itemQuantity)) {
        showErrorMessage(errors.join("\n"));
        return false;
    }

    return true;
}

const addItem = () => {
    const itemName = document.getElementById('itemName').value.trim();
    const itemPrice = parseFloat(document.getElementById('itemPrice').value);
    const itemQuantity = parseInt(document.getElementById('itemQuantity').value);
    const itemDescription = document.getElementById('itemDescription').value.trim();

    if(!validateInputs(itemName, itemPrice, itemQuantity, itemDescription)) {
        return;
    };
}

const resetInput = () => {
    document.getElementById('itemName').value = "";
    document.getElementById('itemPrice').value = "";
    document.getElementById('itemQuantity').value = "";
    document.getElementById('itemDescription').value = "";
}

document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('mlogin-form');
    form.addEventListener('submit', function(e) {
        e.preventDefault();
        addItem();
    });
});