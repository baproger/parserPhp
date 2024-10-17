<?php

$vincode = readline("Vincode: ");

function getCarInfoFromClearVinApi(string $vincode)
{
    $ch = curl_init("https://www.clearvin.com/ru/payment/prepare/$vincode/");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_BINARYTRANSFER, true);
    $response = curl_exec($ch);
    curl_close($ch);

    return $response;
}

function getData( string $vincode )
{
    $resault = getCarInfoFromClearVinApi($vincode);
//    print_r($resault);
    $dom = new DOMDocument();

// Suppress warnings if HTML is not well-formed.
    libxml_use_internal_errors(true);
    $dom->loadHTML($resault);
    libxml_clear_errors();

    $xpath = new DOMXPath($dom);
    $className = 'details-card__stat-value';

// Query elements by class name.
    $nodes = $xpath->query("//*[contains(concat(' ', normalize-space(@class), '  '), ' $className ')]");

// Output the content of the found nodes.
    foreach ($nodes as $node) {
        echo $dom->saveHTML($node) . PHP_EOL;
    }

    $data = [
        'image' => $nodes[0],
        'engine' => $nodes[1]
    ];

    return json_encode($data);

}
print_r(getData($vincode));