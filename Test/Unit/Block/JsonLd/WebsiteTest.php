<?php
/**
 */

namespace CommerceLeague\Seo\Test\Unit\Block\JsonLd;

use CommerceLeague\Seo\Helper\Config as ConfigHelper;
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
     * @var MockObject|ConfigHelper
     */
    protected $configHelper;

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
        $this->configHelper = $this->createMock(ConfigHelper::class);

        $this->context->expects($this->any())
            ->method('getUrlBuilder')
            ->willReturn($this->urlBuilder);

        $this->websiteSchemaFactory = $this->getMockBuilder(WebsiteSchemaFactory::class)
            ->disableOriginalConstructor()
            ->setMethods(['create'])
            ->getMock();

        $this->websiteSchema = $this->getMockBuilder(WebsiteSchema::class)
            ->disableOriginalConstructor()
            ->setMethods(['url', 'name', 'toScript'])
            ->getMock();

        $this->websiteSchemaFactory->expects($this->any())
            ->method('create')
            ->willReturn($this->websiteSchema);

        $this->website = new Website(
            $this->context,
            $this->websiteSchemaFactory,
            $this->configHelper
        );
    }

    public function testToHtml()
    {
        $baseUrl = 'http://example.com';
        $websiteName = 'the website name';
        $script = '<script type="application/ld+json">{}</script>';

        $this->urlBuilder->expects($this->once())
            ->method('getBaseUrl')
            ->willReturn($baseUrl);

        $this->configHelper->expects($this->once())
            ->method('getWebsiteName')
            ->willReturn($websiteName);

        $this->websiteSchema->expects($this->once())
            ->method('url')
            ->with($baseUrl)
            ->willReturnSelf();

        $this->websiteSchema->expects($this->once())
            ->method('name')
            ->with($websiteName)
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
