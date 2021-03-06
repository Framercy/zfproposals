<?php
/**
 * Zend Framework
 *
 * LICENSE
 *
 * This source file is subject to the new BSD license that is bundled
 * with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://framework.zend.com/license/new-bsd
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@zend.com so we can send you a copy immediately.
 *
 * @category   Zend
 * @package    Zend_Service
 * @subpackage Yadis
 * @copyright  Copyright (c) 2005-2007 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 * @version    $Id$
 */

/** Zend_Service_Yadis_Xrds */
require_once 'Zend/Service/Yadis/Xrds.php';

/** Zend_Service_Yadis_Service */
require_once 'Zend/Service/Yadis/Service.php';

/**
 * The Zend_Service_Yadis_Xrds_Service class is a wrapper for Service elements
 * of an XRD document which is parsed using SimpleXML, and contains methods for
 * retrieving data about each Service, including Type, Url and other arbitrary
 * data added in a separate namespace, e.g. openid:Delegate for OpenID 1.1.
 *
 * This class extends the basic Zend_Service_Yadis_Xrds wrapper to implement a
 * Service object specific to the Yadis Specification 1.0. XRDS itself is not
 * an XML format ruled by Yadis, but by an OASIS proposal.
 *
 * @category   Zend
 * @package    Zend_Service
 * @subpackage Yadis
 * @uses       Iterator
 * @copyright  Copyright (c) 2005-2007 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 */
class Zend_Service_Yadis_Xrds_Service extends Zend_Service_Yadis_Xrds implements Iterator
{

    /**
     * Establish a lowest priority integer; we'll take the upper 2^31
     * integer limit.
     * Highest priority is 0.
     */
    const SERVICE_LOWEST_PRIORITY = 2147483647;

    /**
     * Holds the last XRD node of the XRD document as required by Yadis 1.0.
     *
     * @var SimpleXMLElement
     */
    protected $_xrdNode = null;
    
    /**
     * The Yadis Services resultset
     *
     * @var array
     */ 
    protected $_services = array();

    /**
     * Flag holding whether or not the array endpoint has been reached.
     *
     * @var boolean
     */
    protected $_valid = true;

    /**
     * Constructor; Accepts an XRD document for parsing.
     * Parses the XRD document by <xrd:Service> element to construct an array
     * of Zend_Service_Yadis_Service objects ordered by their priority.
     *
     * @param   SimpleXMLElement $xrds
     * @param   Zend_Service_Yadis_Xrds_Namespace $namespace
     */
    public function __construct(SimpleXMLElement $xrds, Zend_Service_Yadis_Xrds_Namespace $namespace)
    {
        parent::__construct($xrds, $namespace);
        /**
         * The Yadis Specification requires we only use the last xrd node. The
         * rest being ignored (if present for whatever reason). Important to
         * note when writing an XRD document for multiple services - put
         * the authentication service XRD node last.
         */
        $this->_xrdNode = $this->_xrdNodes[count($this->_xrdNodes) - 1];
        $this->_namespace->registerXpathNamespaces($this->_xrdNode);
        $services = $this->_xrdNode->xpath('xrd:Service');
        foreach ($services as $service) {
            $serviceObj = new Zend_Service_Yadis_Service($service, $this->_namespace);
            $this->_addService($serviceObj);
        }
        $this->_services = Zend_Service_Yadis_Xrds::sortByPriority($this->_services);
    }

    /**
     * Implements Iterator::current()
     * 
     * Return the current element.
     *
     * @return Zend_Service_Yadis_Service
     */ 
    public function current()
    {
         return current($this->_services);
    }
 
    /**
     * Implements Iterator::key()
     *
     * Return the key of the current element.
     * 
     * @return integer
     */ 
    public function key()
    {
         return key($this->_services);
    }
 
    /**
     * Implements Iterator::next()
     * 
     * Increments pointer to next Service object.
     *
     * @return void
     */ 
    public function next()
    {
         $this->_valid = (false !== next($this->_services));
    }
 
    /**
     * Implements Iterator::rewind()
     * 
     * Rewinds the Iterator to the first Service object
     *
     * @return boolean
     */ 
    public function rewind()
    {
        $this->_valid = (false !== reset($this->_services)); 
    }
 
    /**
     * Implement Iterator::valid()
     *
     * @return boolean
     */ 
    public function valid()
    {
         return $this->_valid;
    }

    /**
     * Add a service to the Service list indexed by priority. Assumes
     * a missing or invalid priority should be shuffled to the bottom
     * of the priority order.
     *
     * @param Zend_Service_Yadis_Service $service
     */
    protected function _addService(Zend_Service_Yadis_Service $service)
    {
        $servicePriority = $service->getPriority();
        if(is_null($servicePriority) || !is_numeric($servicePriority)) {
            $servicePriority = self::SERVICE_LOWEST_PRIORITY;
        }
        if (!array_key_exists($servicePriority, $this->_services)){
            $this->_services[$servicePriority] = array();
        }
        $this->_services[$servicePriority][] = $service;
    }

}