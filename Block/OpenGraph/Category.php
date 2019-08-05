<?php
declare(strict_types=1);
/**
 */

namespace CommerceLeague\Seo\Block\OpenGraph;

use CommerceLeague\Seo\Block\OpenGraphInterface;
use CommerceLeague\Seo\Block\OpenGraphTrait;
use Magento\Catalog\Block\Category\View as CategoryView;
use Magento\Framework\Exception\LocalizedException;

/**
 * Class Category
 */
class Category extends CategoryView implements OpenGraphInterface
{
    use OpenGraphTrait;

    /**
     * @inheritDoc
     */
    public function getOgTitle(): string
    {
        $category = $this->getCurrentCategory();
        $title = '';

        if ($category->getData('meta_title')) {
            $title = $category->getData('meta_title');
        } elseif ($category->getName()) {
            $title = $category->getName();
        }

        return $title;
    }

    /**
     * @inheritDoc
     */
    public function getOgDescription(): string
    {
        $category = $this->getCurrentCategory();
        $description = '';

        if ($category->getData('meta_description')) {
            $description = $category->getData('meta_description');
        } elseif ($category->getData('short_description')) {
            $description = $category->getData('short_description');
        } elseif ($category->getData('description')) {
            $description = $category->getData('description');
        }

        return $this->trimDescription($description);
    }

    /**
     * @inheritDoc
     */
    public function getOgUrl(): string
    {
        return (string) $this->getCurrentCategory()->getUrl();
    }

    /**
     * @return string
     * @throws LocalizedException
     */
    public function getOgImage(): string
    {
        return (string) $this->getCurrentCategory()->getImageUrl();
    }
}
