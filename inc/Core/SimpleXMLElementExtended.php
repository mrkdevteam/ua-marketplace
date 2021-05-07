<?php
/**
 * @package 	MrkvUAMmarketplaces
 */

namespace Inc\Core;

class SimpleXMLElementExtended extends \SimpleXMLElement {

    // Add a child SimpleXMLElement with $value inside CDATA
    public function addChildWithCDATA($name, $value = NULL)
    {
        $new_child = $this->addChild( $name );

        if ($new_child !== NULL) {
            $node = \dom_import_simplexml($new_child);
            $no   = $node->ownerDocument;
            $node->appendChild($no->createCDATASection($value));
        }

        return $new_child;
    }

}
