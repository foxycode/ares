<?php

namespace Edge\Ares\Parser;

use Edge\Ares\Container\Address;
use Edge\Ares\Exception\AresException;
use Edge\Ares\Exception\InvalidArgumentException;
use SimpleXMLElement;

/**
 *
 * @author Marek Makovec marek.makovec@edgedesign.cz
 */
class AddressParser implements ParserInterface
{
    /**
     * Parse address from given XML and return Address object.
     *
     * @param string $xmlString
     * @throws \Edge\Ares\Exception\InvalidArgumentException
     *
     * @return Address
     */
    public function parseAddress($xmlString)
    {
        $previousValue = libxml_use_internal_errors(true);
        $xml = simplexml_load_string($xmlString);
        libxml_use_internal_errors($previousValue);

        if ($xml === false) {
            $message = $this->getXmlErrorMessage();
            throw new InvalidArgumentException('Failed to parse supplied XML string: ' . $message);
        }

        return $this->parseSubjectAddress($xml);
    }

    /**
     * Get error message from last supplied XML string.
     *
     * @return string
     */
    private function getXmlErrorMessage()
    {
        $errors = libxml_get_errors();
        if (count($errors)) {
            /** @var \LibXMLError $error */
            $error = array_shift($errors);
            return $error->message;
        }

        return 'Unknown error withing SimpleXML parser occured.';
    }

    /**
     * Many thanks to Radek Hulan http://myego.cz/item/automaticke-nacteni-sidla-firmy-a-dic-podle-zadaneho-ic-ares-xml-pomoci-php-a-jquery/category/php/group/webdesign
     * as parsing XML would be my death othwerwise...
     *
     * @param SimpleXMLElement $xml
     *
     * @return array
     */
    private function parseSubjectAddress(SimpleXMLElement $xml)
    {
        $this->assertSubjectFound($xml);

        $ns = $xml->getDocNamespaces();
        $data = $xml->children($ns['are']);
        $el = $data->children($ns['D'])->VBAS;

        return new Address::from(array(
            'firma'           => (string) $el->OF,
            'dic'             => (string) $el->DIC,
            'ico'             => (string) $el->ICO,
            'mesto'           => (string) $el->AA->N,
            'mestskaCast'     => (string) $el->AA->NMC,
            'castObce'        => (string) $el->AA->NCO,
            'ulice'           => (string) $el->AA->NU,
            'cisloOrientacni' => (string) $el->AA->CO,
            'cisloPopisne'    => (string) $el->AA->CD,
            'psc'             => (string) $el->AA->PSC
        ));
    }

    private function assertSubjectFound(SimpleXMLElement $xml)
    {
        $ns = $xml->getDocNamespaces();
        $data = $xml->children($ns['are']);
        $el = $data->children($ns['D'])->E;

        if (count($el)) {
            $message = trim((string) $el->ET);
            $code = trim((string) $el->EK);

            throw new AresException('Error occured in ARES.cz system.', $message, $code);
        }
    }

}
