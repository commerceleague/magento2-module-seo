<?php
/**
 */

namespace CommerceLeague\Seo\Block;

/**
 * Trait OpenGraphTrait
 */
trait OpenGraphTrait
{
    /**
     * @param string $description
     * @return string
     */
    public function trimDescription(string $description): string
    {
        if (strlen($description) >= OpenGraphInterface::MAX_DESCRIPTION_LENGTH) {
            $description = substr($description, 0, (OpenGraphInterface::MAX_DESCRIPTION_LENGTH - 4)) . ' ...';
        }

        return $description;
    }
}
