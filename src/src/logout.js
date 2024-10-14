const createXHRObjectLogout = () => {
    return window.XMLHttpRequest ? new XMLHttpRequest() 
                : (window.ActiveXObject 
                ? new ActiveXObject("Microsoft.XMLHTTP") 
                : null)
}

var xHRObject = createXHRObjectLogout();

const showErrorLogoutMessage = (message) => {
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



const logoutManagerFromTheSystem = () => {
    if ((xHRObject.readyState == 4) && (xHRObject.status == 200)) {
        if(xHRObject.responseText.includes("You are not logged in.")) {
            showErrorLogoutMessage(xHRObject.responseText);
        } else {
            window.location.href = `logout.htm?id=${encodeURIComponent(xHRObject.responseText)}`;
        }
	}
}

const logoutCustomerFromTheSystem = () => {
    if ((xHRObject.readyState == 4) && (xHRObject.status == 200)) {
        if(xHRObject.responseText.includes("You are not logged in.")) {
            showErrorLogoutMessage(xHRObject.responseText);
        } else {
            window.location.href = `logout.htm?id=${encodeURIComponent(xHRObject.responseText)}`;
        }
	}
}

const logoutCustomer = () => {
    var url = `logout.php?isCustomer=YES`;
    const isAsynchronous = true;
    xHRObject.open("POST", url, isAsynchronous);
    xHRObject.onreadystatechange = logoutCustomerFromTheSystem;
	xHRObject.send(null);
}

const logoutManager = () => {
    var url = `logout.php?isManager=YES`;
    const isAsynchronous = true;
    xHRObject.open("POST", url, isAsynchronous);
    xHRObject.onreadystatechange = logoutManagerFromTheSystem;
	xHRObject.send(null);
}