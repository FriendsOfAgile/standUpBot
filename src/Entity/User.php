<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ApiResource(
 *     itemOperations={
 *          "get"={"security"="is_granted('GET_ITEM', object)"},
 *          "put"={"security"="is_granted('EDIT', object)"},
 *          "delete"={"security"="is_granted('DELETE', object)"}
 *     },
 *     collectionOperations={
 *          "get"
 *     },
 *     normalizationContext={"groups"={"user:read"}, "swagger_definition_name"="Read"},
 *     denormalizationContext={"groups"={"user:write"}, "swagger_definition_name"="Write"}
 * )
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 */
class User implements UserInterface
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
     * @Assert\NotBlank()
     * @Groups({"user:read", "user:write"})
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $email;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Space", inversedBy="users")
     */
    private $space;

    /**
     * @Assert\NotBlank()
     * @Groups({"user:read", "user:write"})
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @Groups({"user:read"})
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $avatar;

    /**
     * @Groups({"user:read"})
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    private $timeZone;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isAdmin = false;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Carma", mappedBy="user", orphanRemoval=true)
     */
    private $carma;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\StandUp", mappedBy="user", orphanRemoval=true)
     */
    private $standUps;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $password;

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
    public function getCarma(): Collection
    {
        return $this->carma;
    }

    public function addCarma(Carma $carma): self
    {
        if (!$this->carma->contains($carma)) {
            $this->carma[] = $carma;
            $carma->setUser($this);
        }

        return $this;
    }

    public function removeCarma(Carma $carma): self
    {
        if ($this->carma->contains($carma)) {
            $this->carma->removeElement($carma);
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

    public function getType(): string
    {
        if ($this->getSpace())
            $type = $this->getSpace()->getType();
        else {
            $type = $this->getIsAdmin() ? 'admin' : 'user';
        }
        return $type;
    }

    public function getRoles()
    {
        $roles = array(
            'ROLE_USER'
        );

        if ($this->getIsAdmin())
            $roles[] = 'ROLE_ADMIN';

        return array_unique($roles);
    }

    public function getPassword()
    {
        return $this->password;
    }

    public function getSalt()
    {
        // TODO: Implement getSalt() method.
    }

    public function getUsername()
    {
        if (in_array($this->getType(), ['user', 'admin']))
            return $this->getEmail();
        return sprintf('%s@%s', $this->getUid(), $this->getType());
    }

    public function eraseCredentials()
    {
        // TODO: Implement eraseCredentials() method.
    }

    public function setPassword(?string $password): self
    {
        $this->password = $password;

        return $this;
    }


}
