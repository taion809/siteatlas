<?php
/**
 * Created by JetBrains PhpStorm.
 * User: nick
 * Date: 9/26/13
 * Time: 10:22 PM
 * To change this template use File | Settings | File Templates.
 */

namespace Johnsn\Siteatlas\Entity;

use Johnsn\Siteatlas\Contracts\SitemapEntityInterface;
use \DOMDocument;

class Sitemap implements SitemapEntityInterface
{
    protected $sitemap = null;

    public function __construct($version = "1.0", $encoding = "UTF-8")
    {
        $this->sitemap = new DOMDocument($version, $encoding);

        $urlset = $this->sitemap->createElementNS("http://www.sitemaps.org/schemas/sitemap/0.9", "urlset", '');
        $urlset->setAttribute('xmlns:xsi', "http://www.w3.org/2001/XMLSchema-instance");
        $urlset->setAttribute('xsi:schemaLocation', "http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd");

        $this->sitemap->appendChild($urlset);
    }

    public function getSitemapDocument()
    {
        return $this->sitemap;
    }

    public function setSitemapDocument(DOMDocument $sitemap)
    {
        $this->sitemap = $sitemap;
    }

    public function load($filename)
    {
        $xml = file_get_contents($filename);
        $result = $this->loadXML($xml);

        return $result;
    }

    public function loadXML($xml)
    {
        $sitemap = new DOMDocument();
        $sitemap->C14N(true, false);

        $result = $sitemap->loadXML($xml);

        if($result)
        {
            $this->sitemap = $sitemap;
        }

        return $result;
    }

    public function save($filename)
    {
        $xml = $this->saveXML();

        $result = file_put_contents($filename, $xml);

        return $result;
    }

    public function saveXML()
    {
        $this->sitemap->C14N(true, true);
        $this->sitemap->formatOutput = true;

        $xml = $this->sitemap->saveXML();
        return $xml;
    }
}