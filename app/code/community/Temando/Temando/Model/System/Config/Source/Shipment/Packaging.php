<?php

class Temando_Temando_Model_System_Config_Source_Shipment_Packaging extends Temando_Temando_Model_System_Config_Source
{
    const BOX               = 0;
    const CARTON            = 1;
    const CRATE             = 2;
    const CYLINDER          = 3;
    const DOCUMENT_ENVELOPE = 4;
    const FLAT_PACK         = 5;
    const LETTER            = 6;
    const PALLET            = 7;
    const PARCEL            = 8;
    const SATCHEL_OR_BAG    = 9;
    const SKID              = 10;
    const UNPACKAGED_OR_NA  = 11;
    const WHEEL_OR_TYRE     = 12;
    
    protected function _setupOptions()
    {
        $this->_options = array(
            self::BOX               => 'Box',
            self::CARTON            => 'Carton',
            self::CRATE             => 'Crate',
            self::CYLINDER          => 'Cylinder',
            self::DOCUMENT_ENVELOPE => 'Document Envelope',
            self::FLAT_PACK         => 'Flat Pack',
            self::LETTER            => 'Letter',
            self::PALLET            => 'Pallet',
            self::PARCEL            => 'Parcel',
            self::SATCHEL_OR_BAG    => 'Satchel/Bag',
            self::SKID              => 'Skid',
            self::UNPACKAGED_OR_NA  => 'Unpackaged or N/A',
            self::WHEEL_OR_TYRE     => 'Wheel/Tyre'
        );
    }
    
}
