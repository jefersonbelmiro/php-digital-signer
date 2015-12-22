# php-digital-signer

# Usage

```php
require_once("bootstrap.php");

$pkcs12 = new \DS\Certificate\PKCS12();

$reader = \DS\Certificate\Reader\ReaderFactory::create($pkcs12);
$reader->setContent(file_get_contents('/path/to/cert_a1.pfx'));
$reader->setPassword('pfx_password');
$reader->read();

$signer = new \DS\Signer\XMLSigner();
$signer->setCertificate($pkcs12);
$signer->setElement($domElementInstance);  // DOMElement instance that will be signed
$signer->sign(); // Will change $domElementInstance->parentNode, appending "Signature" tag

```

# Contribute

```
$ composer install
```

## Running Tests
```
$ composer test
```

# License
MIT
