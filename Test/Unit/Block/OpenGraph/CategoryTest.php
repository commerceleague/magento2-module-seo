<?php
/**
 */

namespace CommerceLeague\Seo\Test\Unit\Block\OpenGraph;

use CommerceLeague\Seo\Block\OpenGraph\Category;
use Magento\Catalog\Helper\Category as CategoryHelper;
use Magento\Catalog\Model\Category as CategoryModel;
use Magento\Catalog\Model\Layer\Resolver as LayerResolver;
use Magento\Framework\Registry;
use Magento\Framework\View\Element\Template\Context;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class CategoryTest extends TestCase
{
    /**
     * @var MockObject|Context
     */
    protected $context;

    /**
     * @var MockObject|LayerResolver
     */
    protected $layerResolver;

    /**
     * @var MockObject|Registry
     */
    protected $registry;

    /**
     * @var MockObject|CategoryHelper
     */
    protected $categoryHelper;

    /**
     * @var MockObject|CategoryModel
     */
    protected $categoryModel;

    /**
     * @var Category
     */
    protected $category;

    protected function setUp()
    {
        $this->context = $this->createMock(Context::class);
        $this->layerResolver = $this->createMock(LayerResolver::class);
        $this->registry = $this->createMock(Registry::class);
        $this->categoryHelper = $this->createMock(CategoryHelper::class);
        $this->categoryModel = $this->createMock(CategoryModel::class);

        $this->registry->expects($this->any())
            ->method('registry')
            ->with('current_category')
            ->willReturn($this->categoryModel);

        $this->category = new Category(
            $this->context,
            $this->layerResolver,
            $this->registry,
            $this->categoryHelper
        );
    }

    public function testGetOgTitleFromMetaTitle()
    {
        $ogTitle = 'the title';

        $this->categoryModel->expects($this->exactly(2))
            ->method('getData')
            ->with('meta_title')
            ->willReturn($ogTitle);

        $this->assertEquals(
            $ogTitle,
            $this->category->getOgTitle()
        );
    }

    public function testGetOgTitleFromName()
    {
        $ogTitle = 'the title';

        $this->categoryModel->expects($this->once())
            ->method('getData')
            ->with('meta_title')
            ->willReturn(null);

        $this->categoryModel->expects($this->exactly(2))
            ->method('getName')
            ->willReturn($ogTitle);

        $this->assertEquals(
            $ogTitle,
            $this->category->getOgTitle()
        );
    }

    public function testGetOgDescriptionFromMetaDescription()
    {
        $ogDescription = 'the description';

        $this->categoryModel->expects($this->exactly(2))
            ->method('getData')
            ->with('meta_description')
            ->willReturn($ogDescription);

        $this->assertEquals(
            $ogDescription,
            $this->category->getOgDescription()
        );
    }

    public function testGetOgDescriptionFromShortDescription()
    {
        $ogDescription = 'the description';

        $this->categoryModel->expects($this->at(0))
            ->method('getData')
            ->with('meta_description')
            ->willReturn(null);

        $this->categoryModel->expects($this->at(1))
            ->method('getData')
            ->with('short_description')
            ->willReturn($ogDescription);

        $this->categoryModel->expects($this->at(2))
            ->method('getData')
            ->with('short_description')
            ->willReturn($ogDescription);

        $this->assertEquals(
            $ogDescription,
            $this->category->getOgDescription()
        );
    }

    public function testGetOgDescriptionFromDescription()
    {
        $ogDescription = 'the description';

        $this->categoryModel->expects($this->at(0))
            ->method('getData')
            ->with('meta_description')
            ->willReturn(null);

        $this->categoryModel->expects($this->at(1))
            ->method('getData')
            ->with('short_description')
            ->willReturn(null);

        $this->categoryModel->expects($this->at(2))
            ->method('getData')
            ->with('description')
            ->willReturn($ogDescription);

        $this->categoryModel->expects($this->at(3))
            ->method('getData')
            ->with('description')
            ->willReturn($ogDescription);

        $this->assertEquals(
            $ogDescription,
            $this->category->getOgDescription()
        );
    }

    public function testGetOgUrl()
    {
        $url = 'http://example.com/category.html';

        $this->categoryModel->expects($this->once())
            ->method('getUrl')
            ->willReturn($url);

        $this->assertEquals(
            $url,
            $this->category->getOgUrl()
        );
    }

    public function testGetOgImage()
    {
        $imageUrl = 'http://example.com/category.jpg';

        $this->categoryModel->expects($this->once())
            ->method('getImageUrl')
            ->willReturn($imageUrl);

        $this->assertEquals(
            $imageUrl,
            $this->category->getOgImage()
        );
    }
}
