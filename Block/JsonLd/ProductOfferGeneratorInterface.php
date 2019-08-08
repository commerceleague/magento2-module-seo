<?php
declare(strict_types=1);
/**
 */

namespace CommerceLeague\Seo\Block\JsonLd;

use Magento\Catalog\Model\Product as ProductModel;
use Spatie\SchemaOrg\BaseType as BaseTypeSchema;

/**
 * Interface ProductOfferGeneratorInterface
 */
interface ProductOfferGeneratorInterface
{
    /**
     * @param ProductModel $product
     * @return BaseTypeSchema[]
     */
    public function getOffers(ProductModel $product): array;
}