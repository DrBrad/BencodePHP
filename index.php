<?php
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);

    header('Content-Type: application/json; charset=utf-8');

    //$buf = file_get_contents('BLANK.torrent');
    $data = 'd4:dictd3:1234:test3:4565:thinge4:listl11:list-item-111:list-item-2e6:numberi123456e6:string5:valuee';

    $bencode = new Bencode($data);
    $result = $bencode->decode();

    print_r(json_encode($result, JSON_PRETTY_PRINT));
?>
