<?php
/**
 */

namespace CommerceLeague\Seo\Test\Unit\Block\JsonLd;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\UrlInterface;
use Magento\Framework\View\Element\Template\Context;
use Magento\Store\Model\ScopeInterface;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use CommerceLeague\Seo\Block\JsonLd\Website;
use Spatie\SchemaOrg\WebSiteFactory as WebsiteSchemaFactory;
use Spatie\SchemaOrg\WebSite as WebsiteSchema;

class WebsiteTest extends TestCase
{
    /**
     * @var MockObject|Context
     */
    protected $context;

    /**
     * @var MockObject|UrlInterface
     */
    protected $urlBuilder;

    /**
     * @var MockObject|ScopeConfigInterface
     */
    protected $scopeConfig;

    /**
     * @var MockObject|WebsiteSchemaFactory
     */
    protected $websiteSchemaFactory;

    /**
     * @var MockObject|WebsiteSchema
     */
    protected $websiteSchema;

    /**
     * @var Website
     */
    protected $website;

    protected function setUp()
    {
        $this->context = $this->createMock(Context::class);
        $this->urlBuilder = $this->createMock(UrlInterface::class);
        $this->scopeConfig = $this->createMock(ScopeConfigInterface::class);

        $this->context->expects($this->any())
            ->method('getUrlBuilder')
            ->willReturn($this->urlBuilder);

        $this->context->expects($this->any())
            ->method('getScopeConfig')
            ->willReturn($this->scopeConfig);

        $this->websiteSchemaFactory = $this->getMockBuilder(WebsiteSchemaFactory::class)
            ->disableOriginalConstructor()
            ->setMethods(['create'])
            ->getMock();

        $this->websiteSchema = $this->getMockBuilder(WebsiteSchema::class)
            ->disableOriginalConstructor()
            ->setMethods(['url', 'name', 'toScript'])
            ->getMock();

        $this->website = new Website(
            $this->context,
            $this->websiteSchemaFactory
        );
    }

    public function testToHtml()
    {
        $baseUrl = 'http://example.com';
        $storeName = 'a name';
        $script = '<script type="application/ld+json">{}</script>';

        $this->websiteSchemaFactory->expects($this->once())
            ->method('create')
            ->willReturn($this->websiteSchema);

        $this->urlBuilder->expects($this->once())
            ->method('getBaseUrl')
            ->willReturn($baseUrl);

        $this->scopeConfig->expects($this->once())
            ->method('getValue')
            ->with(
                'general/store_information/name',
                ScopeInterface::SCOPE_STORE
            )->willReturn($storeName);

        $this->websiteSchema->expects($this->once())
            ->method('url')
            ->with($baseUrl)
            ->willReturnSelf();

        $this->websiteSchema->expects($this->once())
            ->method('name')
            ->with($storeName)
            ->willReturnSelf();

        $this->websiteSchema->expects($this->once())
            ->method('toScript')
            ->willReturn($script);

        $this->assertEquals(
            $script,
            $this->website->toHtml()
        );
    }
}
