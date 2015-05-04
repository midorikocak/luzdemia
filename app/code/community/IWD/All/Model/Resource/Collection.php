<?php
class IWD_All_Model_Resource_Collection extends Varien_Data_Collection
{
    protected $_filters = array();
    protected $_items_count = 0;

    public function load($print_query = false, $log_query = false)
    {
        $this->_addFilterToCollection();
        $this->_sortCollection();

        $this->_items_count = count($this->_items);

        $this->_renderLimit();

        return $this;
    }

    public function getSize()
    {
        return $this->_items_count;
    }

    public function addFieldToFilter($attribute, $condition = null)
    {
        if (isset($condition['eq'])) {
            $this->_filters[] = array(
                'attribute' => $attribute,
                'method' => 'equal',
                'value' => $condition['eq'],
            );
        } elseif (isset($condition['like'])) {
            $this->_filters[] = array(
                'attribute' => $attribute,
                'method' => 'like',
                'value' => $condition['like'],
            );
        }
    }

    public function equal($filer_value, $needle)
    {
        return ($filer_value == $needle);
    }

    public function like($filer_value, $needle)
    {
        $needle = trim($needle, ' \'"%');
        return stristr($filer_value, $needle);
    }

    protected function _renderLimit()
    {
        if ($this->_pageSize) {
            $items = $this->_items;
            $this->_items = array();

            $from = ($this->getCurPage() - 1) * $this->_pageSize;
            $to = $from + $this->_pageSize - 1;
            $to = count($items) <= $to ? count($items) : $to;

            for ($i = $from; $i <= $to; $i++) {
                if (isset($items[$i])) {
                    $this->addItem($items[$i]);
                }
            }
        }

        return $this;
    }

    protected function _sortCollection()
    {
        usort($this->_items, array($this, '_doCompare'));
        return $this;
    }

    protected function _doCompare($a, $b)
    {
        foreach ($this->_orders as $column => $order) {
            $valueA = $this->_getColumnsValue($a, $column);
            $valueB = $this->_getColumnsValue($b, $column);

            $result = strcmp($valueA, $valueB);

            if (strtolower($order) == 'asc') {
                $result = -$result;
            }

            return $result;
        }
        return 0;
    }

    protected function _addFilterToCollection()
    {
        $items = $this->_items;
        $this->_items = array();
        foreach ($items as $item) {
            if ($this->_filterItem($item)) {
                $this->addItem($item);
            }
        }
    }

    protected function _filterItem($item)
    {
        foreach ($this->_filters as $filter) {
            $method = $filter['method'];
            $attribute = $filter['attribute'];
            $itemValue = $item[$attribute];

            if (is_array($itemValue) ){
                if (!$this->$method($this->_getColumnsValue($item, $filter['attribute']), $filter['value'])) {
                    return false;
                }
            }else if (!$this->$method($itemValue, $filter['value'])) {
                return false;
            }
        }

        return true;
    }

    protected function _getColumnsValue($item, $column)
    {
        $value = $item->getData($column);

        if (is_array($value))
           return implode(',', $value);

        return $value;
    }
}