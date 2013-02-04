<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

namespace Larium\Sale;

/**
 * AdjustableInterface contains a collection of Adjustments and calculates a 
 * total amount besed on the behaviour of each Adjustment. 
 * 
 * @package Larium\Sale
 * @author  Andreas Kollaros <php@andreaskollaros.com> 
 * @license MIT {@link http://opensource.org/licenses/mit-license.php}
 */
interface AdjustableInterface
{

    public function addAdjustment(AdjustmentInterface $adjustment);
    
    public function removeAdjustment(AdjustmentInterface $adjustment);
    
    public function containsAdjustment(AdjustmentInterface $adjustment);

    public function getAdjustments();

    /**
     * Calculates the total amount of adjustments.
     *  
     * @access public
     * @return void
     */
    public function calculateAdjustmentsTotal();
    
    public function getAdjustmentsTotal();
}
