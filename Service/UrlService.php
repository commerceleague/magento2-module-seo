<?php
declare(strict_types=1);
/**
 */

namespace CommerceLeague\Seo\Service;

use CommerceLeague\Seo\Service\UrlRetriever\CategoryUrlRetriever;
use CommerceLeague\Seo\Service\UrlRetriever\PageUrlRetriever;
use CommerceLeague\Seo\Service\UrlRetriever\ProductUrlRetriever;
use Magento\Framework\App\Request\Http as HttpRequest;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Store\Api\Data\StoreInterface;
use Magento\Store\Model\Store;

/**
 * Class UrlService
 */
class UrlService
{
    /**
     * @var HttpRequest
     */
    private $request;

    /**
     * @var CategoryUrlRetriever
     */
    private $categoryUrlRetriever;

    /**
     * @var PageUrlRetriever
     */
    private $pageUrlRetriever;

    /**
     * @var ProductUrlRetriever
     */
    private $productUrlRetriever;

    /**
     * @param HttpRequest $request
     * @param CategoryUrlRetriever $categoryUrlRetriever
     * @param PageUrlRetriever $pageUrlRetriever
     * @param ProductUrlRetriever $productUrlRetriever
     */
    public function __construct(
        HttpRequest $request,
        CategoryUrlRetriever $categoryUrlRetriever,
        PageUrlRetriever $pageUrlRetriever,
        ProductUrlRetriever $productUrlRetriever
    ) {
        $this->request = $request;
        $this->categoryUrlRetriever = $categoryUrlRetriever;
        $this->pageUrlRetriever = $pageUrlRetriever;
        $this->productUrlRetriever = $productUrlRetriever;
    }

    /**
     * @param Store|StoreInterface $store
     * @return string|null
     * @throws NoSuchEntityException
     */
    public function getUrlByStore(StoreInterface $store): ?string
    {
        switch ($this->request->getFullActionName()) {
            case 'catalog_category_view':
                return $this->categoryUrlRetriever->getUrl($this->request->getParam('id'), $store);
            case 'catalog_product_view':
                return $this->productUrlRetriever->getUrl($this->request->getParam('id'), $store);
            case 'cms_page_view':
                return $this->pageUrlRetriever->getUrl($this->request->getParam('page_id'), $store);
            case 'cms_index_index':
                return $store->getBaseUrl();
        }

        return null;
    }
}
