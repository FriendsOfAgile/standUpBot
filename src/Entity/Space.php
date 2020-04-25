<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Core\Annotation\ApiResource;

/**
 * @ORM\Entity(repositoryClass="App\Repository\SpaceRepository")
 */
class Space
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private $type;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=500, nullable=true)
     */
    private $uid;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\User", mappedBy="space")
     */
    private $users;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\StandUpConfig", mappedBy="space", orphanRemoval=true)
     */
    private $standUpConfigs;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $token;

    /**
     * @ORM\Column(type="string", length=500, nullable=true)
     */
    private $avatar;

    public function __construct()
    {
        $this->users = new ArrayCollection();
        $this->standUpConfigs = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getUid(): ?string
    {
        return $this->uid;
    }

    public function setUid(?string $uid): self
    {
        $this->uid = $uid;

        return $this;
    }

    /**
     * @return Collection|User[]
     */
    public function getUsers(): Collection
    {
        return $this->users;
    }

    public function addUser(User $user): self
    {
        if (!$this->users->contains($user)) {
            $this->users[] = $user;
            $user->setSpace($this);
        }

        return $this;
    }

    public function removeUser(User $user): self
    {
        if ($this->users->contains($user)) {
            $this->users->removeElement($user);
            // set the owning side to null (unless already changed)
            if ($user->getSpace() === $this) {
                $user->setSpace(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|StandUpConfig[]
     */
    public function getStandUpConfigs(): Collection
    {
        return $this->standUpConfigs;
    }

    public function addStandUpConfig(StandUpConfig $standUpConfig): self
    {
        if (!$this->standUpConfigs->contains($standUpConfig)) {
            $this->standUpConfigs[] = $standUpConfig;
            $standUpConfig->setSpace($this);
        }

        return $this;
    }

    public function removeStandUpConfig(StandUpConfig $standUpConfig): self
    {
        if ($this->standUpConfigs->contains($standUpConfig)) {
            $this->standUpConfigs->removeElement($standUpConfig);
            // set the owning side to null (unless already changed)
            if ($standUpConfig->getSpace() === $this) {
                $standUpConfig->setSpace(null);
            }
        }

        return $this;
    }

    public function getToken(): ?string
    {
        return $this->token;
    }

    public function setToken(?string $token): self
    {
        $this->token = $token;

        return $this;
    }

    public function getAvatar(): ?string
    {
        return $this->avatar;
    }

    public function setAvatar(?string $avatar): self
    {
        $this->avatar = $avatar;

        return $this;
    }

    public function __toString()
    {
        return $this->getName();
    }
}
