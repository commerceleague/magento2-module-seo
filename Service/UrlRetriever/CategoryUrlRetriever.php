<?php
declare(strict_types=1);
/**
 */

namespace CommerceLeague\Seo\Service\UrlRetriever;

use CommerceLeague\Seo\Service\UrlRetrieverInterface;
use Magento\Catalog\Api\CategoryRepositoryInterface;
use Magento\Catalog\Model\Category;
use Magento\CatalogUrlRewrite\Model\CategoryUrlPathGenerator;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Registry;
use Magento\Store\Api\Data\StoreInterface;
use Magento\Store\Model\Store;

/**
 * Class CategoryUrlRetriever
 */
class CategoryUrlRetriever implements UrlRetrieverInterface
{
    /**
     * @var Registry
     */
    private $registry;

    /**
     * @var CategoryRepositoryInterface
     */
    private $categoryRepository;

    /**
     * @var CategoryUrlPathGenerator
     */
    private $categoryUrlPathGenerator;

    /**
     * @param Registry $registry
     * @param CategoryRepositoryInterface $categoryRepository
     * @param CategoryUrlPathGenerator $categoryUrlPathGenerator
     */
    public function __construct(
        Registry $registry,
        CategoryRepositoryInterface $categoryRepository,
        CategoryUrlPathGenerator $categoryUrlPathGenerator
    ) {
        $this->registry = $registry;
        $this->categoryRepository = $categoryRepository;
        $this->categoryUrlPathGenerator = $categoryUrlPathGenerator;
    }

    /**
     * @param int|string $identifier
     * @param StoreInterface|Store $store
     * @return string|null
     * @throws NoSuchEntityException
     */
    public function getUrl($identifier, StoreInterface $store): ?string
    {
        /** @var Category $category */
        $category = $this->registry->registry('category');

        if (!$category) {
            $category = $this->categoryRepository->get($identifier, $store->getId());
        }

        $path = $this->categoryUrlPathGenerator->getUrlPathWithSuffix($category);
        return $store->getBaseUrl() . $path;
    }
}
