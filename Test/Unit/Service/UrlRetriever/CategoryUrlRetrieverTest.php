<?php
/**
 */

namespace CommerceLeague\Seo\Test\Unit\Service\UrlRetriever;

use CommerceLeague\Seo\Service\UrlRetriever\CategoryUrlRetriever;
use Magento\Catalog\Api\CategoryRepositoryInterface;
use Magento\Catalog\Model\Category;
use Magento\CatalogUrlRewrite\Model\CategoryUrlPathGenerator;
use Magento\Framework\Registry;
use Magento\Store\Model\Store;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class CategoryUrlRetrieverTest extends TestCase
{
    /**
     * @var MockObject|Registry
     */
    protected $registry;

    /**
     * @var MockObject|CategoryRepositoryInterface
     */
    protected $categoryRepository;

    /**
     * @var MockObject|CategoryUrlPathGenerator
     */
    protected $categoryUrlPathGenerator;

    /**
     * @var MockObject|Store
     */
    protected $store;

    /**
     * @var MockObject|Category
     */
    protected $category;

    /**
     * @var CategoryUrlRetriever
     */
    protected $categoryUrlRetriever;

    protected function setUp()
    {
        $this->registry = $this->createMock(Registry::class);
        $this->categoryRepository = $this->createMock(CategoryRepositoryInterface::class);
        $this->categoryUrlPathGenerator = $this->createMock(CategoryUrlPathGenerator::class);
        $this->store = $this->createMock(Store::class);
        $this->category = $this->createMock(Category::class);

        $this->categoryUrlRetriever = new CategoryUrlRetriever(
            $this->registry,
            $this->categoryRepository,
            $this->categoryUrlPathGenerator
        );
    }

    public function testGetUrlGetCategoryFromRegistry()
    {
        $identifier = 123;
        $path = 'category.html';
        $baseUrl = 'http://example.com/';

        $this->registry->expects($this->once())
            ->method('registry')
            ->with('category')
            ->willReturn($this->category);

        $this->categoryRepository->expects($this->never())
            ->method('get');

        $this->categoryUrlPathGenerator->expects($this->once())
            ->method('getUrlPathWithSuffix')
            ->with($this->category)
            ->willReturn($path);

        $this->store->expects($this->once())
            ->method('getBaseUrl')
            ->willReturn($baseUrl);

        $this->assertEquals(
            $baseUrl . $path,
            $this->categoryUrlRetriever->getUrl($identifier, $this->store)
        );
    }

    public function testGetUrlGetCategoryFromRepository()
    {
        $identifier = 123;
        $storeId = 45;
        $path = 'category.html';
        $baseUrl = 'http://example.com/';

        $this->registry->expects($this->once())
            ->method('registry')
            ->with('category')
            ->willReturn(null);

        $this->store->expects($this->once())
            ->method('getId')
            ->willReturn($storeId);

        $this->categoryRepository->expects($this->once())
            ->method('get')
            ->with($identifier, $storeId)
            ->willReturn($this->category);

        $this->categoryUrlPathGenerator->expects($this->once())
            ->method('getUrlPathWithSuffix')
            ->with($this->category)
            ->willReturn($path);

        $this->store->expects($this->once())
            ->method('getBaseUrl')
            ->willReturn($baseUrl);

        $this->assertEquals(
            $baseUrl . $path,
            $this->categoryUrlRetriever->getUrl($identifier, $this->store)
        );
    }
}
