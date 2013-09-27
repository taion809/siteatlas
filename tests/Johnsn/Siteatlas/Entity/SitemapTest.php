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
use org\bovigo\vfs\vfsStream;

class SitemapTest extends PHPUnit_Framework_TestCase
{
    private $filesystem;

    public function setUp()
    {
        $this->filesystem = vfsStream::setup('mockDir');
        $this->addValidSitemapData();
        $this->addInvalidSitemapData();
    }

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

    public function testSaveXMLString()
    {
        $sitemap = new Sitemap();

        $actual = $sitemap->saveXML();
        $expected = $this->getBlankSitemap();

        $this->assertXmlStringEqualsXmlString($expected, $actual);
    }

    public function testLoadXmlString()
    {
        $xml = $this->getValidSitemapData();

        $sitemap = new Sitemap();
        $result = $sitemap->loadXML($xml);

        $this->assertTrue($result);
    }

    public function testAddNode()
    {
        $sitemap = new Sitemap();
        $result = $sitemap->addNode("http://www.example.com/", "2005-01-01", "monthly", "0.8");

        $this->assertInstanceOf("DOMElement", $result);
    }

    public function testSaveXMLSavesAfterAddingNode()
    {
        $sitemap = new Sitemap();
        $sitemap->addNode("http://www.example.com/", "2005-01-01", "monthly", "0.8");

        $result = $sitemap->saveXML();
        $expected = $this->getValidSitemapData();

        $this->assertXmlStringEqualsXmlString($expected, $result);
    }

    public function testSitemapLoadActuallyLoads()
    {
        $sitemap = new Sitemap();
        $result = $sitemap->load(vfsStream::url('mockDir').DIRECTORY_SEPARATOR."mockmap.xml");

        $this->assertTrue($result);
    }

    public function testSitemapLoadNonXml()
    {
        $sitemap = new Sitemap();
        $result = $sitemap->load(vfsStream::url('mockDir').DIRECTORY_SEPARATOR."invalid_mockmap.xml");

        $this->assertFalse($result);
    }

    private function getBlankSitemap()
    {
        $xml = '<?xml version="1.0" encoding="UTF-8"?>';
        $xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" ';
        $xml .= 'xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" ';
        $xml .= 'xsi:schemaLocation="http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd"></urlset>';

        return $xml;
    }
    private function getValidSitemapData()
    {
        $xml  = '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
        $xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" ';
        $xml .= 'xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" ';
        $xml .= 'xsi:schemaLocation="http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd">' . "\n";
        $xml .= "\t" . '<url>' . "\n";
        $xml .= "\t\t" . '<loc>http://www.example.com/</loc>' . "\n";
        $xml .= "\t\t" . '<lastmod>2005-01-01</lastmod>' . "\n";
        $xml .= "\t\t" . '<changefreq>monthly</changefreq>' . "\n";
        $xml .= "\t\t" . '<priority>0.8</priority>' . "\n";
        $xml .= "\t" . '</url>' . "\n";
        $xml .= '</urlset>' . "\n";

        return $xml;
    }

    private function addValidSitemapData()
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

        if(!file_exists(vfsStream::url('mockDir').DIRECTORY_SEPARATOR."mockmap.xml"))
        {
            file_put_contents(vfsStream::url('mockDir').DIRECTORY_SEPARATOR.'mockmap.xml', $xml);
        }
    }

    private function addInvalidSitemapData()
    {
        if(!file_exists(vfsStream::url('mockDir').DIRECTORY_SEPARATOR."invalid_mockmap.xml"))
        {
            file_put_contents(vfsStream::url('mockDir').DIRECTORY_SEPARATOR.'invalid_mockmap.xml', 'Invalid Data');
        }
    }
}
