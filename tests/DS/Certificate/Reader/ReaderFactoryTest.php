<?php

namespace DS\Certificate\Reader;

class ReaderFactoryTest extends \PHPUnit_Framework_TestCase
{

  public function testPKCSInstance() 
  {

    $instance = ReaderFactory::create(new \DS\Certificate\PKCS12());
    $this->assertInstanceOf('\DS\Certificate\Reader\PKCS12Reader', $instance, 'message');
  }

  /**
   * @expectedException \Exception
   * @expectedExceptionMessage Reader não identificado
   */
  public function testExpectException()
  {
    $instance = ReaderFactory::create(null);
  }

}
