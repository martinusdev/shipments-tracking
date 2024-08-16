<?php

declare(strict_types=1);

namespace MartinusDev\ShipmentsTracking\Endpoints\PPL;

class ShipmentEvent
{
    /**
     * @var int $statusId
     */
    protected $statusId = null;

    /**
     * @var string $code
     */
    protected $code = null;

    /**
     * @var string $phase
     */
    protected $phase = null;

    /**
     * @var string $group
     */
    protected $group = null;

    /**
     * @var string $eventDate
     */
    protected $eventDate = null;

    /**
     * @var string $name
     */
    protected $name = null;

    /**
     * @param int $statusId
     * @param string $code
     * @param string $phase
     * @param string $group
     * @param string $eventDate
     * @param string $name
     */
    public function __construct(
        int $statusId,
        string $code,
        string $phase,
        string $group,
        string $eventDate,
        string $name
    ) {
        $this->statusId = $statusId;
        $this->code = $code;
        $this->phase = $phase;
        $this->group = $group;
        $this->eventDate = $eventDate;
        $this->name = $name;
    }

    /**
     * @return int
     */
    public function getStatusId(): int
    {
        return $this->statusId;
    }

    /**
     * @return string
     */
    public function getCode(): string
    {
        return $this->code;
    }

    /**
     * @return string
     */
    public function getPhase(): string
    {
        return $this->phase;
    }

    /**
     * @return string
     */
    public function getGroup(): string
    {
        return $this->group;
    }

    /**
     * @return string
     */
    public function getEventDate(): string
    {
        return $this->eventDate;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }
}
