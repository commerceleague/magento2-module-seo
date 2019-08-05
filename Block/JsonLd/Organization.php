<?php
declare(strict_types=1);
/**
 */

namespace CommerceLeague\Seo\Block\JsonLd;

use CommerceLeague\Seo\Block\JsonLdInterface;
use CommerceLeague\Seo\Helper\Config as ConfigHelper;
use Magento\Framework\View\Element\Template;
use Spatie\SchemaOrg\OrganizationFactory as OrganizationSchemaFactory;
use Spatie\SchemaOrg\Organization as OrganizationSchema;
use Spatie\SchemaOrg\ContactPointFactory as ContactPointSchemaFactory;
use Spatie\SchemaOrg\ContactPoint as ContactPointSchema;
use Magento\Theme\Block\Html\Header\Logo as LogoBlock;

/**
 * Class Organization
 */
class Organization extends Template implements JsonLdInterface
{
    /**
     * @var ConfigHelper
     */
    private $configHelper;

    /**
     * @var LogoBlock
     */
    private $logoBlock;

    /**
     * @var OrganizationSchemaFactory
     */
    private $organizationSchemaFactory;

    /**
     * @var ContactPointSchemaFactory
     */
    private $contactPointSchemaFactory;

    /**
     * @param Template\Context $context
     * @param OrganizationSchemaFactory $organizationSchemaFactory
     * @param ContactPointSchemaFactory $contactPointSchemaFactory
     * @param ConfigHelper $configHelper
     * @param LogoBlock $logoBlock
     * @param array $data
     */
    public function __construct(
        Template\Context $context,
        ConfigHelper $configHelper,
        LogoBlock $logoBlock,
        OrganizationSchemaFactory $organizationSchemaFactory,
        ContactPointSchemaFactory $contactPointSchemaFactory,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->configHelper = $configHelper;
        $this->logoBlock = $logoBlock;
        $this->organizationSchemaFactory = $organizationSchemaFactory;
        $this->contactPointSchemaFactory = $contactPointSchemaFactory;
    }

    /**
     * @inheritDoc
     */
    public function getScript(): string
    {
        /** @var OrganizationSchema $organizationSchema */
        $organizationSchema = $this->organizationSchemaFactory->create();

        $organizationSchema->url($this->getBaseUrl())
            ->name($this->configHelper->getBusinessName())
            ->logo($this->logoBlock->getLogoSrc())
            ->sameAs([
                $this->configHelper->getSocialProfileFacebook(),
                $this->configHelper->getSocialProfileInstagram()
            ]);

        /** @var ContactPointSchema $contactPointSchema */
        $contactPointSchema = $this->contactPointSchemaFactory->create();

        $contactPointSchema
            ->contactType('customer service')
            ->telephone($this->configHelper->getBusinessCustomerServicePhone());

        $organizationSchema->contactPoint($contactPointSchema);

        return $organizationSchema->toScript();
    }

    /**
     * @return string
     */
    public function toHtml()
    {
        return $this->getScript();
    }
}
