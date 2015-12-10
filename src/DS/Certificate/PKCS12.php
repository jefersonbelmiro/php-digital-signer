<?php

namespace DS\Certificate;

class PKCS12 implements CertificateInterface {

  private $pubKey;

  private $privKey;

  const PUBKEY_PREFIX = "-----BEGIN CERTIFICATE-----\n";
  const PUBKEY_SUFFIX = "-----END CERTIFICATE-----\n";
  const PRIVKEY_PREFIX = "-----BEGIN PRIVATE KEY-----\n";
  const PRIVKEY_SUFFIX = "-----END PRIVATE KEY-----\n";

  public function setPubKey($pubKey)
  {
    $this->pubKey = $pubKey;
    $this->pubKey = str_replace(static::PUBKEY_PREFIX, '', $this->pubKey);
    $this->pubKey = str_replace(static::PUBKEY_SUFFIX, '', $this->pubKey);
    $this->pubKey = trim($this->pubKey, "\n");
  }

  public function getPubKey()
  {
    return $this->pubKey;
  }

  public function setPrivKey($privKey) 
  {
    $this->privKey = $privKey;
    $this->privKey = str_replace(static::PRIVKEY_PREFIX, '', $this->privKey);
    $this->privKey = str_replace(static::PRIVKEY_SUFFIX, '', $this->privKey);
    $this->privKey = trim($this->privKey, "\n");
  }

  public function getPrivKey() 
  {
    return $this->privKey;
  }

  public function getFullPrivKey()
  {
    return static::PRIVKEY_PREFIX . $this->privKey . "\n" . static::PRIVKEY_SUFFIX;
  }

  public function getCleanPubKey()
  {
    return str_replace("\n", '', $this->pubKey);
  }

}