<?php
/**
 * Created by PhpStorm.
 * User: samue
 * Date: 27.03.2019
 * Time: 0:17
 */

namespace MartinusDev\ShipmentsTracking\Shipment\ShipmentStates;

use Cake\Chronos\Chronos;
use JsonSerializable;

abstract class State implements JsonSerializable
{
    /** @var string */
    protected $name;
    /** @var \Cake\Chronos\Chronos */
    public $date;
    /** @var string */
    public $description;
    /** @var string[] */
    public $original;

    /**
     * @param array<string,array<string>|string|Chronos> $data
     */
    public function __construct(array $data = [])
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

    public function __toString()
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
