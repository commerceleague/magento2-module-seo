<?php
declare(strict_types=1);
/**
 */

namespace CommerceLeague\Seo\Block\OpenGraph;

use CommerceLeague\Seo\Block\OpenGraphInterface;
use CommerceLeague\Seo\Block\OpenGraphTrait;
use Magento\Framework\View\Element\Template;
use Magento\Cms\Model\Page as CmsPage;

/**
 * Class Page
 */
class Page extends Template implements OpenGraphInterface
{
    use OpenGraphTrait;

    /**
     * @var CmsPage
     */
    private $page;

    /**
     * @param Template\Context $context
     * @param CmsPage $page
     * @param array $data
     */
    public function __construct(
        Template\Context $context,
        CmsPage $page,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->page = $page;
    }

    /**
     * @inheritDoc
     */
    public function getOgTitle(): string
    {
        if (!$this->page->getId()) {
            return '';
        }

        $title = '';

        if ($this->page->getMetaTitle()) {
            $title = $this->page->getMetaTitle();
        } elseif ($this->page->getTitle()) {
            $title = $this->page->getTitle();
        }

        return (string)$title;
    }

    /**
     * @inheritDoc
     */
    public function getOgDescription(): string
    {
        if (!$this->page->getId()) {
            return '';
        }

        $description = '';

        if ($this->page->getMetaDescription()) {
            $description = $this->page->getMetaDescription();
        } elseif ($this->page->getContent()) {
            $description = $this->page->getContent();
        }

        return $this->trimDescription($description);
    }

    /**
     * @inheritDoc
     */
    public function getOgUrl(): string
    {
        if (!$this->page->getId()) {
            return '';
        }

        return $this->_urlBuilder->getUrl($this->page->getIdentifier());
    }

    /**
     * @inheritDoc
     */
    public function getOgImage(): string
    {
        return '';
    }
}
