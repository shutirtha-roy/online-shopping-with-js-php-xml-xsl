// Name: Shutirtha Roy
// Student ID: 105008711
// Course: COS80021 Web Application Development
// Function: This JavaScript code loads all the items which are sold from the server side php processing.php and 
// when the Process button is clicked it sends a request to update all the items based on the quantities available,
// quantities on hold, quantities sold.



const createXHRObject = () => {
    return window.XMLHttpRequest ? new XMLHttpRequest() 
                : (window.ActiveXObject 
                ? new ActiveXObject("Microsoft.XMLHTTP") 
                : null)
}

let errors = [];
var xHRObject = createXHRObject();

const loadItems = () => {
    var url = 'processing.php?action=get_sold_items';
    xHRObject.open('GET', url, true);
    xHRObject.onreadystatechange = function() {
        if (xHRObject.readyState === 4 && xHRObject.status === 200) {
            document.getElementById('itemsTable').getElementsByTagName('tbody')[0].innerHTML = xHRObject.responseText;
        }
    };
    xHRObject.send(null);
}

const processItems = () => {
    var url = 'processing.php?action=process_items';
    xHRObject.open('POST', url, true);
    xHRObject.onreadystatechange = function() {
        if (xHRObject.readyState === 4 && xHRObject.status === 200) {
            alert(xHRObject.responseText);
            loadItems();
        }
    };
    xHRObject.send(null);
}

document.addEventListener('DOMContentLoaded', function() {
    loadItems();
    document.getElementById('processButton').addEventListener('click', processItems);
});