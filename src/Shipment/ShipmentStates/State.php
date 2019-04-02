<?php
/**
 * Created by PhpStorm.
 * User: samue
 * Date: 27.03.2019
 * Time: 0:17
 */

namespace MartinusDev\ShipmentsTracking\Shipment\ShipmentStates;

use JsonSerializable;

abstract class State implements JsonSerializable
{
    protected $name;
    /** @var \Cake\Chronos\Chronos */
    public $date;
    /** @var string */
    public $description;
    /** @var array */
    public $original;

    public function __construct($data = [])
    {
        $data += [
            'date' => null,
            'description' => null,
            'original' => [],
        ];
        $this->date = $data['date'];
        $this->description = $data['description'];
        $this->original = $data['original'];
    }

    function __toString()
    {
        return $this->name;
    }

    public function jsonSerialize()
    {
        $fields = [
            'name',
            'date',
            'description',
        ];
        $data = [];
        foreach ($fields as $field) {
            $data[$field] = $this->{$field};
        }

        return $data;
    }
}
