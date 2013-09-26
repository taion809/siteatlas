<?php
/**
 * Created by JetBrains PhpStorm.
 * User: nick
 * Date: 9/25/13
 * Time: 9:47 AM
 * To change this template use File | Settings | File Templates.
 */

namespace Johnsn\Siteatlas;

use DOMDocument;

class Siteatlas
{
    protected $sitemap = null;

    protected $urlset_namespace = "http://www.sitemaps.org/schemas/sitemap/0.9";

    public function __construct(DOMDocument $sitemap = null, $version = '1.0', $encoding = 'UTF-8')
    {
        if(empty($sitemap)) {
            $this->sitemap = new DOMDocument($version, $encoding);
            $urlset = $this->sitemap->createElementNS($this->urlset_namespace, "urlset", '');

            $this->sitemap->appendChild($urlset);
        } else {
            $this->sitemap = $sitemap;
        }
    }

    public function getSitemapDocument()
    {
        return $this->sitemap;
    }

    public function load($filename, $options = 0)
    {
        //I don't like using @ for anything
        //TODO: think of a better way.
        $result = @$this->sitemap->load($filename, $options);

        return $result;
    }

    public function addElement($location, $date, $priority = "0.5", $frequency = "weekly")
    {
        $url_element = $this->sitemap->createElement('url');
        $url_element->appendChild($this->sitemap->createElement('loc', htmlentities($location)));
        $url_element->appendChild($this->sitemap->createElement('lastmod', $date));
        $url_element->appendChild($this->sitemap->createElement('changefreq', $frequency));
        $url_element->appendChild($this->sitemap->createElement('priority', $priority));

        $urlset = $this->sitemap->getElementsByTagName('urlset')->item(0);
        $urlset->appendChild($url_element);

        return $url_element;
    }

    public function save($filename, $format = true)
    {
        $this->sitemap->formatOutput = $format;

        $result = $this->sitemap->saveXML();

        if(!empty($result))
        {
            $result = file_put_contents($filename, $result);
        }

        return $result;
    }

}
