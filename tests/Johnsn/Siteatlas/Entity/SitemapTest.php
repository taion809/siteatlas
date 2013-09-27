<?php
/**
 * Created by JetBrains PhpStorm.
 * User: nick
 * Date: 9/26/13
 * Time: 10:10 PM
 * To change this template use File | Settings | File Templates.
 */

namespace Johnsn\Siteatlas\Entity;

use \PHPUnit_Framework_TestCase;

class SitemapTest extends PHPUnit_Framework_TestCase
{
    public function testGetSitemapReturnsDOMDocument()
    {
        $sitemap = new Sitemap();

        $document = $sitemap->getSitemapDocument();

        $this->assertInstanceOf("DOMDocument", $document);
    }

    public function testSetSitemap()
    {
        $sitemap = new Sitemap();
        $old_document = $sitemap->getSitemapDocument();

        $new_document = new \DOMDocument();
        $sitemap->setSitemapDocument($new_document);

        $document = $sitemap->getSitemapDocument();

        $this->assertInstanceOf("DOMDocument", $document);
        $this->assertNotEquals($document, $old_document);
    }

    public function testLoadXmlString()
    {
        $xml = $this->getValidSitemapData();

        $sitemap = new Sitemap();
        $result = $sitemap->loadXML($xml);

        $this->assertTrue($result);
    }

    private function getValidSitemapData()
    {
        $xml  = '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
        $xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";
        $xml .= "\t" . '<url>' . "\n";
        $xml .= "\t\t" . '<loc>http://www.example.com/</loc>' . "\n";
        $xml .= "\t\t" . '<lastmod>2005-01-01</lastmod>' . "\n";
        $xml .= "\t\t" . '<changefreq>monthly</changefreq>' . "\n";
        $xml .= "\t\t" . '<priority>0.8</priority>' . "\n";
        $xml .= "\t" . '</url>' . "\n";
        $xml .= '</urlset>' . "\n";

        return $xml;
    }
}
