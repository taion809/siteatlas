<?php
/**
 * Created by JetBrains PhpStorm.
 * User: nick
 * Date: 9/24/13
 * Time: 10:16 AM
 * To change this template use File | Settings | File Templates.
 */

namespace Johnsn\Siteatlas;

use \PHPUnit_Framework_TestCase;

class SiteatlasTest extends PHPUnit_Framework_TestCase
{
    public function testConstructorAndGetters()
    {
        $siteatlas = new Siteatlas();

        $this->assertNotNull($siteatlas);
    }

    public function testGetSitemapReturnsDOMDocument()
    {
        $siteatlas = new Siteatlas();

        $sitemap = $siteatlas->getSitemapDocument();

        $this->assertInstanceOf("DOMDocument", $sitemap);
    }
}
