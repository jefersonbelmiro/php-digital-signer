<?php

namespace DS\Certificate\Reader;

interface ReaderInterface
{

  public function setCertificate(\DS\Certificate\CertificateInterface $certificate);

  public function read();

}