<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/zf2 for the canonical source repository
 * @copyright Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Zend\Feed\Writer\Extension\Syndication\Renderer;

use DOMDocument;
use DOMElement;
use Zend\Feed\Writer\Extension;

/**
*/
class Feed extends Extension\AbstractRenderer
{
    /**
     * Set to TRUE if a rendering method actually renders something. This
     * is used to prevent premature appending of a XML namespace declaration
     * until an element which requires it is actually appended.
     *
     * @var bool
     */
    protected $called = false;

    /**
     * Render feed
     *
     * @return void
     */
    public function render()
    {
        if (strtolower($this->getType()) == 'atom') {
            return;
        }
        $this->_setSyUpdatePeriod($this->dom, $this->base);
        $this->_setSyUpdateFrequency($this->dom, $this->base);
        if ($this->called) {
            $this->_appendNamespaces();
        }
    }

    /**
     * Append namespaces to feed element
     *
     * @return void
     */
    protected function _appendNamespaces()
    {
        $this->getRootElement()->setAttribute('xmlns:sy','http://purl.org/rss/1.0/modules/syndication/');
    }

    /**
     * Set feed authors
     *
     * @param  DOMDocument $dom
     * @param  DOMElement $root
     * @return void
     */
    protected function _setSyUpdatePeriod(DOMDocument $dom, DOMElement $root)
    {
        $period = $this->getDataContainer()->getPeriod();
        if (!$period || empty($period)) {
            return;
        }
        $element = $dom->createElement('sy:updatePeriod',$period);
        $root->appendChild($element);
        $this->called = true;
    }

    protected function _setSyUpdateFrequency(DOMDocument $dom, DOMElement $root)
    {
        $frequency = $this->getDataContainer()->getFrequency();
        if (!$frequency || empty($frequency)) {
            return;
        }
        $element = $dom->createElement('sy:updateFrequency',$frequency);
        $root->appendChild($element);
        $this->called = true;
    }
}