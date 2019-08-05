<?php
declare(strict_types=1);
/**
 */

namespace CommerceLeague\Seo\Block\JsonLd;

use CommerceLeague\Seo\Block\JsonLdInterface;
use CommerceLeague\Seo\Helper\Config as ConfigHelper;
use Magento\Framework\View\Element\Template;
use Spatie\SchemaOrg\WebSiteFactory as WebsiteSchemaFactory;
use Spatie\SchemaOrg\WebSite as WebsiteSchema;

/**
 * Class Website
 */
class Website extends Template implements JsonLdInterface
{
    /**
     * @var WebsiteSchemaFactory
     */
    private $websiteSchemaFactory;

    /**
     * @var ConfigHelper
     */
    private $configHelper;

    /**
     * @param Template\Context $context
     * @param WebsiteSchemaFactory $websiteSchemaFactory
     * @param ConfigHelper $configHelper
     * @param array $data
     */
    public function __construct(
        Template\Context $context,
        WebsiteSchemaFactory $websiteSchemaFactory,
        ConfigHelper $configHelper,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->websiteSchemaFactory = $websiteSchemaFactory;
        $this->configHelper = $configHelper;
    }

    /**
     * @inheritDoc
     */
    public function getScript(): string
    {
        /** @var WebsiteSchema $websiteSchema */
        $websiteSchema = $this->websiteSchemaFactory->create();

        $websiteSchema->url($this->getBaseUrl())
            ->name($this->configHelper->getWebsiteName());

        return $websiteSchema->toScript();
    }

    /**
     * @return string
     */
    public function toHtml()
    {
        return $this->getScript();
    }
}
