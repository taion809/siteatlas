<?php
/**
 * Created by JetBrains PhpStorm.
 * User: nick
 * Date: 9/26/13
 * Time: 10:23 PM
 * To change this template use File | Settings | File Templates.
 */

namespace Johnsn\Siteatlas\Contracts;

interface SitemapEntityInterface
{
    public function load($filename);
    public function loadXML($xml);
    public function save($filename);
    public function saveXML();
    public function addNode($location, $date, $frequency, $priority);
}
