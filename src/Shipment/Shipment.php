<?php

namespace MartinusDev\ShipmentsTracking\Shipment;

use JsonSerializable;
use MartinusDev\ShipmentsTracking\Shipment\ShipmentStates\State;

class Shipment implements JsonSerializable
{
    /** @var \MartinusDev\ShipmentsTracking\Carriers\CarrierInterface */
    public $carrier;
    public $carrierName;
    /** @var string */
    public $number = '';
    /** @var string */
    public $trackingLink;
    /** @var State[] */
    public $states;

    /**
     * Shipment constructor.
     *
     * @param array $data
     */
    public function __construct($data)
    {
        $data += [
            'number' => null,
            'carrierName' => null,
            'carrier' => null,
            'trackingLink' => null,
        ];

        $this->number = $data['number'];
        $this->carrierName = $data['carrierName'];
        $this->carrier = $data['carrier'];
        $this->trackingLink = $data['trackingLink'];

        $this->states = $this->carrier->getStates($this);
    }

    public function __toString()
    {
        return $this->number;
    }

    public function jsonSerialize()
    {
        $fields = [
            'number',
            'carrierName',
            'trackingLink',
        ];
        $data = [];
        foreach ($fields as $field) {
            $data[$field] = $this->{$field};
        }

        return $data;
    }

    public function getLastState(): State
    {
        return end($this->states);
    }

    public function getTrackingLink(): string
    {
        return $this->trackingLink;
    }
}
