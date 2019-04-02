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
            'states',
        ];
        $data = [];
        foreach ($fields as $field) {
            $data[$field] = $this->{$field};
        }

        return $data;
    }

    public function toArray(): array
    {
        $json = (string)json_encode($this);
        return json_decode($json, true);
    }

    /**
     * @return State|null
     */
    public function getLastState()
    {
        $state = end($this->states);
        if ($state === false) {
            return null;
        }

        return $state;
    }

    public function getTrackingLink(): string
    {
        return $this->trackingLink;
    }
}
