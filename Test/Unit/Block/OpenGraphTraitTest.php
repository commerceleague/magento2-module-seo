<?php
/**
 */

namespace CommerceLeague\Seo\Test\Unit\Block;

use CommerceLeague\Seo\Block\OpenGraphInterface;
use CommerceLeague\Seo\Block\OpenGraphTrait;
use PHPUnit\Framework\TestCase;

class OpenGraphTraitTest extends TestCase
{
    use OpenGraphTrait;

    public function testTrimDescription()
    {
        $description = str_repeat('-', 400);
        $this->assertEquals(
            OpenGraphInterface::MAX_DESCRIPTION_LENGTH,
            strlen($this->trimDescription($description))
        );
    }
}
