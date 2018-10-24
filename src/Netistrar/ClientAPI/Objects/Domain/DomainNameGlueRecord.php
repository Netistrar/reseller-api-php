<?php

namespace Netistrar\ClientAPI\Objects\Domain;

use Kinikit\Core\Object\SerialisableObject;
/**
 * A value object used within the <b>Domains API</b> when listing or setting glue records for a domain name.
 *
 * Glue records (sometimes called child nameservers) define explicit mappings of subdomains to IP addresses such that the subdomain may be used as a delegated
 * nameserver for other domains or indeed the domain name itself.
 *
 */
class DomainNameGlueRecord extends SerialisableObject {

    /**
     * @var string
     */
    protected $domainName;

    /**
     * @var string
     */
    private $subDomainPrefix;

    /**
     * @var string
     */
    private $ipv4Address;

    /**
     * @var string
     */
    private $ipv6Address;



    /**
     * Constructor
     *
    * @param  $subDomainPrefix
    * @param  $ipv4Address
    * @param  $ipv6Address
    */
    public function __construct($subDomainPrefix = null, $ipv4Address = null, $ipv6Address = null){

        $this->subDomainPrefix = $subDomainPrefix;
        $this->ipv4Address = $ipv4Address;
        $this->ipv6Address = $ipv6Address;
        
    }

    /**
     * Get the domainName
     *
     * @return string
     */
    public function getDomainName(){
        return $this->domainName;
    }

    /**
     * Get the subDomainPrefix
     *
     * @return string
     */
    public function getSubDomainPrefix(){
        return $this->subDomainPrefix;
    }

    /**
     * Set the subDomainPrefix
     *
     * @param string $subDomainPrefix
     */
    public function setSubDomainPrefix($subDomainPrefix){
        $this->subDomainPrefix = $subDomainPrefix;
    }

    /**
     * Get the ipv4Address
     *
     * @return string
     */
    public function getIpv4Address(){
        return $this->ipv4Address;
    }

    /**
     * Set the ipv4Address
     *
     * @param string $ipv4Address
     */
    public function setIpv4Address($ipv4Address){
        $this->ipv4Address = $ipv4Address;
    }

    /**
     * Get the ipv6Address
     *
     * @return string
     */
    public function getIpv6Address(){
        return $this->ipv6Address;
    }

    /**
     * Set the ipv6Address
     *
     * @param string $ipv6Address
     */
    public function setIpv6Address($ipv6Address){
        $this->ipv6Address = $ipv6Address;
    }


}