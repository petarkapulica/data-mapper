<?php

use G4\DataMapper\Engine\Solr\SolrSelectionFactory;

class SolrSelectionFactoryTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var SolrSelectionFactory
     */
    private $selectionFactory;

    private $identityMock;

    protected function setUp()
    {
        $this->identityMock = $this->getMockBuilder('\G4\DataMapper\Common\Identity')
            ->disableOriginalConstructor()
            ->getMock();

        $this->selectionFactory = new SolrSelectionFactory($this->identityMock);
    }

    protected function tearDown()
    {
        $this->identityMock = null;

        $this->selectionFactory = null;
    }

    public function testFieldNames()
    {
        $this->identityMock
            ->expects($this->once())
            ->method('getFieldNames')
            ->willReturn(['name']);

        $this->assertEquals(['name'], $this->selectionFactory->fieldNames());
    }

    public function testEmptyFieldNames()
    {
        $this->identityMock
            ->expects($this->once())
            ->method('getFieldNames')
            ->willReturn([]);

        $this->assertEquals('*', $this->selectionFactory->fieldNames());
    }

    public function testGroup()
    {
        $this->identityMock
            ->expects($this->once())
            ->method('getGrouping')
            ->willReturn([]);

        $this->assertEquals([], $this->selectionFactory->group());
    }

    public function testLimit()
    {
        $this->identityMock
            ->expects($this->once())
            ->method('getLimit')
            ->willReturn(5);

        $this->assertEquals(5, $this->selectionFactory->limit());
    }

    public function testOffset()
    {
        $this->identityMock
            ->expects($this->once)
            ->method('getOffset')
            ->willReturn(8);
    }
}
