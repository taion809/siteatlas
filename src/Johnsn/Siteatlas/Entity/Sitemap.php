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

    protected $version = "1.0";

    protected $encoding = "UTF-8";

    public function __construct($version = "1.0", $encoding = "UTF-8")
    {
        $this->version = $version;
        $this->encoding = $encoding;

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

        //I hate using @ but i can't catch or do anything with the errors.
        $result = @$sitemap->loadXML($xml);

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

    public function addNode($location, $date, $frequency = 'weekly', $priority = '0.5')
    {
        $url_element = $this->sitemap->createElement('url');
        $url_element->appendChild($this->sitemap->createElement('loc', htmlentities($location, ENT_DISALLOWED, $this->encoding)));
        $url_element->appendChild($this->sitemap->createElement('lastmod', $date));
        $url_element->appendChild($this->sitemap->createElement('changefreq', $frequency));
        $url_element->appendChild($this->sitemap->createElement('priority', $priority));

        $urlset = $this->sitemap->getElementsByTagName('urlset')->item(0);
        $urlset->appendChild($url_element);

        return $url_element;
    }
}
