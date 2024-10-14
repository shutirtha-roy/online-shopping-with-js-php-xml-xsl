const createXHRObject = () => {
    return window.XMLHttpRequest ? new XMLHttpRequest() 
                : (window.ActiveXObject 
                ? new ActiveXObject("Microsoft.XMLHTTP") 
                : null)
}

let errors = [];
var xHRObject = createXHRObject();

function updateShoppingCatalog() {
    var url = 'buying.php';
    isAsynchronous = true;
    xHRObject.open('GET', url, isAsynchronous);
    xHRObject.onreadystatechange = setData;
    xHRObject.send(null);
}

const setData = () => {
    if (xHRObject.readyState === 4 && xHRObject.status === 200) {
        document.getElementById('shopping-catalog').innerHTML = xHRObject.responseText;
    }
}

document.addEventListener('DOMContentLoaded', function() {
    updateShoppingCatalog();
    setInterval(updateShoppingCatalog, 10000);
});