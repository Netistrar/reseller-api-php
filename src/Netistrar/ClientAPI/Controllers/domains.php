<?php

namespace Netistrar\ClientAPI\Controllers;

use Kinikit\Core\Util\HTTP\WebServiceProxy;

/**
 * The Domains API provides the core services for managing Domains within the Netistrar Registrar system.
 *
 * 
*/
class domains extends WebServiceProxy {

    /**
     * List domains currently contained within your account as <a href="../../object/DomainNameSummaryObject">DomainNameSummaryObject</a> items.
     *
     * This method supports paging operations using the <i>pageSize</i> and <i>page</i> parameters and allows for sorting of results using the <i>orderBy</i> and <i>orderDirection</i> parameters.
     *
     * 
     * @param string $searchTerm
     * @param int $pageSize
     * @param int $page
     * @param string $orderBy
     * @param string $orderDirection
     * @return \Netistrar\ClientAPI\Objects\Domain\DomainNameListResults
     */
    public function list($searchTerm = "", $pageSize = 10, $page = 1, $orderBy = "domainName", $orderDirection = "ASC"){
        return parent::callMethod("", "GET", array("searchTerm" => $searchTerm, "pageSize" => $pageSize, "page" => $page, "orderBy" => $orderBy, "orderDirection" => $orderDirection),null,"\Netistrar\ClientAPI\Objects\Domain\DomainNameListResults");
    }

    /**
     * Return a single domain name from within your account as a full domain name object.
     *
     *
     * 
     * @param string $domainName
     * @return \Netistrar\ClientAPI\Objects\Domain\DomainNameObject
     */
    public function get($domainName){
        return parent::callMethod("$domainName", "GET", array(),null,"\Netistrar\ClientAPI\Objects\Domain\DomainNameObject");
    }

    /**
     * Get multiple domain names from within your account as full domain name objects.
     *
     * 
     * @param string[] $domainNames
     * @return \Netistrar\ClientAPI\Objects\Domain\DomainNameObject[]
     */
    public function getMultiple($domainNames){
        return parent::callMethod("multiple", "POST", array(),$domainNames,"\Netistrar\ClientAPI\Objects\Domain\DomainNameObject[]");
    }

    /**
     * Validate one or more domain names for creation using a single set of contact and nameserver details.  This is designed to be called as a lightweight check before calling the create API.
     *
     * 
     * @param \Netistrar\ClientAPI\Objects\Domain\Descriptor\DomainNameCreateDescriptor $createDescriptor
     * @return mixed[string]
     */
    public function validate($createDescriptor){
        return parent::callMethod("validate", "POST", array(),$createDescriptor,"mixed[string]");
    }

    /**
     * Create one or more domain names using a single set of contact and nameserver details.
     *
     * 
     * @param \Netistrar\ClientAPI\Objects\Domain\Descriptor\DomainNameCreateDescriptor $createDescriptor
     * @param string $bulkOperationProgressKey
     * @return \Netistrar\ClientAPI\Objects\Domain\DomainNameTransaction
     */
    public function create($createDescriptor, $bulkOperationProgressKey = ""){
        return parent::callMethod("", "POST", array("bulkOperationProgressKey" => $bulkOperationProgressKey),$createDescriptor,"\Netistrar\ClientAPI\Objects\Domain\DomainNameTransaction");
    }

    /**
     * Update one or more domain names within your account.  This allows for contact, nameserver and domain name attributes to be updated in bulk.
     *
     * <b>NB: </b> Please note the special additional verification workflow documented below when changing owner contacts for GTLDs.
     *
     * 
     * @param \Netistrar\ClientAPI\Objects\Domain\Descriptor\DomainNameUpdateDescriptor $updateDescriptor
     * @param string $bulkOperationProgressKey
     * @return \Netistrar\ClientAPI\Objects\Domain\DomainNameTransaction
     */
    public function update($updateDescriptor, $bulkOperationProgressKey = ""){
        return parent::callMethod("", "PATCH", array("bulkOperationProgressKey" => $bulkOperationProgressKey),$updateDescriptor,"\Netistrar\ClientAPI\Objects\Domain\DomainNameTransaction");
    }

    /**
     *
     * 
     * @param \Netistrar\ClientAPI\Objects\Domain\Descriptor\DomainNameRenewDescriptor $renewDescriptor
     * @param string $bulkOperationProgressKey
     * @return \Netistrar\ClientAPI\Objects\Domain\DomainNameTransaction
     */
    public function renew($renewDescriptor, $bulkOperationProgressKey = ""){
        return parent::callMethod("renew", "POST", array("bulkOperationProgressKey" => $bulkOperationProgressKey),$renewDescriptor,"\Netistrar\ClientAPI\Objects\Domain\DomainNameTransaction");
    }

