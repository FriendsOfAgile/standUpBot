<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ApiResource(
 *     denormalizationContext={"groups"={"member:write"}},
 *     normalizationContext={"groups"={"member:read"}}
 * )
 * @ORM\Entity(repositoryClass="App\Repository\MemberRepository")
 * @ORM\EntityListeners({"App\Doctrine\MemberEntityListener"})
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
     * @Groups({"member:write"})
     * @ORM\ManyToOne(targetEntity="App\Entity\StandUpConfig", inversedBy="members", cascade={"persist"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $config;

    /**
     * @Groups({"member:read", "config:read"})
     * @ORM\ManyToOne(targetEntity="App\Entity\User")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * @Groups({"member:read", "member:write", "config:read"})
     * @ORM\Column(type="boolean")
     */
    private $canRead = true;

    /**
     * @Groups({"member:read", "member:write", "config:read"})
     * @ORM\Column(type="boolean")
     */
    private $canWrite = false;

    /**
     * @Groups({"member:read", "member:write", "config:read"})
     * @ORM\Column(type="boolean")
     */
    private $canEdit = false;

    /**
     * @Groups({"config:write", "member:write"})
     * @var string
     */
    private $uid;

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

    public function getUid(): ?string
    {
        return $this->uid;
    }

    public function setUid(string $uid): self
    {
        $this->uid = $uid;
        return $this;
    }

    public function __toString()
    {
        return $this->getUser() ? $this->getUser()->getName() : 'Unknown';
    }
}
