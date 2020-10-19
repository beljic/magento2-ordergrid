<?php
namespace PSSoftware\OrderGrid\Setup\Patch\Data;

use Magento\Framework\Setup\Patch\DataPatchInterface;
use Magento\Ui\Model\ResourceModel\Bookmark\Collection as BookmarkCollection;

/**
 * Class PopulateGridData
 * @package PSSoftware\OrderGrid\Setup\Patch\Data
 */
class PopulateGridData implements DataPatchInterface
{
    /**
     * @var BookmarkCollection
     */
    private $bookmarkCollection;

    /**
     * @param BookmarkCollection $bookmarkCollection
     */
    public function __construct(
        BookmarkCollection $bookmarkCollection
    ) {
        $this->bookmarkCollection = $bookmarkCollection;
    }

    /**
     * Update sales_order_grid table
     */
    private function populateSalesOrderGridColumns()
    {
        $connection = $this->bookmarkCollection->getConnection();

        /* Magento Commerce uses magento_sales_order_grid_archive too*/
        $gridTables = [
            $connection->getTableName('sales_order_grid'),
            $connection->getTableName('magento_sales_order_grid_archive')
        ];

        foreach ($gridTables as $table) {
            if($connection->isTableExists($table)) {
                $select = $connection->select()->join(
                    $connection->getTableName('sales_order'),
                    sprintf("sales_order.entity_id = %s.entity_id", $table),
                    ['coupon_code']
                );
                $connection->query(
                    $connection->updateFromSelect(
                        $select,
                        $table
                    )
                );
            }
        }
    }

    /**
     * Clean ui_bookmarks table
     */
    private function deleteOrderGridBookmarks()
    {
        $connection = $this->bookmarkCollection->getConnection();

        $ids = $this->bookmarkCollection->addFieldToFilter('namespace', [
            'in' => ['sales_order_grid', 'sales_archive_order_grid']
        ])->getColumnValues('bookmark_id');

        $connection->delete(
            $this->bookmarkCollection->getMainTable(),
            $connection->quoteInto('bookmark_id IN(?)', $ids)
        );
    }

    /**
     * Update sales_order_grid and clean ui_bookmarks
     */
    public function apply()
    {
        $this->populateSalesOrderGridColumns();
        $this->deleteOrderGridBookmarks();
    }

    /**
     * Get aliases (previous names) for the patch.
     *
     * @return string[]
     */
    public function getAliases()
    {
        return [];
    }

    /**
     * Get array of patches that have to be executed prior to this.
     *
     * @return string[]
     */
    public static function getDependencies()
    {
        return [];
    }
}
