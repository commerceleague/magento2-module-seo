<?php
declare(strict_types=1);
/**
 */

namespace CommerceLeague\Seo\Service\UrlRetriever;

use CommerceLeague\Seo\Service\UrlRetrieverInterface;
use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Catalog\Model\Product;
use Magento\CatalogUrlRewrite\Model\ProductUrlPathGenerator;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Registry;
use Magento\Store\Api\Data\StoreInterface;
use Magento\Store\Model\Store;

/**
 * Class ProductUrlRetriever
 */
class ProductUrlRetriever implements UrlRetrieverInterface
{
    /**
     * @var Registry
     */
    private $registry;

    /**
     * @var ProductRepositoryInterface
     */
    private $productRepository;

    /**
     * @var ProductUrlPathGenerator
     */
    private $productUrlPathGenerator;

    /**
     * @param Registry $registry
     * @param ProductRepositoryInterface $productRepository
     * @param ProductUrlPathGenerator $productUrlPathGenerator
     */
    public function __construct(
        Registry $registry,
        ProductRepositoryInterface $productRepository,
        ProductUrlPathGenerator $productUrlPathGenerator
    ) {
        $this->registry = $registry;
        $this->productRepository = $productRepository;
        $this->productUrlPathGenerator = $productUrlPathGenerator;
    }

    /**
     * @param int|string $identifier
     * @param StoreInterface|Store $store
     * @return string|null
     * @throws NoSuchEntityException
     */
    public function getUrl($identifier, StoreInterface $store): ?string
    {
        /** @var Product $product */
        $product = $this->registry->registry('product');
        if (!$product) {
            $product = $this->productRepository->getById($identifier, false, $store->getId());
        }

        $path = $this->productUrlPathGenerator->getUrlPathWithSuffix($product, $store->getId());
        return $store->getBaseUrl() . $path;
    }
}
