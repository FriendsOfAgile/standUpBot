<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ApiResource(
 *     normalizationContext={"groups"={"schedule:read"}},
 *     denormalizationContext={"groups"={"schedule:write"}}
 * )
 * @ORM\Entity(repositoryClass="App\Repository\ScheduleRepository")
 */
class Schedule
{
    const DAYS_OF_THE_WEEK = array(
        'monday',
        'tuesday',
        'wednesday',
        'thursday',
        'friday',
        'saturday',
        'sunday'
    );

    /**
     * @ORM\Id()
     * @Groups({"schedule:read"})
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @Groups({"schedule:write"})
     * @ORM\OneToOne(targetEntity="App\Entity\StandUpConfig", inversedBy="schedule", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $config;

    /**
     * @Assert\NotBlank()
     * @Groups({"schedule:read", "schedule:write", "config:read", "config:write"})
     * @ORM\Column(type="string", length=255)
     */
    private $time = '10:00';

    /**
     * @ORM\Column(type="json", nullable=true)
     */
    private $daysOfTheWeek = [];

    /**
     * @Assert\GreaterThanOrEqual(1)
     * @Groups({"schedule:read", "schedule:write", "config:read", "config:write"})
     * @ORM\Column(type="integer")
     */
    private $weeksForRepeat = 1;

    /**
     * @Groups({"schedule:read", "schedule:write", "config:read", "config:write"})
     * @ORM\Column(type="boolean")
     */
    private $useUserTimezone = false;

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

    public function getTime(): ?string
    {
        return $this->time;
    }

    public function setTime(string $time): self
    {
        $this->time = $time;

        return $this;
    }

    protected function getDaysOfTheWeek(): ?array
    {
        return $this->daysOfTheWeek;
    }

    protected function setDaysOfTheWeek(?array $daysOfTheWeek): self
    {
        $this->daysOfTheWeek = $daysOfTheWeek;

        return $this;
    }

    /**
     * @Groups({"schedule:read", "config:read"})
     * @return array
     */
    public function getWeekSchedule(): array
    {
        $result = array();
        foreach (self::DAYS_OF_THE_WEEK as $index => $day) {
            $result[$day] = $this->daysOfTheWeek[$index] ?? false;
        }
        return $result;
    }

    /**
     * @Groups({"schedule:write", "config:write"})
     * @param array $schedule
     * @return Schedule
     */
    public function setWeekSchedule(array $schedule): self
    {
        foreach (self::DAYS_OF_THE_WEEK as $index => $day) {
            $this->daysOfTheWeek[$index] = $schedule[$day] ?? false;
        }
        return $this;
    }

    public function getWeeksForRepeat(): ?int
    {
        return $this->weeksForRepeat;
    }

    public function setWeeksForRepeat(int $weeksForRepeat): self
    {
        $this->weeksForRepeat = $weeksForRepeat;

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
        return '/api/schedules/'.$this->getId();
    }
}