    /**
     * Cancel any pending owner contact changes for the supplied domain names.  Pending changes arise when a call to the <b>updateDomainNames</b> method has resulted in a change to key fields for the owner contact of a GTLD.
     *
     * In this case the owner contact will be returned with a status of <b>PENDING_CHANGES</b>.  This method will remove the pending data awaiting verification and restore the Owner contact back a status of <b>LIVE</b> with the previous details intact.  This returns a DomainNameTransaction object with transaction elements which will be successful if the owner contact is pending changes or will contain an operation error if not successful.
     *
     * 
     * @param string[] $domainNames
     * @return \Netistrar\ClientAPI\Objects\Domain\DomainNameTransaction
     */
    public function cancelPendingOwnerChanges($domainNames){
        return parent::callMethod("cancelownerchanges", "DELETE", array(),$domainNames,"\Netistrar\ClientAPI\Objects\Domain\DomainNameTransaction");
    }

    /**
     * List all previously set glue records for a domain name.   Glue records (sometimes called child nameservers) define explicit mappings of subdomains to IP addresses such that the subdomain may be used as a delegated
     * nameserver for other domains or indeed the domain name itself.
     *
     * PLEASE NOTE:  This method will only list glue records which have been explicitly set using the <b>setGlueRecords</b> method or via the Netistrar Control Panel.  There is no guarantee that this list is the complete list of records defined at the registry if this name has been transferred into Netistrar with existing additional glue records intact.
     *
     * This method returns an array of <b>DomainNameGlueRecord</b> objects currently defined for the domain name
     *
     * 
     * @param string $domainName
     * @return \Netistrar\ClientAPI\Objects\Domain\DomainNameGlueRecord[]
     */
    public function listGlueRecords($domainName){
        return parent::callMethod("gluerecords/$domainName", "GET", array(),null,"\Netistrar\ClientAPI\Objects\Domain\DomainNameGlueRecord[]");
    }

    /**
     * Set one or more glue records for a domain name.  Glue records (sometimes called child nameservers) define explicit mappings of subdomains to IP addresses such that the subdomain may be used as a delegated
     * nameserver for other domains or indeed the domain name itself.
     *
     * This method accepts a domain name and an array of <b>DomainNameGlueRecord</b> objects which comprise a subdomain prefix and either an ipv4 or ipv6 address or both.
     *
     * It returns a <b>DomainNameTransaction</b> object which encodes the result of the set operation with a transaction element for each glue record passed.  If the glue records are
     * in an invalid format or have missing data, a validation error will be returned as part of the element.
     *
     * 
     * @param string $domainName
     * @param \Netistrar\ClientAPI\Objects\Domain\DomainNameGlueRecord[] $glueRecords
     * @param string $bulkOperationProgressKey
     * @return \Netistrar\ClientAPI\Objects\Domain\DomainNameTransaction
     */
    public function setGlueRecords($domainName, $glueRecords, $bulkOperationProgressKey = ""){
        return parent::callMethod("gluerecords/$domainName", "PATCH", array("bulkOperationProgressKey" => $bulkOperationProgressKey),$glueRecords,"\Netistrar\ClientAPI\Objects\Domain\DomainNameTransaction");
    }

    /**
     * Remove one or more glue records for a domain name.  Glue records (sometimes called child nameservers) define explicit mappings of subdomains to IP addresses such that the subdomain may be used as a delegated nameserver for other domains or indeed the domain name itself.
     *
     * This method accepts a domain name and an array of String objects which represent the subdomains to remove as glue records.
     *
     * It returns a <b>DomainNameTransaction</b> object which encodes the result of the remove operation with a transaction element for each glue record passed.  Operation errors will be raised
     * if the glue record does not exist or cannot be removed
     *
     * 
     * @param string $domainName
     * @param string[] $glueRecordSubdomains
     * @param string $bulkOperationProgressKey
     * @return \Netistrar\ClientAPI\Objects\Domain\DomainNameTransaction
     */
    public function removeGlueRecords($domainName, $glueRecordSubdomains, $bulkOperationProgressKey = ""){
        return parent::callMethod("gluerecords/$domainName", "DELETE", array("bulkOperationProgressKey" => $bulkOperationProgressKey),$glueRecordSubdomains,"\Netistrar\ClientAPI\Objects\Domain\DomainNameTransaction");
    }


}

