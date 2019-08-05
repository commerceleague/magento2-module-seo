<?php
/**
 */

namespace CommerceLeague\Seo\Service;

use Magento\Store\Api\Data\StoreInterface;

/**
 * Interface UrlRetrieverInterface
 */
interface UrlRetrieverInterface
{
    /**
     * @param string|int $identifier
     * @param StoreInterface $store
     * @return string|null
     */
    public function getUrl($identifier, StoreInterface $store): ?string;
}
