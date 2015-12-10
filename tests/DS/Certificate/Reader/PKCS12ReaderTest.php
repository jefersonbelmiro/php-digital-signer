<?php

namespace DS\Certificate\Reader;

use \DS\Certificate\PKCS12;

class PKCS12ReaderTest extends \PHPUnit_Framework_TestCase
{

  private $reader;

  private $certificate;

  private $pubKey = '-----BEGIN CERTIFICATE-----
MIIEqzCCA5OgAwIBAgIDMTg4MA0GCSqGSIb3DQEBBQUAMIGSMQswCQYDVQQGEwJC
UjELMAkGA1UECBMCUlMxFTATBgNVBAcTDFBvcnRvIEFsZWdyZTEdMBsGA1UEChMU
VGVzdGUgUHJvamV0byBORmUgUlMxHTAbBgNVBAsTFFRlc3RlIFByb2pldG8gTkZl
IFJTMSEwHwYDVQQDExhORmUgLSBBQyBJbnRlcm1lZGlhcmlhIDEwHhcNMDkwNTIy
MTcwNzAzWhcNMTAxMDAyMTcwNzAzWjCBnjELMAkGA1UECBMCUlMxHTAbBgNVBAsT
FFRlc3RlIFByb2pldG8gTkZlIFJTMR0wGwYDVQQKExRUZXN0ZSBQcm9qZXRvIE5G
ZSBSUzEVMBMGA1UEBxMMUE9SVE8gQUxFR1JFMQswCQYDVQQGEwJCUjEtMCsGA1UE
AxMkTkZlIC0gQXNzb2NpYWNhbyBORi1lOjk5OTk5MDkwOTEwMjcwMIGfMA0GCSqG
SIb3DQEBAQUAA4GNADCBiQKBgQCx1O/e1Q+xh+wCoxa4pr/5aEFt2dEX9iBJyYu/
2a78emtorZKbWeyK435SRTbHxHSjqe1sWtIhXBaFa2dHiukT1WJyoAcXwB1GtxjT
2VVESQGtRiujMa+opus6dufJJl7RslAjqN/ZPxcBXaezt0nHvnUB/uB1K8WT9G7E
S0V17wIDAQABo4IBfjCCAXowIgYDVR0jAQEABBgwFoAUPT5TqhNWAm+ZpcVsvB7m
alDBjEQwDwYDVR0TAQH/BAUwAwEBADAPBgNVHQ8BAf8EBQMDAOAAMAwGA1UdIAEB
AAQCMAAwgawGA1UdEQEBAASBoTCBnqA4BgVgTAEDBKAvBC0yMjA4MTk3Nzk5OTk5
OTk5OTk5MDAwMDAwMDAwMDAwMDAwMDAwMDAwMDAwMDCgEgYFYEwBAwKgCQQHREZU
LU5GZaAZBgVgTAEDA6AQBA45OTk5OTA5MDkxMDI3MKAXBgVgTAEDB6AOBAwwMDAw
MDAwMDAwMDCBGmRmdC1uZmVAcHJvY2VyZ3MucnMuZ292LmJyMCAGA1UdJQEB/wQW
MBQGCCsGAQUFBwMCBggrBgEFBQcDBDBTBgNVHR8BAQAESTBHMEWgQ6BBhj9odHRw
Oi8vbmZlY2VydGlmaWNhZG8uc2VmYXoucnMuZ292LmJyL0xDUi9BQ0ludGVybWVk
aWFyaWEzOC5jcmwwDQYJKoZIhvcNAQEFBQADggEBAJFytXuiS02eJO0iMQr/Hi+O
x7/vYiPewiDL7s5EwO8A9jKx9G2Baz0KEjcdaeZk9a2NzDEgX9zboPxhw0RkWahV
CP2xvRFWswDIa2WRUT/LHTEuTeKCJ0iF/um/kYM8PmWxPsDWzvsCCRp146lc0lz9
LGm5ruPVYPZ/7DAoimUk3bdCMW/rzkVYg7iitxHrhklxH7YWQHUwbcqPt7Jv0RJx
clc1MhQlV2eM2MO1iIlk8Eti86dRrJVoicR1bwc6/YDqDp4PFONTi1ddewRu6elG
S74AzCcNYRSVTINYiZLpBZO0uivrnTEnsFguVnNtWb9MAHGt3tkR0gAVs6S0fm8=
-----END CERTIFICATE-----
';

