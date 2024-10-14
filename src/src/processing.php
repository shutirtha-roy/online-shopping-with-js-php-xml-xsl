<?php
function loadXML($filename) {
    $xml = new DOMDocument();
    $xml->load($filename);
    return $xml;
}

function saveXML($xml, $filename) {
    $xml->formatOutput = true;
    $xml->save($filename);
}

function getSoldItems() {
    $items = loadXML('../../data/goods.xml');
    $output = '';

    foreach ($items->getElementsByTagName('item') as $item) {
        $quantitySold = intval($item->getElementsByTagName('quantity_sold')->item(0)->nodeValue);
        
        if ($quantitySold > 0) {
            $itemNumber = $item->getElementsByTagName('item_number')->item(0)->nodeValue;
            $itemName = $item->getElementsByTagName('item_name')->item(0)->nodeValue;
            $price = $item->getElementsByTagName('price')->item(0)->nodeValue;
            $quantityTotal = intval($item->getElementsByTagName('quantity_available')->item(0)->nodeValue);
            $quantityOnHold = intval($item->getElementsByTagName('quantity_onhold')->item(0)->nodeValue);
            $quantityAvailable = $quantityTotal - $quantityOnHold - $quantitySold;
            
            $output .= "<tr>
                <td>{$itemNumber}</td>
                <td>{$itemName}</td>
                <td>\${$price}</td>
                <td>{$quantityAvailable}</td>
                <td>{$quantityOnHold}</td>
                <td>{$quantitySold}</td>
            </tr>";
        }
    }

    echo $output;
}

function processItems() {
    $items = loadXML('../../data/goods.xml');
    $itemsToRemove = [];

    foreach ($items->getElementsByTagName('item') as $item) {
        $quantitySold = intval($item->getElementsByTagName('quantity_sold')->item(0)->nodeValue);
        
        if ($quantitySold > 0) {
            // Decrease the quantity_total by the amount sold
            $quantityTotal = intval($item->getElementsByTagName('quantity_available')->item(0)->nodeValue);
            $newQuantityTotal = $quantityTotal - $quantitySold;
            $item->getElementsByTagName('quantity_available')->item(0)->nodeValue = $newQuantityTotal;

            // Clear the quantity sold
            $item->getElementsByTagName('quantity_sold')->item(0)->nodeValue = 0;
            
            // Get the quantity on hold
            $quantityOnHold = intval($item->getElementsByTagName('quantity_onhold')->item(0)->nodeValue);
            
            // Check if the item should be removed
            if ($newQuantityTotal == 0 && $quantityOnHold == 0) {
                $itemsToRemove[] = $item;
            }
        }
    }

    // Remove items that have been completely sold
    foreach ($itemsToRemove as $item) {
        $item->parentNode->removeChild($item);
    }

    saveXML($items, '../../data/goods.xml');

    echo "Processing complete. Sold quantities cleared, totals updated, and completely sold items removed.";
}

if (isset($_GET['action'])) {
    switch ($_GET['action']) {
        case 'get_sold_items':
            getSoldItems();
            break;
        case 'process_items':
            processItems();
            break;
        default:
            echo "Invalid action";
    }
} else {
    echo "No action specified";
}
?>