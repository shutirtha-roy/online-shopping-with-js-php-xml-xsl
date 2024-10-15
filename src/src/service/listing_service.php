<?php
    function generateItemNumber($totalItem) {
        $itemNumber = ITEM_NUMBER_INDEX. ($totalItem + 1);
        return $itemNumber;
    }

    function prepareItemData($item_number, $item_name, $price, $quantity_total, $quantity_onhold, $quantity_sold, $description) {
        return [
            'item_number' => $item_number,
            'item_name' => $item_name,
            'price' => $price,
            'quantity_available' => $quantity_total,
            'quantity_onhold' => $quantity_onhold,
            'quantity_sold' => $quantity_sold,
            'description' => $description
        ];
    }

    function createItemXmlElement($doc, $parent, $elementName, $value) {
        $element = $doc->createElement($elementName);
        $parent->appendChild($element);
        $textNode = $doc->createTextNode(trim($value));
        $element->appendChild($textNode);
        return $element;
    }
?>