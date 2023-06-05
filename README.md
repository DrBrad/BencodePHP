BencodePHP
========

This is an implementation of Bencode for JavaScript. Bencode is used for DHTs, Torrents, and Google DataServers. Its a lightweight fast data serialization.
[Wikipedia](https://en.wikipedia.org/wiki/Bencode)

I have also made an implementation of Bencode with [Java](https://github.com/DrBrad/Bencode) and [JavaScript](https://github.com/DrBrad/BencodeJS).

Usage
-----
Here are some examples of how to use the Bencode library.

**Bencode**
```PHP
//DATA MUST BE IN THE FORMAT: Uint8Array
$data = 'd4:dictd3:1234:test3:4565:thinge4:listl11:list-item-111:list-item-2e6:numberi123456e6:string5:valuee';

$bencode = new Bencode();
$result = $bencode->decode($data);

print_r(json_encode($result, JSON_PRETTY_PRINT));

echo $bencode->encode($result);
```
