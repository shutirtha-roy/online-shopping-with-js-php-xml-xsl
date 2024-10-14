<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

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
            $quantityTotal = intval($item->getElementsByTagName('quantity_total')->item(0)->nodeValue);
            $quantityOnhold = intval($item->getElementsByTagName('quantity_onhold')->item(0)->nodeValue);
            $availableQuantity = $quantityTotal - $quantityOnhold;
            
            if ($availableQuantity > 0) {
                $item->getElementsByTagName('quantity_onhold')->item(0)->nodeValue = $quantityOnhold + 1;
                saveXML($doc);
                $price = $item->getElementsByTagName('price')->item(0)->nodeValue;
                return "Success|$price";
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
            $quantityOnhold = intval($item->getElementsByTagName('quantity_onhold')->item(0)->nodeValue);
            $newQuantityOnhold = max(0, $quantityOnhold - $quantity);
            $item->getElementsByTagName('quantity_onhold')->item(0)->nodeValue = $newQuantityOnhold;
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
                $quantityTotal = intval($item->getElementsByTagName('quantity_total')->item(0)->nodeValue);
                $quantityOnhold = intval($item->getElementsByTagName('quantity_onhold')->item(0)->nodeValue);
                $quantitySold = intval($item->getElementsByTagName('quantity_sold')->item(0)->nodeValue);
                $price = floatval($item->getElementsByTagName('price')->item(0)->nodeValue);
                
                $purchaseQuantity = min($itemData['quantity'], $quantityOnhold);
                $item->getElementsByTagName('quantity_total')->item(0)->nodeValue = $quantityTotal - $purchaseQuantity;
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
                $quantityOnhold = intval($item->getElementsByTagName('quantity_onhold')->item(0)->nodeValue);
                $item->getElementsByTagName('quantity_onhold')->item(0)->nodeValue = max(0, $quantityOnhold - $itemData['quantity']);
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

        switch ($action) {
            case 'get_catalog':
                echo getCatalog();
                break;
            case 'add_to_cart':
                if(isset($_GET["item_number"])) {
                    echo addToCart($_GET["item_number"]);
                } else {
                    echo "Invalid request";
                }
                break;
            case 'remove_from_cart':
                if(isset($_GET["item_number"]) && isset($_GET["quantity"])) {
                    echo removeFromCart($_GET["item_number"], intval($_GET["quantity"]));
                } else {
                    echo "Invalid request";
                }
                break;
            case 'confirm_purchase':
                if(isset($_GET["cart"])) {
                    $cart = json_decode($_GET["cart"], true);
                    echo confirmPurchase($cart);
                } else {
                    echo "Invalid request";
                }
                break;
            case 'cancel_purchase':
                if(isset($_GET["cart"])) {
                    $cart = json_decode($_GET["cart"], true);
                    echo cancelPurchase($cart);
                } else {
                    echo "Invalid request";
                }
                break;
            default:
                echo "Invalid action";
        }
    } else {
        echo "No action specified";
    }
} catch (Exception $e) {
    error_log("Error in buying.php: " . $e->getMessage());
    echo "An error occurred. Please try again later.";
}
?>