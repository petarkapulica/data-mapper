<?php

namespace G4\DataMapper\Engine\Solr;

use G4\DataMapper\Common\IdentityInterface;
use G4\DataMapper\Common\SelectionFactoryInterface;
use G4\DataMapper\Common\Selection\Sort;
use G4\DataMapper\Common\Selection\Comparison;

class SolrSelectionFactory implements SelectionFactoryInterface
{
    /**
     * @var IdentityInterface
     */
    private $identity;

    public function __construct(IdentityInterface $identity)
    {
        $this->identity = $identity;
    }

    public function fieldNames()
    {
        $fieldNames = $this->identity->getFieldNames();

        return count($fieldNames) === 0
            ? '*'
            : $fieldNames;
    }

    public function group()
    {
        return $this->identity->getGrouping();
    }

    public function limit()
    {
        return (int) $this->identity->getLimit();
    }

    public function offset()
    {
        return (int) $this->identity->getOffset();
    }

    public function sort()
    {

        $rawSorting = $this->identity->getSorting();

        if (empty($rawSorting)) {
            return [];
        }

        $sorting = [];

        foreach ($rawSorting as $oneSort) {
            if ($oneSort instanceof Sort) {
                $sorting[] = $oneSort->getSort($this->makeSortingFormatter());
            }
        }
        return $sorting;

    }

    public function where()
    {
        if ($this->identity->isVoid()) {
            return '1';
        }

        $comparisons = [];

        foreach ($this->identity->getComparisons() as $oneComparison) {
            if ($oneComparison instanceof Comparison) {
                $comparisons[] = $oneComparison->getComparison($this->makeComparisonFormatter());
            }
        }
        return join(' AND ', $comparisons);
    }

    private function makeComparisonFormatter()
    {
        return new SolrComparisonFormatter();
    }

    private function makeSortingFormatter()
    {
        return new SolrSortingFormatter();
    }


}