<?php

    // Name: Shutirtha Roy
    // Student ID: 105008711
    // Course: COS80021 Web Application Development
    // Function: The file acts as a backend for for strong file in an XML data store. Besides that, it provides 
    // a series of functions according to various AJAX requests on the part 
    // of client-side JavaScript to provide for adding a new item from 
    // the form and updating the XML file in response to changes. Also, it generates an item number to insert
    // in the XML file.

    header('Content-Type: text/xml');

    include 'constants/listing-service-contants.php';
    include 'helpers/listing_validation.php';
    include 'service/listing_service.php';

    function getTotalItemLength() {
        $xmlFile = '../../data/goods.xml';

        if (!file_exists($xmlFile)){
            return 0;
        }

        $doc = new DOMDocument();
        $doc->load($xmlFile);
        $items = $doc->getElementsByTagName('item');
        $totalItems = $items->length;
        return $totalItems;
    }

    function addItemToXML($itemName, $itemPrice, $itemQuantity, $itemQuantityOnHold, $itemQuantitySold, $itemDescription) {
        try {
            $xmlFile = '../../data/goods.xml';
            
            $doc = new DomDocument();
                
            if (!file_exists($xmlFile)){
                $customers = $doc->createElement('items');
                $doc->appendChild($customers);
            }
            else {
                $doc->preserveWhiteSpace = FALSE; 
                $doc->load($xmlFile);  
            }

            $items = $doc->getElementsByTagName('items')->item(0);
            $item = $doc->createElement('item');
            $items->appendChild($item);
            
            $item_id = generateItemNumber(getTotalItemLength());

            $itemData = prepareItemData($item_id, $itemName, $itemPrice, $itemQuantity, $itemQuantityOnHold, $itemQuantitySold, $itemDescription);
            
            foreach ($itemData as $key => $value) {
                createItemXmlElement($doc, $item, $key, $value);
            }

            $doc->formatOutput = true;
            $customerXML = $doc->save($xmlFile);  
            chmod('../../data/goods.xml', 0777);

            return $item_id;
        }
        catch (Exception $e) {
            return "INVALID";
        }
    }

    function addItem($itemName, $itemPrice, $itemQuantity, $itemDescription) {
        $hasCorrectInput = hasCorrectItemInputs($itemName, $itemPrice, $itemQuantity, $itemDescription); 
        
        if(!$hasCorrectInput['success']) {
            echo $hasCorrectInput['errors'];
            return;
        }

        $item_number = addItemToXML($itemName, $itemPrice, $itemQuantity, 0, 0, $itemDescription);

        if($item_number != "INVALID") {
            echo "The item has been listed in the system, and the item number is:$item_number";
        }

        if($item_number == "INVALID") {
            echo ERROR_XML_CREATION;
        }
    }

    if(isset($_GET["itemName"]) && isset($_GET["itemPrice"]) 
        && isset($_GET["itemQuantity"]) && isset($_GET["itemDescription"])) {
        $itemName = $_GET["itemName"];
        $itemPrice = floatval($_GET["itemPrice"]);
        $itemQuantity = intval($_GET["itemQuantity"]);
        $itemDescription = $_GET["itemDescription"];

        addItem($itemName, $itemPrice, $itemQuantity, $itemDescription);
    }
?>