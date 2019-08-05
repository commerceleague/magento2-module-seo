<?php
/**
 */

namespace CommerceLeague\Seo\Test\Unit\Block\JsonLd;

use CommerceLeague\Seo\Helper\Config as ConfigHelper;
use Magento\Framework\UrlInterface;
use Magento\Framework\View\Element\Template\Context;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use CommerceLeague\Seo\Block\JsonLd\Organization;
use Spatie\SchemaOrg\OrganizationFactory as OrganizationSchemaFactory;
use Spatie\SchemaOrg\Organization as OrganizationSchema;
use Spatie\SchemaOrg\ContactPointFactory as ContactPointSchemaFactory;
use Spatie\SchemaOrg\ContactPoint as ContactPointSchema;
use Magento\Theme\Block\Html\Header\Logo as LogoBlock;

class OrganizationTest extends TestCase
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
     * @var MockObject|LogoBlock
     */
    protected $logoBlock;

    /**
     * @var MockObject|OrganizationSchemaFactory
     */
    protected $organizationSchemaFactory;

    /**
     * @var MockObject|OrganizationSchema
     */
    protected $organizationSchema;

    /**
     * @var MockObject|ContactPointSchemaFactory
     */
    protected $contactPointSchemaFactory;

    /**
     * @var MockObject|ContactPointSchema
     */
    protected $contactPointSchema;

    /**
     * @var Organization
     */
    protected $organization;

    protected function setUp()
    {
        $this->context = $this->createMock(Context::class);
        $this->urlBuilder = $this->createMock(UrlInterface::class);

        $this->context->expects($this->any())
            ->method('getUrlBuilder')
            ->willReturn($this->urlBuilder);

        $this->configHelper = $this->createMock(ConfigHelper::class);
        $this->logoBlock = $this->createMock(LogoBlock::class);

        $this->organizationSchemaFactory = $this->getMockBuilder(OrganizationSchemaFactory::class)
            ->disableOriginalConstructor()
            ->setMethods(['create'])
            ->getMock();

        $this->organizationSchema = $this->getMockBuilder(OrganizationSchema::class)
            ->disableOriginalConstructor()
            ->setMethods(['url', 'name', 'logo', 'sameAs', 'contactPoint', 'toScript'])
            ->getMock();

        $this->organizationSchemaFactory->expects($this->any())
            ->method('create')
            ->willReturn($this->organizationSchema);

        $this->contactPointSchemaFactory = $this->getMockBuilder(ContactPointSchemaFactory::class)
            ->disableOriginalConstructor()
            ->setMethods(['create'])
            ->getMock();

        $this->contactPointSchema = $this->getMockBuilder(ContactPointSchema::class)
            ->setMethods(['contactType', 'telephone'])
            ->getMock();

        $this->contactPointSchemaFactory->expects($this->any())
            ->method('create')
            ->willReturn($this->contactPointSchema);

        $this->organization = new Organization(
            $this->context,
            $this->configHelper,
            $this->logoBlock,
            $this->organizationSchemaFactory,
            $this->contactPointSchemaFactory
        );
    }

    public function testToHtml()
    {
        $baseUrl = 'http://example.com';
        $businessName = 'the business name';
        $logo = 'http://example.com/logo.svg';
        $socialProfileFacebook = 'https://facebook.com/example';
        $socialProfileInstagram = 'https://instagram.com/example';
        $contactType = 'customer service';
        $telephone = '1234567';
        $script = '<script type="application/ld+json">{}</script>';

        $this->urlBuilder->expects($this->once())
            ->method('getBaseUrl')
            ->willReturn($baseUrl);

        $this->organizationSchema->expects($this->once())
            ->method('url')
            ->with($baseUrl)
            ->willReturnSelf();

        $this->configHelper->expects($this->once())
            ->method('getBusinessName')
            ->willReturn($businessName);

        $this->organizationSchema->expects($this->once())
            ->method('name')
            ->with($businessName)
            ->willReturnSelf();

        $this->logoBlock->expects($this->once())
            ->method('getLogoSrc')
            ->willReturn($logo);

        $this->organizationSchema->expects($this->once())
            ->method('logo')
            ->with($logo)
            ->willReturnSelf();

        $this->configHelper->expects($this->once())
            ->method('getSocialProfileFacebook')
            ->willReturn($socialProfileFacebook);

        $this->configHelper->expects($this->once())
            ->method('getSocialProfileInstagram')
            ->willReturn($socialProfileInstagram);

        $this->organizationSchema->expects($this->once())
            ->method('sameAs')
            ->with([$socialProfileFacebook, $socialProfileInstagram])
            ->willReturnSelf();

        $this->contactPointSchema->expects($this->once())
            ->method('contactType')
            ->with($contactType)
            ->willReturnSelf();

        $this->configHelper->expects($this->once())
            ->method('getBusinessCustomerServicePhone')
            ->willReturn($telephone);

        $this->contactPointSchema->expects($this->once())
            ->method('telephone')
            ->with($telephone)
            ->willReturnSelf();

        $this->organizationSchema->expects($this->once())
            ->method('contactPoint')
            ->with($this->contactPointSchema)
            ->willReturnSelf();

        $this->organizationSchema->expects($this->once())
            ->method('toScript')
            ->willReturn($script);

        $this->assertEquals(
            $script,
            $this->organization->toHtml()
        );
    }
}