<?php
/**
 * Created by JetBrains PhpStorm.
 * User: nick
 * Date: 9/24/13
 * Time: 10:16 AM
 * To change this template use File | Settings | File Templates.
 */

namespace Johnsn\Siteatlas;

use org\bovigo\vfs\vfsStream;
use \PHPUnit_Framework_TestCase;

class SiteatlasTest extends PHPUnit_Framework_TestCase
{
    private $filesystem;

    public function setUp()
    {
        $this->filesystem = vfsStream::setup('mockDir');
        $this->addValidSitemapData();
        $this->addInvalidSitemapData();
    }

    public function testConstructor()
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

    public function testGetSitemapHasURLSetElement()
    {
        $siteatlas = new Siteatlas();
        $sitemap = $siteatlas->getSitemapDocument();
        $urlset = $sitemap->getElementsByTagName("urlset");

        $this->assertGreaterThanOrEqual(1, $urlset->length);
    }

    public function testSitemapLoadActuallyLoads()
    {
        $siteatlas = new Siteatlas();
        $result = $siteatlas->load(vfsStream::url('mockDir').DIRECTORY_SEPARATOR."mockmap.xml");
        $this->assertTrue($result);
    }

    public function testSitemapLoadNonXml()
    {
        $siteatlas = new Siteatlas();
        $result = $siteatlas->load(vfsStream::url('mockDir').DIRECTORY_SEPARATOR."invalid_mockmap.xml");
        $this->assertFalse($result);
    }

    public function testAddElementReturnsNewElement()
    {
        $siteatlas = new Siteatlas();
        $siteatlas->load(vfsStream::url('mockDir').DIRECTORY_SEPARATOR."mockmap.xml");

        $result = $siteatlas->addElement("http://www.google.com", date("Y-m-d"));

        $this->assertInstanceOf("DOMElement", $result);
    }

    public function testAddElementAddsNewElementToSitemap()
    {
        $siteatlas = new Siteatlas();
        $siteatlas->load(vfsStream::url('mockDir').DIRECTORY_SEPARATOR."mockmap.xml");

        $siteatlas->addElement("http://www.google.com", date("Y-m-d"));

        $sitemap = $siteatlas->getSitemapDocument();

        $loc_element = $sitemap->getElementsByTagName('loc');

        $location = $loc_element->item(1)->nodeValue;
        $expected = "http://www.google.com";

        $this->assertEquals($expected, $location);
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
