<?php

namespace DS\Certificate;

class PKCS12Test extends \PHPUnit_Framework_TestCase
{

  public function setUp() {
    $this->pkcs12 = new PKCS12();
  }

  public function testGetter() {
    $this->assertNull($this->pkcs12->getPubKey());
  }

}