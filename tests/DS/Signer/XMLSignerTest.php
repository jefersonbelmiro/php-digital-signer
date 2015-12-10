<?php

namespace DS\Signer;

use \DS\Certificate\PKCS12;
use \DS\Signer\XMLSigner;

class XMLSignerTest extends \PHPUnit_Framework_TestCase
{

  private $certificate;

  private $nfeDocument ;

  private $nfeSignedDocument;

  public function setUp()
  {
    $rootTestPath = dirname(dirname(__DIR__)) . DIRECTORY_SEPARATOR;
    $pubKeyPath = $rootTestPath . "Fixtures" . DIRECTORY_SEPARATOR . "certificado_teste_pub.pem";
    $privKeyPath =  $rootTestPath . "Fixtures" . DIRECTORY_SEPARATOR . "certificado_teste_priv.pem";
    $xmlPath =  $rootTestPath . "Fixtures" . DIRECTORY_SEPARATOR . "nfe_teste.xml";
    $xmlSignedPath =  $rootTestPath . "Fixtures" . DIRECTORY_SEPARATOR . "nfe_teste_assinado.xml";

    $this->nfeDocument = new \DOMDocument();
    $this->nfeDocument->preserveWhiteSpace = false;
    $this->nfeDocument->formatOutput = true;
    $this->nfeDocument->load($xmlPath);

    $this->nfeSignedDocument = new \DOMDocument();
    $this->nfeSignedDocument->preserveWhiteSpace = false;
    $this->nfeSignedDocument->formatOutput = true;
    $this->nfeSignedDocument->load($xmlSignedPath);

    $this->certificate = new PKCS12();
    $this->certificate->setPubKey(file_get_contents($pubKeyPath));
    $this->certificate->setPrivKey(file_get_contents($privKeyPath));

    $this->signer = new XMLSigner();    
    $this->signer->setCertificate($this->certificate);
  }

  public function testSign()
  {
    $element = $this->nfeDocument->getElementsByTagName('infNFe')->item(0);
    $this->signer->setElement($element);
    $this->signer->sign();    
    $this->assertXmlStringEqualsXmlString($this->nfeSignedDocument->saveXML(), $this->nfeDocument->saveXML());
  }

  /**
   * @expectedException \Exception
   * @expectedExceptionMessage Erro ao assinar o XML
   */
  public function testSignExpectException()
  {
    $element = $this->nfeDocument->getElementsByTagName('infNFe')->item(0);
    $this->signer->setElement($element);
    $this->certificate->setPrivKey('invalid');
    $this->signer->sign();
  }

}