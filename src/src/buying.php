<?php
    $xmlDoc = new DOMDocument('1.0');
    $xmlDoc->formatOutput = true;
    $xmlDoc->load("../../data/goods.xml");

    $xslDoc = new DomDocument('1.0');
    $xslDoc->load("goods.xsl");

    $proc = new XSLTProcessor;
    $proc->importStyleSheet($xslDoc); 

    $strXml= $proc->transformToXML($xmlDoc);

    echo ($strXml);
?>