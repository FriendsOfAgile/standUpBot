<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ScheduleRepository")
 */
class Schedule
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\StandUpConfig", inversedBy="schedule", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $config;

    /**
     * @ORM\Column(type="json", nullable=true)
     */
    private $daysOfTheWeek = [];

    /**
     * @ORM\Column(type="integer")
     */
    private $weeksForRepeaat;

    /**
     * @ORM\Column(type="boolean")
     */
    private $useUserTimezone;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getConfig(): ?StandUpConfig
    {
        return $this->config;
    }

    public function setConfig(StandUpConfig $config): self
    {
        $this->config = $config;

        return $this;
    }

    public function getDaysOfTheWeek(): ?array
    {
        return $this->daysOfTheWeek;
    }

    public function setDaysOfTheWeek(?array $daysOfTheWeek): self
    {
        $this->daysOfTheWeek = $daysOfTheWeek;

        return $this;
    }

    public function getWeeksForRepeaat(): ?int
    {
        return $this->weeksForRepeaat;
    }

    public function setWeeksForRepeaat(int $weeksForRepeaat): self
    {
        $this->weeksForRepeaat = $weeksForRepeaat;

        return $this;
    }

    public function getUseUserTimezone(): ?bool
    {
        return $this->useUserTimezone;
    }

    public function setUseUserTimezone(bool $useUserTimezone): self
    {
        $this->useUserTimezone = $useUserTimezone;

        return $this;
    }

    public function __toString()
    {
        return 'Not implemented';
    }
}
