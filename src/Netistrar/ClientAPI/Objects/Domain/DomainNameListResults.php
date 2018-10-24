<?php

namespace Netistrar\ClientAPI\Objects\Domain;

use Kinikit\Core\Object\SerialisableObject;
/**
 * A results object which wraps an array of <a href="domain-name-summary-object">DomainNameSummaryObject</a> items returned from a call to <i>listDomains</i> within the <a href="netistrar-domain-lifecycle-api">Netistrar Domain Lifecycle API</a>.
 * This object encodes the criteria used to obtain and sort this list as well as the total number of items for the list criteria ignoring supplied page and pageSize which allows for the development of paging GUIs.
 *
 */
class DomainNameListResults extends SerialisableObject {

    /**
     * @var string
     */
    protected $searchTerm;

    /**
     * @var integer
     */
    protected $pageSize;

    /**
     * @var integer
     */
    protected $page;

    /**
     * @var string
     */
    protected $orderBy;

    /**
     * @var string
     */
    protected $orderDirection;

    /**
     * @var integer
     */
    protected $numberOfDomainsReturned;

    /**
     * @var integer
     */
    protected $totalNumberOfDomains;

    /**
     * @var integer
     */
    protected $totalNumberOfPages;

    /**
     * @var \Netistrar\ClientAPI\Objects\Domain\DomainNameSummary[]
     */
    protected $domainNameSummaries;



    /**
     * Constructor
     *
    */
    public function __construct(){

        
    }

    /**
     * Get the searchTerm
     *
     * @return string
     */
    public function getSearchTerm(){
        return $this->searchTerm;
    }

    /**
     * Get the pageSize
     *
     * @return integer
     */
    public function getPageSize(){
        return $this->pageSize;
    }

    /**
     * Get the page
     *
     * @return integer
     */
    public function getPage(){
        return $this->page;
    }

    /**
     * Get the orderBy
     *
     * @return string
     */
    public function getOrderBy(){
        return $this->orderBy;
    }

    /**
     * Get the orderDirection
     *
     * @return string
     */
    public function getOrderDirection(){
        return $this->orderDirection;
    }

    /**
     * Get the numberOfDomainsReturned
     *
     * @return integer
     */
    public function getNumberOfDomainsReturned(){
        return $this->numberOfDomainsReturned;
    }

    /**
     * Get the totalNumberOfDomains
     *
     * @return integer
     */
    public function getTotalNumberOfDomains(){
        return $this->totalNumberOfDomains;
    }

    /**
     * Get the totalNumberOfPages
     *
     * @return integer
     */
    public function getTotalNumberOfPages(){
        return $this->totalNumberOfPages;
    }

    /**
     * Get the domainNameSummaries
     *
     * @return \Netistrar\ClientAPI\Objects\Domain\DomainNameSummary[]
     */
    public function getDomainNameSummaries(){
        return $this->domainNameSummaries;
    }


}