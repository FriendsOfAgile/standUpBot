<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\StandUpDelayRepository")
 */
class StandUpDelay
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\StandUpConfig")
     * @ORM\JoinColumn(nullable=false)
     */
    private $config;

    /**
     * @ORM\Column(type="datetime")
     */
    private $sendAfter;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getConfig(): ?StandUpConfig
    {
        return $this->config;
    }

    public function setConfig(?StandUpConfig $config): self
    {
        $this->config = $config;

        return $this;
    }

    public function getSendAfter(): ?\DateTimeInterface
    {
        return $this->sendAfter;
    }

    public function setSendAfter(\DateTimeInterface $sendAfter): self
    {
        $this->sendAfter = $sendAfter;

        return $this;
    }
}
