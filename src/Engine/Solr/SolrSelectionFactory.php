<?php

namespace G4\DataMapper\Engine\Solr;

use G4\DataMapper\Common\CoordinatesValue;
use G4\DataMapper\Common\SelectionFactoryInterface;
use G4\DataMapper\Common\Selection\Sort;
use G4\DataMapper\Common\Selection\Comparison;

class SolrSelectionFactory implements SelectionFactoryInterface
{
    /**
     * @var SolrIdentity
     */
    private $identity;

    public function __construct(SolrIdentity $identity)
    {
        $this->identity = $identity;
    }

    public function fieldNames()
    {
        $fieldNames = $this->identity->getFieldNames();

        return !is_array($fieldNames) || count($fieldNames) === 0
            ? '*'
            : $this->getFieldNamesString();
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

        $sorting = [];

        if (!empty($rawSorting)) {
            foreach ($rawSorting as $oneSort) {
                if ($oneSort instanceof Sort) {
                    $sorting[] = $oneSort->getSort($this->makeSortingFormatter());
                }
            }
        }

        return join(',', $sorting);
    }

    public function where()
    {
        if ($this->identity->isVoid()) {
            return '*:*';
        }

        $comparisons = [];

        foreach ($this->identity->getComparisons() as $oneComparison) {
            if ($oneComparison instanceof Comparison) {
                if(!$oneComparison->getValue()->isEmpty()) {
                    $comparisons[] = $oneComparison->getComparison($this->makeComparisonFormatter());
                }
            }
        }

        $comparisonsString = join(' AND ', $comparisons);

        return $this->identity->hasRawQuery() ? sprintf('%s AND %s', $this->identity->getRawQuery(), $comparisonsString) : $comparisonsString;
    }

    public function getGeodistParameters()
    {
        return $this->identity->getCoordinates();
    }

    private function getFieldNamesString()
    {
        $fieldNames = [];

        foreach($this->identity->getFieldNames() as $fieldName) {
            $fieldNames[] = is_array($fieldName) ? key($fieldName) .':'.$fieldName[key($fieldName)] : $fieldName;
        }

        return join(',', $fieldNames);
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