  private $privKey = '-----BEGIN PRIVATE KEY-----
MIICdwIBADANBgkqhkiG9w0BAQEFAASCAmEwggJdAgEAAoGBALHU797VD7GH7AKj
Frimv/loQW3Z0Rf2IEnJi7/Zrvx6a2itkptZ7IrjflJFNsfEdKOp7Wxa0iFcFoVr
Z0eK6RPVYnKgBxfAHUa3GNPZVURJAa1GK6Mxr6im6zp258kmXtGyUCOo39k/FwFd
p7O3Sce+dQH+4HUrxZP0bsRLRXXvAgMBAAECgYABg5yfOxUtH8kkpJrW66SKzRZx
hv8+wvu3ZR3pfkL9J1WuyHuNExDuhc1XiftTbBrKIfJBj+xmGFCgxi9U7pvZab9q
er4XkvZA1PQVTFyRG6AO0fq1jrCpuz4ChMr55MFxKjAHoc/on3JmyzTaNLOHXGpf
W3urt0kQmNMaahFzMQJBANGte7H03kF2fO69NAWbtbVxiTEuvE9n+AcVocsCN3+H
b1wbVROmSZR4oUMM8RMTirpmaDexY4B4xkG1lADud/UCQQDZHmFLZn6+9csQbPFJ
PBgfalEGhdTO6PzTVioyF7hP+km7gfcHNpvj9IQRl1wURrnUd06aq061CPU0PL/P
ctvTAkEAjVhoYS9TwEdysrGC5yDvXlAaDriVouXQcl4nwiVNaj/PVwTp1iQrx9WF
yCBqRtTOmRc9vAVtsQY5h8Qy8GnRHQJBAKsb3y+uIhta2GMkiG/f9V7kyeBrHpDG
W2IumOiLew1EwlENFuLPbcIUFPVMJRwxtQg10nPgqBHScnRtn/jcm1MCQEsnCRQ1
AMt3HHYQyJ/woxF+xMCpRDR3jBadQ/SE8cfiqNSb+6fTTRMXt4Hzm2zNfEbFYBNo
bHg49P3V1fDMOyE=
-----END PRIVATE KEY-----
';

  private $password = 'associacao';

  public function setUp()
  {
    $filePath = dirname(dirname(dirname(__DIR__))) . DIRECTORY_SEPARATOR . "Fixtures" . DIRECTORY_SEPARATOR . "certificado_teste.pfx";
    $this->certificate = new PKCS12();
    $this->reader = ReaderFactory::create($this->certificate);
    $this->reader->setContent(file_get_contents($filePath));
    $this->reader->setPassword($this->password);
  }

  public function testRead()
  {
    $this->reader->read();

    $expectedCertificate = new PKCS12();
    $expectedCertificate->setPubKey($this->pubKey);
    $expectedCertificate->setPrivKey($this->privKey);

    $this->assertEquals($expectedCertificate->getPubKey(), $this->certificate->getPubKey());
    $this->assertEquals($expectedCertificate->getPrivKey(), $this->certificate->getPrivKey());
  }

  /**
   * @expectedException \Exception
   * @expectedExceptionMessage O certificado n�o pode ser lido, senha ou arquivo inv�lido
   */
  public function testReadExpectException()
  {
    $this->reader->setContent('invalid');
    $this->reader->read();
  }

  /**
   * @expectedException \Exception
   * @expectedExceptionMessage O certificado n�o pode ser lido, senha ou arquivo inv�lido
   */
  public function testReadInvaliPasswordExpectException()
  {
    $this->reader->setPassword('invalid');
    $this->reader->read();
  }  

}