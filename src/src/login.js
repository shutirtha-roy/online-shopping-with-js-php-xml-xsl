
const createXHRObject = () => {
    return window.XMLHttpRequest ? new XMLHttpRequest() 
                : (window.ActiveXObject 
                ? new ActiveXObject("Microsoft.XMLHTTP") 
                : null)
}

let errors = [];
var xHRObject = createXHRObject();

const showErrorMessage = (message) => {
    const alertDiv = document.createElement('div');
    alertDiv.className = 'alert alert-danger alert-dismissible fade show';
    alertDiv.setAttribute('role', 'alert');
    alertDiv.innerHTML = `
        ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    `;

    const container = document.getElementById('successful_login');
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

const validateIfEmpty = (email, password) => {
        validateNotEmpty("Email", email);
        validateNotEmpty("Password", password);
        return errors.length === 0;
}

const validateInputs = (email, password) => {
    errors = [];
    
    if(!validateIfEmpty(email, password)) {
        showErrorMessage(errors.join("\n"));
        return false;
    }

    return true;
}

const resetInput = () => {
    document.getElementById('email').value = '';
    document.getElementById('password').value = '';
}

const loginUserToXML = () => {
    if ((xHRObject.readyState == 4) && (xHRObject.status == 200)) {
        if(xHRObject.responseText.includes("Login Failed")) {
            showErrorMessage(xHRObject.responseText);
            resetInput();
        } else {
            //showSuccessMessage(xHRObject.responseText);
            window.location = 'buying.htm';
        }
	}
}

const loginUser = () => {
    var email = document.getElementById('email').value.trim();
    var password = document.getElementById('password').value.trim();

    if(!validateInputs(email, password)) {
            return;
    };

    var url = `login.php?&email=${encodeURIComponent(email)}&password=${password}&id=${Number(new Date)}`;
    const isAsynchronous = true;
    xHRObject.open("POST", url, isAsynchronous);
    xHRObject.onreadystatechange = loginUserToXML;
	xHRObject.send(null);
    
}

document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('login-form');
    form.addEventListener('submit', function(e) {
        e.preventDefault();
        loginUser();
    });
});