<?php
/**
 * Logicrays
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade this extension to newer
 * version in the future.
 *
 * @category    Logicrays
 * @package     Logicrays_StockNotification
 * @copyright   Copyright (c) Logicrays (https://www.logicrays.com/)
 */
declare(strict_types=1);

namespace Logicrays\StockNotification\Unit\Test\Model\ResourceModel\StockNotification;

use Magento\Framework\DB\Adapter\Pdo\Mysql;
use Magento\Framework\DB\Select;
use Magento\Framework\EntityManager\MetadataPool;
use Magento\Framework\Model\ResourceModel\Db\AbstractDb;
use Magento\Framework\TestFramework\Unit\Helper\ObjectManager;
use Magento\Store\Model\StoreManagerInterface;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Logicrays\StockNotification\Model\ResourceModel\StockNotification\Collection;

/**
 * Unit test for collection file.
 */
class CollectionTest extends TestCase
{
    /**
     * @var Select|MockObject
     */
    private $select;

    /**
     * @var Mysql|MockObject
     */
    private $connection;

    /**
     * @var ObjectManager|MockObject
     */
    private $objectManager;

    /**
     * @var AbstractDb|MockObject
     */
    private $resource;

    protected function setUp(): void
    {
        $this->storeManagerMock  = $this->getMockBuilder(StoreManagerInterface::class)
            ->getMockForAbstractClass();

        $this->metadataPoolMock  = $this->getMockBuilder(MetadataPool::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->select = $this->getMockBuilder(Select::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->connection = $this->getMockBuilder(Mysql::class)
            ->disableOriginalConstructor()
            ->getMock();
        $this->connection->expects($this->any())->method('select')->willReturn($this->select);

        $this->resource = $this->getMockBuilder(AbstractDb::class)
            ->disableOriginalConstructor()
            ->setMethods(['getConnection', 'getMainTable', 'getTable'])
            ->getMockForAbstractClass();
        $this->resource->expects($this->any())->method('getConnection')->willReturn($this->connection);
        $this->resource->expects($this->any())->method('getMainTable')->willReturn('table_test');
        $this->resource->expects($this->any())->method('getTable')->willReturn('test');

        $this->objectManager = new ObjectManager($this);

        $this->collection = $this->objectManager->getObject(
            Collection::class,
            [
                'resource' => $this->resource,
                'connection' => $this->connection,
                'storeManager' => $this->storeManagerMock,
                'metadataPool' => $this->metadataPoolMock,
            ]
        );
    }

    public function testAddFieldToFilter()
    {
        $field = 'title';
        $value = 'test_filter';
        $searchSql = 'sql query';

        $this->connection->expects($this->any())->method('quoteIdentifier')->willReturn($searchSql);
        $this->connection->expects($this->any())->method('prepareSqlCondition')->willReturn($searchSql);

        $this->select->expects($this->once())
            ->method('where')
            ->with($searchSql, null, Select::TYPE_CONDITION);

        $this->assertSame($this->collection, $this->collection->addFieldToFilter($field, $value));
    }
}
