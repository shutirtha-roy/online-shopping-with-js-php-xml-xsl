<?php
$xmlFile = "../../data/goods.xml";

function loadXML() {
    global $xmlFile;
    if (!file_exists($xmlFile)) {
        throw new Exception("XML file not found: $xmlFile");
    }
    $doc = new DOMDocument();
    $doc->load($xmlFile);
    return $doc;
}

function saveXML($doc) {
    global $xmlFile;
    if (!$doc->save($xmlFile)) {
        throw new Exception("Failed to save XML file: $xmlFile");
    }
}

function getCatalog() {
    $xmlDoc = loadXML();
    $xslDoc = new DOMDocument();
    $xslDoc->load("goods.xsl");
    $proc = new XSLTProcessor();
    $proc->importStyleSheet($xslDoc);
    return $proc->transformToXML($xmlDoc);
}

function addToCart($itemNumber) {
    $doc = loadXML();
    $items = $doc->getElementsByTagName('item');
    foreach ($items as $item) {
        if ($item->getElementsByTagName('item_number')->item(0)->nodeValue == $itemNumber) {
            $availableQuantity = intval($item->getElementsByTagName('quantity_available')->item(0)->nodeValue);
            $quantityOnhold = intval($item->getElementsByTagName('quantity_onhold')->item(0)->nodeValue);
            $quantitySold = intval($item->getElementsByTagName('quantity_sold')->item(0)->nodeValue);
            
            if ($availableQuantity > 0) {
                $newQuantityOnhold = $quantityOnhold + 1;
                $item->getElementsByTagName('quantity_onhold')->item(0)->nodeValue = $newQuantityOnhold;
                $price = $item->getElementsByTagName('price')->item(0)->nodeValue;
                $newAvailableQuantity = $availableQuantity - 1;
                $item->getElementsByTagName('quantity_available')->item(0)->nodeValue = $newAvailableQuantity;
                saveXML($doc);
                
                return "Success|$price|$newAvailableQuantity";
            } else {
                return "Sorry, this item is not available for sale";
            }
        }
    }
    return "Item not found";
}

function removeFromCart($itemNumber, $quantity) {
    $doc = loadXML();
    $items = $doc->getElementsByTagName('item');
    foreach ($items as $item) {
        if ($item->getElementsByTagName('item_number')->item(0)->nodeValue == $itemNumber) {
            $quantityAvailable = intval($item->getElementsByTagName('quantity_available')->item(0)->nodeValue);
            $quantityAvailable += 1;
            $quantityOnhold = intval($item->getElementsByTagName('quantity_onhold')->item(0)->nodeValue);
            $newQuantityOnhold = max(0, $quantityOnhold - $quantity);
            $item->getElementsByTagName('quantity_onhold')->item(0)->nodeValue = $newQuantityOnhold;
            $item->getElementsByTagName('quantity_available')->item(0)->nodeValue = $quantityAvailable;
            saveXML($doc);
            return "Success";
        }
    }
    return "Item not found";
}

function confirmPurchase($cart) {
    $doc = loadXML();
    $items = $doc->getElementsByTagName('item');
    $totalAmount = 0;
    
    foreach ($cart as $itemNumber => $itemData) {
        foreach ($items as $item) {
            if ($item->getElementsByTagName('item_number')->item(0)->nodeValue == $itemNumber) {
                $quantityOnhold = intval($item->getElementsByTagName('quantity_onhold')->item(0)->nodeValue);
                $quantitySold = intval($item->getElementsByTagName('quantity_sold')->item(0)->nodeValue);
                $price = floatval($item->getElementsByTagName('price')->item(0)->nodeValue);
                
                $purchaseQuantity = min($itemData['quantity'], $quantityOnhold);
                $item->getElementsByTagName('quantity_onhold')->item(0)->nodeValue = $quantityOnhold - $purchaseQuantity;
                $item->getElementsByTagName('quantity_sold')->item(0)->nodeValue = $quantitySold + $purchaseQuantity;
                
                $totalAmount += $price * $purchaseQuantity;
            }
        }
    }
    
    saveXML($doc);
    return "Your purchase has been confirmed and total amount due to pay is $" . number_format($totalAmount, 2);
}

function cancelPurchase($cart) {
    $doc = loadXML();
    $items = $doc->getElementsByTagName('item');
    
    foreach ($cart as $itemNumber => $itemData) {
        foreach ($items as $item) {
            if ($item->getElementsByTagName('item_number')->item(0)->nodeValue == $itemNumber) {
                $availableQuantity = intval($item->getElementsByTagName('quantity_available')->item(0)->nodeValue);
                $quantityOnhold = intval($item->getElementsByTagName('quantity_onhold')->item(0)->nodeValue);
                $item->getElementsByTagName('quantity_onhold')->item(0)->nodeValue = max(0, $quantityOnhold - $itemData['quantity']);
                $item->getElementsByTagName('quantity_available')->item(0)->nodeValue = max(0, $availableQuantity + $itemData['quantity']);
                break;
            }
        }
    }
    
    saveXML($doc);
    return "Your purchase request has been cancelled, welcome to shop next time";
}

try {
    if(isset($_GET["action"])) {
        $action = $_GET["action"];

        if ($action == 'get_catalog') {
            echo getCatalog();
        } elseif ($action == 'add_to_cart') {
            if(isset($_GET["item_number"])) {
                echo addToCart($_GET["item_number"]);
            } else {
                echo "Invalid request";
            }
        } elseif ($action == 'remove_from_cart') {
            if(isset($_GET["item_number"]) && isset($_GET["quantity"])) {
                echo removeFromCart($_GET["item_number"], intval($_GET["quantity"]));
            } else {
                echo "Invalid request";
            }
        } elseif ($action == 'confirm_purchase') {
            if(isset($_GET["cart"])) {
                $cart = json_decode($_GET["cart"], true);
                echo confirmPurchase($cart);
            } else {
                echo "Invalid request";
            }
        } elseif ($action == 'cancel_purchase') {
            if(isset($_GET["cart"])) {
                $cart = json_decode($_GET["cart"], true);
                echo cancelPurchase($cart);
            } else {
                echo "Invalid request";
            }
        } else {
            echo "Invalid action";
        }
    } else {
        echo "No action specified";
    }
} catch (Exception $e) {
    echo "An error occurred. Please try again later.";
}
?>