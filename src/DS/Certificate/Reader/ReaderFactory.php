<?php

namespace DS\Certificate\Reader;

class ReaderFactory
{

  public static function create(\DS\Certificate\CertificateInterface $certificate = null)
  {

    $instance = null;

    if ( $certificate instanceof \DS\Certificate\PKCS12 ) {
      $instance = new \DS\Certificate\Reader\PKCS12Reader();
    }

    if ($instance == null) 
      throw new \Exception('Reader não identificado');

    $instance->setCertificate($certificate);

    return $instance;
  }  
}