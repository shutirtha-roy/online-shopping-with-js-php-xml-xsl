const createXHRObject = () => {
    return window.XMLHttpRequest ? new XMLHttpRequest() 
                : (window.ActiveXObject 
                ? new ActiveXObject("Microsoft.XMLHTTP") 
                : null)
}

let errors = [];
var xHRObject = createXHRObject();

const validateNotEmpty = (fieldName, value) => {
    if (!value) {
        errors.push(`${fieldName} is required.`);
    }
}

const validateNumericalInput = (fieldName, value) => {
    if (!isNaN(value) || value < 0) {
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

const addItemToXML = () => {
    if ((xHRObject.readyState == 4) && (xHRObject.status == 200)) {
        if(xHRObject.responseText.includes("item has been listed in the system")) {
            const container = document.getElementById('item_message');
            container.innerHTML = xHRObject.responseText;
            //POPULATE DATA
            resetInput();
        } else {
            const container = document.getElementById('item_message');
            container.innerHTML = xHRObject.responseText;
        }
	}
}

const addItem = () => {
    const itemName = document.getElementById('itemName').value.trim();
    const itemPrice = parseFloat(document.getElementById('itemPrice').value);
    const itemQuantity = parseInt(document.getElementById('itemQuantity').value);
    const itemDescription = document.getElementById('itemDescription').value.trim();

    if(!validateInputs(itemName, itemPrice, itemQuantity, itemDescription)) {
        return;
    };

    var url = `listing.php?itemName=${itemName}&itemPrice=${itemPrice}
        &itemQuantity=${itemQuantity}&itemDescription=${itemDescription}&id=${Number(new Date)}`;
    const isAsynchronous = true;
    xHRObject.open("POST", url, isAsynchronous);
    xHRObject.onreadystatechange = addItemToXML;
	xHRObject.send(null);
}

const resetInput = () => {
    document.getElementById('itemName').value = "";
    document.getElementById('itemPrice').value = "";
    document.getElementById('itemQuantity').value = "";
    document.getElementById('itemDescription').value = "";
}

document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('listing-form');
    form.addEventListener('submit', function(e) {
        e.preventDefault();
        addItem();
    });
});