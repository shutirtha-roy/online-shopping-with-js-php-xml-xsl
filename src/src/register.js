
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

const validateAustralianPhoneNumber = (phoneNumber) => {    
    if (phoneNumber.length === 12) {
        return /^\(0\d\)\d{8}$/.test(phoneNumber);
    } 

    if (phoneNumber.length === 11){
        return /^0\d \d{8}$/.test(phoneNumber);
    }

    errors.push("Please enter a correct phone number format.");
    return false;
}

const validateIfEmpty = (firstName, lastName, password, confirmPassword,
    email
    ) => {
        validateNotEmpty("First Name", firstName);
        validateNotEmpty("Last Name", lastName);
        validateNotEmpty("Password", password);
        validateNotEmpty("Confirm Password", confirmPassword);
        validateNotEmpty("Email", email);

        return errors.length === 0;
}

const validatePassword = (password, confirmPassword) => {
    if (password !== confirmPassword) {
        errors.push("The Passwords do not match. Please match the password.");
        return false;
    }

    if (password.length < 6) {
        errors.push("Password must be at least 8 characters long.");
        return false;
    }

    if (password.length > 16) {
        errors.push("Password must not exceed 16 characters.");
        return false;
    }

    return true;
}

const validateInputs = (firstName, lastName, password, confirmPassword,
    email, phone
    ) => {
    errors = [];
    
    if(!validateIfEmpty(firstName, lastName, password, confirmPassword,
        email)) {
        showErrorMessage(errors.join("\n"));
        return false;
    }

    if(phone != "" && !validateAustralianPhoneNumber(phone)) {
        showErrorMessage(errors.join("\n"));
        return false;
    }

    if(!validatePassword(password, confirmPassword)) {
        showErrorMessage(errors.join("\n"));
        return false;
    }

    return true;
}

const showSuccessMessage = (message) => {
    const alertDiv = document.createElement('div');
    alertDiv.className = 'alert alert-success alert-dismissible fade show';
    alertDiv.setAttribute('role', 'alert');
    alertDiv.innerHTML = `
        ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    `;

    const container = document.getElementById('successful_registration');
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

    const container = document.getElementById('successful_registration');
    container.innerHTML = '';

    container.appendChild(alertDiv);

    const duration = 3000;
    setTimeout(() => {
        alertDiv.classList.remove('show');
        setTimeout(() => alertDiv.remove(), 150);
    }, duration);
}


const registerUserToXML = () => {
    if ((xHRObject.readyState == 4) && (xHRObject.status == 200)) {
        if(xHRObject.responseText.includes("Your email is already registered.")) {
            showErrorMessage(xHRObject.responseText);
        } else {
            showSuccessMessage(xHRObject.responseText);
        }
	}
}

const registerUser = () => {
    var firstName = document.getElementById('fname').value.trim();
    var lastName = document.getElementById('lname').value.trim();
    var password = document.getElementById('password').value.trim();
    var confirmPassword = document.getElementById('confirm_password').value.trim();
	var email = document.getElementById('email').value.trim();
	var phone = document.getElementById('phone').value.trim();

    if(!validateInputs(firstName, lastName, password, confirmPassword,
        email, phone)) {
            return;
    };

    var url = `register.php?fname=${firstName}&lname=${lastName}
        &password=${password}&confirm_password=${confirmPassword}
        &email=${encodeURIComponent(email)}&phone=${phone}&id=${Number(new Date)}`;
    const isAsynchronous = true;
    xHRObject.open("POST", url, isAsynchronous);
    xHRObject.onreadystatechange = registerUserToXML;
	xHRObject.send(null);
    
}