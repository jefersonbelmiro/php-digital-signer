<?php

namespace DS\Signer;

use \DOMElement;

class XMLSigner implements SignerInterface 
{

  private $certificate;

  private $element;

  public function setCertificate(\DS\Certificate\CertificateInterface $certificate)
  {
    $this->certificate = $certificate;
  }

  public function setElement(\DOMElement $element)
  {
    $this->element = $element;
  }

  public function sign() 
  {
    return $this->createSignature();    
  }
 
  /**
   * @example 
   *   <Signature>
   *     <SignedInfo>                    // The SignedInfo element contains or references the signed data and specifies what algorithms are used.
   *       <CanonicalizationMethod />    // The SignatureMethod and CanonicalizationMethod elements are used by the SignatureValue element 
   *       <SignatureMethod />           // are included in SignedInfo to protect them from tampering.
   *       <Reference>                   // One or more Reference elements specify the resource being signed by URI reference; and any transforms to be applied to the resource prior to signing.
   *          <Transforms>
   *          <DigestMethod>             // DigestMethod specifies the hash algorithm before applying the hash.
   *          <DigestValue>              // DigestValue contains the result of applying the hash algorithm to the transformed resource(s).
   *       </Reference>
   *       <Reference /> etc.
   *     </SignedInfo>
   *     <SignatureValue />              // The SignatureValue element contains the Base64 encoded signature result
   *                                     // the signature generated with the parameters specified in the SignatureMethod element - of the SignedInfo element 
   *                                     // after applying the algorithm specified by the CanonicalizationMethod.
   *                                     
   *     <KeyInfo />                     // KeyInfo element optionally allows the signer to provide recipients with the key that validates the signature, usually in the form of one or more X.509 digital certificates. 
   *     <Object />                      // The Object element (optional) contains the signed data if this is an enveloping signature.
   *   </Signature>
   */
  private function createSignature()
  {
    $nsDSIG = 'http://www.w3.org/2000/09/xmldsig#';
    $nsCannonMethod = 'http://www.w3.org/TR/2001/REC-xml-c14n-20010315';
    $nsSignatureMethod = 'http://www.w3.org/2000/09/xmldsig#rsa-sha1';
    $nsTransformMethod1 ='http://www.w3.org/2000/09/xmldsig#enveloped-signature';
    $nsTransformMethod2 = 'http://www.w3.org/TR/2001/REC-xml-c14n-20010315';
    $nsDigestMethod = 'http://www.w3.org/2000/09/xmldsig#sha1';

    $ownerDocument = $this->element->ownerDocument;
    $xpathReference = $this->element->getNodePath();    

    //extrai os dados da tag para uma string na forma canonica
    $nodeData = $this->element->C14N(true, false, null, null);
    //calcular o hash dos dados
    $hashValue = hash('sha1', $nodeData, true);
    //converter o hash para base64
    $digValue = base64_encode($hashValue);

    $signatureNode = $ownerDocument->createElementNS($nsDSIG, 'Signature');
    $this->element->parentNode->appendChild($signatureNode);

    $signedInfoNode = $ownerDocument->createElement('SignedInfo');
    $signatureNode->appendChild($signedInfoNode);

    $canonicalNode = $ownerDocument->createElement('CanonicalizationMethod');
    $signedInfoNode->appendChild($canonicalNode);
    $canonicalNode->setAttribute('Algorithm', $nsCannonMethod);    

    $signatureMethodNode = $ownerDocument->createElement('SignatureMethod');    
    $signedInfoNode->appendChild($signatureMethodNode);
    $signatureMethodNode->setAttribute('Algorithm', $nsSignatureMethod);    

    $referenceNode = $ownerDocument->createElement('Reference');
    $referenceNode->setAttribute('URI', $xpathReference);
    $signedInfoNode->appendChild($referenceNode);

    $transformsNode = $ownerDocument->createElement('Transforms');
    $referenceNode->appendChild($transformsNode);

    $digestMethodNode = $ownerDocument->createElement('DigestMethod');
    $referenceNode->appendChild($digestMethodNode);
    $digestMethodNode->setAttribute('Algorithm', $nsDigestMethod);    

    $digestValueNode =  $ownerDocument->createElement('DigestValue', $digValue);
    $referenceNode->appendChild($digestValueNode);
    
    $transfNode1 = $ownerDocument->createElement('Transform');
    $transfNode1->setAttribute('Algorithm', $nsTransformMethod1);
    $transformsNode->appendChild($transfNode1);

    $transfNode2 = $ownerDocument->createElement('Transform');
    $transfNode2->setAttribute('Algorithm', $nsTransformMethod2);
    $transformsNode->appendChild($transfNode2);

    $canonicalizedSignedInfo = $signedInfoNode->C14N(true, false, null, null);

    $signature = '';

    if (!@openssl_sign($canonicalizedSignedInfo, $signature, $this->certificate->getFullPrivKey())) {
      throw new \Exception("Erro ao assinar o XML");
    }

    $signatureValue = base64_encode($signature);

    $signatureValueNode = $ownerDocument->createElement('SignatureValue', $signatureValue);
    $signatureNode->appendChild($signatureValueNode);

    $keyInfoNode = $ownerDocument->createElement('KeyInfo');
    $signatureNode->appendChild($keyInfoNode);

    $x509DataNode = $ownerDocument->createElement('X509Data');
    $keyInfoNode->appendChild($x509DataNode);

    $x509CertificateNode = $ownerDocument->createElement('X509Certificate', $this->certificate->getCleanPubKey());
    $x509DataNode->appendChild($x509CertificateNode);
    
    return $signatureNode;
  }

}