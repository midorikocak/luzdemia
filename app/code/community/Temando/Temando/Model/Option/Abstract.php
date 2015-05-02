<?php

abstract class Temando_Temando_Model_Option_Abstract extends Varien_Object
{
    
    /**
     * The key of this option in a collection.
     *
     * @var string
     */
    protected $_id = null;
    
    /**
     * The name of this option as shown on the frontend.
     *
     * @var string
     */
    protected $_name = null;

    /**
     * The desc of this option as shown on the frontend.
     *
     * @var string
     */
    protected $_desc = null;
    
    /**
     * An associative array of the valid values for this option.
     *
     * @var array
     */
    protected $_values = array();
    
    /**
     * The forced value, if one exists, otherwise NULL.
     */
    protected $_forced_value = null;
    
    /**
     * The type of the action object that applies to this option.
     *
     * @var string
     */
    protected $_action_type = '';
    
    /**
     * The object that performs the action that applies to this option.
     *
     * @var Temando_Temando_Model_Option_Action_Abstract
     */
    protected $_action = null;
    
    
    /**
     * Calls the _setupValues() method to initialise the values array.
     *
     * (non-PHPdoc)
     *
     * @see Varien_Object::_construct()
     */
    protected function _construct()
    {
        parent::_construct();
        $this->_setupValues();
        $this->_setupAction();
    }
    
    /**
     * Sets up the $this->_values array.
     *
     * Called in the self::_construct() method.
     */
    abstract protected function _setupValues();
    
    /**
     * Sets up the action that applies to this option.
     *
     * Called in the self::_construct() method.
     */
    protected function _setupAction()
    {
        $model_name = 'temando/option_action_' . $this->_action_type;
        $this->_action = Mage::getModel($model_name);
        
        if (!$this->_action) {
            throw Mage::exception('Temando_Temando', 'Incorrect action model for option.');
        }
    }
    
    /**
     * Use the ID in this class, for use in collections etc.
     *
     * (non-PHPdoc)
     *
     * @see Varien_Object::getId()
     */
    public function getId()
    {
        return $this->_id;
    }
    
    /**
     * Gets the name of this option as shown on the frontend.
     *
     * @return string
     */
    public function getName()
    {
        return $this->_name;
    }
    
    /**
     * Gets the valid values for this option as an associative array.
     *
     * Format is as follows:
     *
     * array(
     *     $value => $label,
     *     // ...
     * )
     *
     * where $value is what's used internally and $label is shown on the
     * frontend.
     *
     * @return array
     */
    public function getValues()
    {
        return $this->_values;
    }
    
    /**
     * Gets the label for a given value of this option.
     */
    public function getLabel($value)
    {
        if (array_key_exists($value, $this->_values)) {
            return $this->_values[$value];
        }
        return null;
    }

    /**
     * Gets the description for the option
     */
    public function getDescription()
    {
        return $this->_desc;
    }
    
    /**
     * Sets one of the values for this option as the only available selection.
     *
     * The $forced_value parameter should be a key in the array returned by
     * $this->getValues(), otherwise no action is taken.
     *
     * @return Temando_Temando_Model_Option_Abstract
     */
    public function setForcedValue($forced_value)
    {
        if (array_key_exists($forced_value, $this->_values) || $forced_value === null) {
            $this->_forced_value = $forced_value;
        }
        
        return $this;
    }
    
    /**
     * Gets the forced value for this option (if there is one), otherwise NULL.
     *
     * A forced option is one that can't be changed by the customer.
     */
    public function getForcedValue()
    {
        return $this->_forced_value;
    }
    
    /**
     * Applies a given value of this option to a quote.
     *
     * A copy of the quote is returned, with the option's effects applied.
     *
     * @param Temando_Temando_Model_Quote $quote
     * @return Temando_Temando_Model_Option_Abstract
     */
    public function apply($value, &$quote)
    {
        $this->_apply($value, $quote);
        return $this;
    }
    
    /**
     * Does the actual applying of the quote, based on the value of the option.
     *
     * Returns true if the action was applied, or false if it wasn't.
     *
     * (Overridden in child classes)
     *
     * @param $value
     * @param Temando_Temando_Model_Quote $quote
     */
    abstract protected function _apply($value, &$quote);
    
}
