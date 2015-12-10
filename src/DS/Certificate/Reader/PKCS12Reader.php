<?php

namespace DS\Certificate\Reader;

class PKCS12Reader implements ReaderInterface
{

  /**
   * content of pfx file
   * @var string
   */
  private $content;

  /**   
   * @var string
   */
  private $password;

  /**
   * @var \DS\Certificate\CertificateInterface
   */
  private $certificate;


  public function setContent($content) 
  {
    $this->content = $content;
  }

  public function setCertificate(\DS\Certificate\CertificateInterface $certificate)
  {
    $this->certificate = $certificate;
  }

  public function setPassword($password)
  {
    $this->password = $password;
  }

  public function read()
  {

    $data = array();

    if (!openssl_pkcs12_read($this->content, $data, $this->password)) {
      throw new \Exception('O certificado não pode ser lido, senha ou arquivo inválido');
    }

    $this->certificate->setPubKey($data['cert']);
    $this->certificate->setPrivKey($data['pkey']);
  }

}