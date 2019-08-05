<?php
declare(strict_types=1);
/**
 */

namespace CommerceLeague\Seo\Service\UrlRetriever;

use CommerceLeague\Seo\Service\UrlRetrieverInterface;
use Magento\Cms\Api\PageRepositoryInterface;
use Magento\Cms\Model\ResourceModel\Page as PageResource;
use Magento\Framework\Exception\LocalizedException;
use Magento\Store\Api\Data\StoreInterface;
use Magento\CmsUrlRewrite\Model\CmsPageUrlPathGenerator;
use Magento\Store\Model\Store;

/**
 * Class PageUrlRetriever
 */
class PageUrlRetriever implements UrlRetrieverInterface
{
    /**
     * @var PageRepositoryInterface
     */
    private $pageRepository;

    /**
     * @var PageResource
     */
    private $pageResource;

    /**
     * @var CmsPageUrlPathGenerator
     */
    private $cmsPageUrlPathGenerator;

    /**
     * @param PageRepositoryInterface $pageRepository
     * @param PageResource $pageResource
     * @param CmsPageUrlPathGenerator $cmsPageUrlPathGenerator
     */
    public function __construct(
        PageRepositoryInterface $pageRepository,
        PageResource $pageResource,
        CmsPageUrlPathGenerator $cmsPageUrlPathGenerator
    ) {
        $this->pageRepository = $pageRepository;
        $this->pageResource = $pageResource;
        $this->cmsPageUrlPathGenerator = $cmsPageUrlPathGenerator;
    }

    /**
     * @param int|string $identifier
     * @param StoreInterface|Store $store
     * @return string|null
     */
    public function getUrl($identifier, StoreInterface $store): ?string
    {
        try {
            $page = $this->pageRepository->getById($identifier);
            $pageId = $this->pageResource->checkIdentifier($page->getIdentifier(), $store->getId());
            $storePage = $this->pageRepository->getById($pageId);
            $path = $this->cmsPageUrlPathGenerator->getUrlPath($storePage);
            return $store->getBaseUrl() . $path;
        } catch (LocalizedException $e) {
            return null;
        }
    }
}
