<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 */
class User
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=500, nullable=true)
     */
    private $uid;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $email;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Space", inversedBy="users")
     */
    private $space;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $avatar;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    private $timeZone;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isAdmin;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Carma", mappedBy="user", orphanRemoval=true)
     */
    private $carmas;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\StandUp", mappedBy="user", orphanRemoval=true)
     */
    private $standUps;

    public function __construct()
    {
        $this->carmas = new ArrayCollection();
        $this->standUps = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getSpace(): ?Space
    {
        return $this->space;
    }

    public function setSpace(?Space $space): self
    {
        $this->space = $space;

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

    public function getAvatar(): ?string
    {
        return $this->avatar;
    }

    public function setAvatar(?string $avatar): self
    {
        $this->avatar = $avatar;

        return $this;
    }

    public function getTimeZone(): ?string
    {
        return $this->timeZone;
    }

    public function setTimeZone(?string $timeZone): self
    {
        $this->timeZone = $timeZone;

        return $this;
    }

    public function getIsAdmin(): ?bool
    {
        return $this->isAdmin;
    }

    public function setIsAdmin(bool $isAdmin): self
    {
        $this->isAdmin = $isAdmin;

        return $this;
    }

    /**
     * @return Collection|Carma[]
     */
    public function getCarmas(): Collection
    {
        return $this->carmas;
    }

    public function addCarma(Carma $carma): self
    {
        if (!$this->carmas->contains($carma)) {
            $this->carmas[] = $carma;
            $carma->setUser($this);
        }

        return $this;
    }

    public function removeCarma(Carma $carma): self
    {
        if ($this->carmas->contains($carma)) {
            $this->carmas->removeElement($carma);
            // set the owning side to null (unless already changed)
            if ($carma->getUser() === $this) {
                $carma->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|StandUp[]
     */
    public function getStandUps(): Collection
    {
        return $this->standUps;
    }

    public function addStandUp(StandUp $standUp): self
    {
        if (!$this->standUps->contains($standUp)) {
            $this->standUps[] = $standUp;
            $standUp->setUser($this);
        }

        return $this;
    }

    public function removeStandUp(StandUp $standUp): self
    {
        if ($this->standUps->contains($standUp)) {
            $this->standUps->removeElement($standUp);
            // set the owning side to null (unless already changed)
            if ($standUp->getUser() === $this) {
                $standUp->setUser(null);
            }
        }

        return $this;
    }
}
