<?php
    // Name: Shutirtha Roy
    // Student ID: 105008711
    // Course: COS80021 Web Application Development
    // Function: This file retrieves and displays sold items from the XML file, 
    // and then process request is initiated. It processes these items by 
    // clearing sold quantities and completely removes the sold items when quantities available
    // and quantities on hold are same.
    
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
                
                $output .= "<tr>
                    <td>{$itemNumber}</td>
                    <td>{$itemName}</td>
                    <td>\${$price}</td>
                    <td>{$quantityTotal }</td>
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
                $item->getElementsByTagName('quantity_sold')->item(0)->nodeValue = 0;
                $quantityAvailable = intval($item->getElementsByTagName('quantity_available')->item(0)->nodeValue);
                $quantityOnHold = intval($item->getElementsByTagName('quantity_onhold')->item(0)->nodeValue);
                
                if ($quantityAvailable == 0 && $quantityOnHold == 0) {
                    $itemsToRemove[] = $item;
                }
            }
        }

        foreach ($itemsToRemove as $item) {
            $item->parentNode->removeChild($item);
        }

        saveXML($items, '../../data/goods.xml');

        echo "Processing complete. Sold quantities cleared, totals updated, and completely sold items removed.";
    }

    if (isset($_GET['action'])) {
        if ($_GET['action'] === 'get_sold_items') {
            getSoldItems();
        } elseif ($_GET['action'] === 'process_items') {
            processItems();
        } else {
            echo "Invalid action";
        }
    } else {
        echo "No action specified";
    }
?>