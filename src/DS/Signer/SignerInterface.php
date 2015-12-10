<?php

namespace DS\Signer;

interface SignerInterface 
{

  public function setCertificate(\DS\Certificate\CertificateInterface $certificate);
  
  public function sign();

}