<?php

class Temando_Temando_Model_Options extends Varien_Data_Collection
{
    
    /**
     * Applies all options to a quote, returning a quote for every combination.
     *
     * For example, if there are three boolean options available, there will
     * be eight quotes returned. In general, for (n) options with (m) values,
     * there will be (m^n) quotes returned.
     *
     * @param Temando_Temando_Model_Quote $quote
     */
    public function applyAll($quote)
    {
        if (!($quote instanceof Temando_Temando_Model_Quote)) {
            return null;
        }
        
        /* @var $quote Temando_Temando_Model_Quote */
        $quotes = array(); 
        foreach ($this->_listPermutations() as $permutation) {
            // apply every option to the quote
	    $permutation_id = '';
            $new_quote = clone $quote;
            $valid = true;

            // apply all options to the new quote
            foreach ($permutation as $option_id => $option_value) {
                $option = $this->getItemById($option_id);
                /* @var $option Temando_Temando_Model_Option_Abstract */
                $extras = $new_quote->getExtras();
                if ($valid) {
                    if ($option_value == 'Y' && (!is_array($extras) || !array_key_exists($option_id, $extras))) {
                        $valid = false;
                    }

                    if ($extras && isset($extras[$option_id])) {
                        $extra_field = $extras[$option_id];
                        if (isset($extra_field['mandatory']) && ($option_value == 'N') && ($extra_field['mandatory'] == 'Y')) {
                            $valid = false;
                        }
                    }
                }
		
                $option->apply($option_value, $new_quote);
                
                $permutation_id .= $option_id . '_' . $option_value . '_';
            }
            
            // remove last underscore
            $permutation_id = substr($permutation_id, 0, -1);
            
            if ($valid) {
                $quotes[$permutation_id] = $new_quote;
            }
        }
        
        return $quotes;
    }
    
    /**
     * Works out all the possible permutations for a given set of options.
     *
     * This function works recursively to build every combination of values
     * for every option.
     *
     * The return value is in the following format:
     *
     * array(
     *     array(
     *         'option_1_id' => 'option_1_value_a',
     *         'option_2_id' => 'option_2_value_a',
     *         // ...
     *     ),
     *     array(
     *         'option_1_id' => 'option_1_value_a',
     *         'option_2_id' => 'option_2_value_b',
     *         // ...
     *     ),
     *     // ...
     * );
     *
     * @param array $options
     * @param array $progress
     *
     * @return array
     */
    protected function _listPermutations($options = null, $progress = array())
    {
        if ($options === null) {
            $options = $this->_items;
        }
        
    	if (count($options) > 0) {
    		// remove head of list and keep it in $current_option
    		$current_option = each($options);
    		array_shift($options);
    		
    		// build up permutations
    		$permutations = array();
    		
    		foreach ($current_option['value']->getValues() as $value => $label) {
    		    // only use this value if there's no forced value, or if this is the forced value.
    		    if ($current_option['value']->getForcedValue() === null || $current_option['value']->getForcedValue() == $value) {
        			$progress[$current_option['key']] = $value;
        			$permutations = array_merge($permutations, $this->_listPermutations($options, $progress));
    		    }
    		}
    		
    		return $permutations;
    	} else {
    		return array($progress);
    	}
    }
    
}
