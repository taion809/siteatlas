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

    public function __construct(DOMDocument $sitemap = null, $version = '1.0', $encoding = 'UTF-8')
    {
        if(empty($sitemap)) {
            $this->sitemap = new DOMDocument($version, $encoding);
        } else {
            $this->sitemap = $sitemap;
        }
    }

    public function getSitemapDocument()
    {
        return $this->sitemap;
    }
}
