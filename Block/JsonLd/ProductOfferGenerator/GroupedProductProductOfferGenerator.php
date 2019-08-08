<?php
declare(strict_types=1);
/**
 */

namespace CommerceLeague\Seo\Block\JsonLd\ProductOfferGenerator;

use CommerceLeague\Seo\Block\JsonLd\AbstractProduct;
use CommerceLeague\Seo\Block\JsonLd\ProductOfferGeneratorInterface;
use Magento\Catalog\Block\Product\ImageBuilder;
use Magento\Catalog\Model\Product as ProductModel;
use Magento\Framework\Registry;
use Magento\GroupedProduct\Model\Product\Type\Grouped as GroupedType;
use Spatie\SchemaOrg\AggregateOffer as AggregateOfferSchema;
use Spatie\SchemaOrg\AggregateOfferFactory as AggregateOfferSchemaFactory;
use Spatie\SchemaOrg\ItemAvailability;
use Spatie\SchemaOrg\Offer as OfferSchema;
use Spatie\SchemaOrg\OfferFactory as OfferSchemaFactory;
use Magento\Framework\View\Element\Template;

/**
 * Class GroupedProductOfferGenerator
 */
class GroupedProductProductOfferGenerator extends AbstractProduct implements ProductOfferGeneratorInterface
{
    /**
     * @var AggregateOfferSchemaFactory
     */
    private $aggregateOfferSchemaFactory;

    /**
     * @var OfferSchemaFactory
     */
    private $offerSchemaFactory;

    /**
     * @param Template\Context $context
     * @param Registry $registry
     * @param ImageBuilder $imageBuilder
     * @param AggregateOfferSchemaFactory $aggregateOfferSchemaFactory
     * @param OfferSchemaFactory $offerSchemaFactory
     * @param array $data
     */
    public function __construct(
        Template\Context $context,
        Registry $registry,
        ImageBuilder $imageBuilder,
        AggregateOfferSchemaFactory $aggregateOfferSchemaFactory,
        OfferSchemaFactory $offerSchemaFactory,
        array $data = []
    ) {
        parent::__construct(
            $context,
            $registry,
            $imageBuilder,
            $data
        );
        $this->aggregateOfferSchemaFactory = $aggregateOfferSchemaFactory;
        $this->offerSchemaFactory = $offerSchemaFactory;
    }

    /**
     * @inheritDoc
     */
    public function getOffers(ProductModel $product): array
    {
        /** @var GroupedType $productType */
        $productType = $product->getTypeInstance();

        /** @var AggregateOfferSchema $aggregateOfferSchema */
        $aggregateOfferSchema = $this->aggregateOfferSchemaFactory->create();

        $aggregateOfferSchema->priceCurrency($product->getStore()->getCurrentCurrencyCode())
            ->name($product->getName())
            ->availability($product->isInStock() ? ItemAvailability::InStock : ItemAvailability::OutOfStock);

        $associatedProducts = $productType->getAssociatedProducts($product);
        $prices = [];
        $offers = [];

        /** @var ProductModel $associatedProduct */
        foreach ($associatedProducts as $associatedProduct) {
            $prices[] = $associatedProduct->getFinalPrice();
            $offers[] = $this->getAssociatedProductOffer($product, $associatedProduct);
        }

        $aggregateOfferSchema->offerCount(count($associatedProducts))
            ->highPrice((float)(max($prices)))
            ->lowPrice((float)min($prices));

        if (!empty($offers)) {
            $aggregateOfferSchema->offers($offers);
        }

        return [$aggregateOfferSchema];
    }

    /**
     * @param ProductModel $parentProduct
     * @param ProductModel $associatedProduct
     * @return OfferSchema
     */
    private function getAssociatedProductOffer(
        ProductModel $parentProduct,
        ProductModel $associatedProduct
    ): OfferSchema {
        /** @var OfferSchema $offerSchema */
        $offerSchema = $this->offerSchemaFactory->create();

        $offerSchema->name($associatedProduct->getName())
            ->price($associatedProduct->getFinalPrice())
            ->sku($associatedProduct->getSku())
            ->image($this->getProductImage($parentProduct));

        return $offerSchema;
    }
}
