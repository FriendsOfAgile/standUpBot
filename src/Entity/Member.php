<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\MemberRepository")
 */
class Member
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\StandUpConfig", inversedBy="members")
     * @ORM\JoinColumn(nullable=false)
     */
    private $config;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * @ORM\Column(type="boolean")
     */
    private $canRead;

    /**
     * @ORM\Column(type="boolean")
     */
    private $canWrite;

    /**
     * @ORM\Column(type="boolean")
     */
    private $canEdit;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getCanRead(): ?bool
    {
        return $this->canRead;
    }

    public function setCanRead(bool $canRead): self
    {
        $this->canRead = $canRead;

        return $this;
    }

    public function getCanWrite(): ?bool
    {
        return $this->canWrite;
    }

    public function setCanWrite(bool $canWrite): self
    {
        $this->canWrite = $canWrite;

        return $this;
    }

    public function getCanEdit(): ?bool
    {
        return $this->canEdit;
    }

    public function setCanEdit(bool $canEdit): self
    {
        $this->canEdit = $canEdit;

        return $this;
    }
}
