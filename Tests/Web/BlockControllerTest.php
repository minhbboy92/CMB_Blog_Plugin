<?php
namespace Plugin\CMBlog\Tests\Web;

use Eccube\Tests\Web\Admin\AbstractAdminWebTestCase;
use Faker\Generator;
use Symfony\Component\HttpKernel\Client;
use Symfony\Component\DomCrawler\Crawler;

/**
 * ブロックに関するテスト.
 */
class BlockControllerTest extends AbstractAdminWebTestCase
{
    public function testEnable() {
        $this->assertTrue(true);
    }

    public function testDisable() {
        $this->assertTrue(true);
    }
}
