<?php
header('Content-Type: text/xml');

function generateItemNumber() {
    // Simple implementation - you might want to make this more sophisticated
    return 'ITM' . str_pad(rand(1, 9999), 4, '0', STR_PAD_LEFT);
}

function addItemToXML($item) {
    $xmlFile = '../../data/goods.xml';
    
    $doc = new DomDocument();
		
    if (!file_exists($xmlfile)){
        $customers = $doc->createElement('items');
        $doc->appendChild($customers);
    }
    else {
        $doc->preserveWhiteSpace = FALSE; 
        $doc->load($xmlfile);  
    }

    $items = $doc->getElementsByTagName('items')->item(0);
    
    $newItem = $xml->addChild('item');
    $newItem->addChild('itemNumber', $item['itemNumber']);
    $newItem->addChild('name', $item['name']);
    $newItem->addChild('price', $item['price']);
    $newItem->addChild('quantity', $item['quantity']);
    $newItem->addChild('description', $item['description']);
    $newItem->addChild('quantityOnHold', 0);
    $newItem->addChild('quantitySold', 0);
    
    $xml->asXML($xmlFile);
}

// Process the incoming data
$itemName = filter_input(INPUT_POST, 'itemName', FILTER_SANITIZE_STRING);
$itemPrice = filter_input(INPUT_POST, 'itemPrice', FILTER_VALIDATE_FLOAT);
$itemQuantity = filter_input(INPUT_POST, 'itemQuantity', FILTER_VALIDATE_INT);
$itemDescription = filter_input(INPUT_POST, 'itemDescription', FILTER_SANITIZE_STRING);

if ($itemName && $itemPrice && $itemQuantity && $itemDescription) {
    $itemNumber = generateItemNumber();
    $item = [
        'itemNumber' => $itemNumber,
        'name' => $itemName,
        'price' => $itemPrice,
        'quantity' => $itemQuantity,
        'description' => $itemDescription
    ];
    
    addItemToXML($item);
    
    echo json_encode(['success' => true, 'itemNumber' => $itemNumber]);
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid input data.']);
}
?>