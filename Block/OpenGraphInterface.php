<?php
/**
 */

namespace CommerceLeague\Seo\Block;

/**
 * Interface OpenGraphInterface
 */
interface OpenGraphInterface
{
    public const MAX_DESCRIPTION_LENGTH = 200;

    /**
     * @return string
     */
    public function getOgTitle(): string;

    /**
     * @return string
     */
    public function getOgDescription(): string;

    /**
     * @return string
     */
    public function getOgUrl(): string;

    /**
     * @return string
     */
    public function getOgImage(): string;
}
