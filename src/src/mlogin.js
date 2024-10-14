
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

const validateIfEmpty = (mid, password) => {
        validateNotEmpty("Manager Id", mid);
        validateNotEmpty("Password", password);
        return errors.length === 0;
}

const validateInputs = (mid, password) => {
    errors = [];
    
    if(!validateIfEmpty(mid, password)) {
        showErrorMessage(errors.join("\n"));
        return false;
    }

    return true;
}

const resetInput = () => {
    document.getElementById('mid').value = '';
    document.getElementById('password').value = '';
}

const loginManagerToXML = () => {
    if ((xHRObject.readyState == 4) && (xHRObject.status == 200)) {
        if(xHRObject.responseText.includes("Your manager account is not registered.") || 
            xHRObject.responseText.includes("Invalid manager account password.")) {
            showErrorMessage(xHRObject.responseText);
            resetInput();
        } else {
            //showSuccessMessage(xHRObject.responseText);
            window.location = 'listing.htm';
        }
	}
}

const loginManager = () => {
    var mid = document.getElementById('mid').value.trim();
    var password = document.getElementById('password').value.trim();

    if(!validateInputs(mid, password)) {
            return;
    };

    var url = `mlogin.php?&mid=${encodeURIComponent(mid)}&password=${password}&id=${Number(new Date)}`;
    const isAsynchronous = true;
    xHRObject.open("POST", url, isAsynchronous);
    xHRObject.onreadystatechange = loginManagerToXML;
	xHRObject.send(null);
    
}

document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('mlogin-form');
    form.addEventListener('submit', function(e) {
        e.preventDefault();
        loginManager();
    });
});