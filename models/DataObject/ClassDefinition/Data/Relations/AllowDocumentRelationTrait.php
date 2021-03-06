<?php

namespace Pimcore\Model\DataObject\ClassDefinition\Data\Relations;

use Pimcore\Logger;
use Pimcore\Model\Document;

/**
 * @internal
 */
trait AllowDocumentRelationTrait
{
    /**
     * Checks if a document is an allowed relation
     *
     * @internal
     * @param Document $document
     *
     * @return bool
     */
    protected function allowDocumentRelation($document)
    {
        if (!$document instanceof Document || $document->getId() <= 0) {
            return false;
        }

        $allowedDocumentTypes = $this->getDocumentTypes();

        $allowed = true;
        if (!$this->getDocumentsAllowed()) {
            $allowed = false;
        } elseif ($this->getDocumentsAllowed() and is_array($allowedDocumentTypes) and count($allowedDocumentTypes) > 0) {
            //check for allowed asset types
            $allowedTypes = [];
            foreach ($allowedDocumentTypes as $t) {
                if ($t['documentTypes']) {
                    $allowedTypes[] = $t['documentTypes'];
                }
            }

            if (!in_array($document->getType(), $allowedTypes) && count($allowedTypes)) {
                $allowed = false;
            }
        } else {
            //don't check if no allowed document types set
        }

        Logger::debug('checked object relation to target document [' . $document->getId() . '] in field [' . $this->getName() . '], allowed:' . $allowed);

        return $allowed;
    }
}
