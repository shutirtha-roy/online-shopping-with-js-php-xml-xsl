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

const logoutManagerFromTheSystem = () => {
    if ((xHRObject.readyState == 4) && (xHRObject.status == 200)) {
        if(xHRObject.responseText.includes("You are not logged in.")) {
            showErrorMessage(xHRObject.responseText);
        } else {
            window.location.href = `logout.htm?managerId=${encodeURIComponent(xHRObject.responseText)}`;
        }
	}
}

const logoutManager = () => {
    var url = `logout.php?isManager=YES`;
    const isAsynchronous = true;
    xHRObject.open("POST", url, isAsynchronous);
    xHRObject.onreadystatechange = logoutManagerFromTheSystem;
	xHRObject.send(null);
}