<?php

namespace Netistrar\ClientAPI\Objects\Domain;

use Kinikit\Core\Object\SerialisableObject;
/**

*/
class DomainNameDNSSECRecord extends SerialisableObject {

    /**
     * The domain name for which this glue record applies.  This field is only populated for results returned from the <b>listDNSSECRecords</b> method on the
     * <b>Domains API</b> and is not supplied when setting DNSSEC records.
     *
     * @var string 
     */
    protected $domainName;

    /**
     * The key tag for this DNSSEC record.  This is obtained from the DNS provider
     *
     * @var integer 
     */
    private $keyTag;

    /**
     * The digest for this DNSSEC record.  This is obtained from the DNS provider and should be in Hexadecimal format.
     *
     * @var string 
     */
    private $digest;

    /**
     * The algorithm in use for this DNSSEC record.  This is obtained from the DNS provider and
     * will be usually be a number between 1 and 5.  NB: support of algorithms varies amongst Registry
     * operators with algorithm number 5 (RSA/SHA-1) being the only mandatory value under the DNSSEC specification.
     * This is the default value if none is supplied
     *
     * @var integer 
     */
    private $algorithm;

    /**
     * The digest type in use for this DNSSEC record.  This is obtained from the DNS provider and
     * is usually set to a value of 1 (SHA-1) which is the default value if none is supplied.
     *
     * @var integer 
     */
    private $digestType;



    /**
     * Constructor
     *
     * @param  $keyTag
     * @param  $digest
     * @param  $algorithm
     * @param  $digestType
     */
    public function __construct($keyTag = null, $digest = null, $algorithm = 5, $digestType = 1){

        $this->keyTag = $keyTag;
        $this->digest = $digest;
        $this->algorithm = $algorithm;
        $this->digestType = $digestType;
        
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
     * Get the keyTag
     *
     * @return integer
     */
    public function getKeyTag(){
        return $this->keyTag;
    }

    /**
     * Set the keyTag
     *
     * @param integer $keyTag
     * @return DomainNameDNSSECRecord
     */
    public function setKeyTag($keyTag){
        $this->keyTag = $keyTag;
        return $this;
    }

    /**
     * Get the digest
     *
     * @return string
     */
    public function getDigest(){
        return $this->digest;
    }

    /**
     * Set the digest
     *
     * @param string $digest
     * @return DomainNameDNSSECRecord
     */
    public function setDigest($digest){
        $this->digest = $digest;
        return $this;
    }

    /**
     * Get the algorithm
     *
     * @return integer
     */
    public function getAlgorithm(){
        return $this->algorithm;
    }

    /**
     * Set the algorithm
     *
     * @param integer $algorithm
     * @return DomainNameDNSSECRecord
     */
    public function setAlgorithm($algorithm){
        $this->algorithm = $algorithm;
        return $this;
    }

    /**
     * Get the digestType
     *
     * @return integer
     */
    public function getDigestType(){
        return $this->digestType;
    }

    /**
     * Set the digestType
     *
     * @param integer $digestType
     * @return DomainNameDNSSECRecord
     */
    public function setDigestType($digestType){
        $this->digestType = $digestType;
        return $this;
    }


}