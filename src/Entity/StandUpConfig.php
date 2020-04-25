<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Annotation\ApiSubresource;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ApiResource(
 *     normalizationContext={"groups"={"config:read"}},
 *     denormalizationContext={"groups"={"config:write"}},
 *     collectionOperations={
 *          "get",
 *          "post"
 *     },
 *     shortName="Config"
 * )
 * @ORM\Entity(repositoryClass="App\Repository\StandUpConfigRepository")
 * @ORM\EntityListeners({"App\Doctrine\StandUpConfigEntityListener"})
 */
class StandUpConfig
{
    /**
     * @ORM\Id()
     * @Groups({"config:read"})
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Space", inversedBy="standUpConfigs")
     * @ORM\JoinColumn(nullable=false)
     */
    private $space;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User")
     * @ORM\JoinColumn(nullable=false)
     */
    private $author;

    /**
     * @Groups({"config:write", "config:read"})
     * @ORM\Column(type="string", length=500, nullable=true)
     */
    private $messageBefore;

    /**
     * @Groups({"config:write", "config:read"})
     * @ORM\Column(type="string", length=500, nullable=true)
     */
    private $messageAfter;

    /**
     * @Assert\Valid()
     * @Groups({"config:write", "config:read"})
     * @ORM\OneToMany(targetEntity="App\Entity\Question", mappedBy="config", orphanRemoval=true)
     */
    private $questions;

    /**
     * @Assert\Valid()
     * @Groups({"config:write", "config:read"})
     * @ORM\OneToOne(targetEntity="App\Entity\Schedule", mappedBy="config", cascade={"persist", "remove"})
     */
    private $schedule;

    /**
     * @Assert\Valid()
     * @Groups({"config:write", "config:read"})
     * @ORM\OneToMany(targetEntity="App\Entity\Member", mappedBy="config", orphanRemoval=true)
     */
    private $members;

    /**
     * @ApiSubresource()
     * @ORM\OneToMany(targetEntity="App\Entity\StandUp", mappedBy="config", orphanRemoval=true)
     */
    private $standUps;

    /**
     * @Groups({"config:write", "config:read"})
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $name = 'Your standup';

    public function __construct()
    {
        $this->questions = new ArrayCollection();
        $this->members = new ArrayCollection();
        $this->standUps = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getAuthor(): ?User
    {
        return $this->author;
    }

    public function setAuthor(?User $author): self
    {
        $this->author = $author;

        return $this;
    }

    public function getMessageBefore(): ?string
    {
        return $this->messageBefore;
    }

    public function setMessageBefore(?string $messageBefore): self
    {
        $this->messageBefore = $messageBefore;

        return $this;
    }

    public function getMessageAfter(): ?string
    {
        return $this->messageAfter;
    }

    public function setMessageAfter(?string $messageAfter): self
    {
        $this->messageAfter = $messageAfter;

        return $this;
    }

    /**
     * @return Collection|Question[]
     */
    public function getQuestions(): Collection
    {
        return $this->questions;
    }

    public function addQuestion(Question $question): self
    {
        if (!$this->questions->contains($question)) {
            $this->questions[] = $question;
            $question->setConfig($this);
        }

        return $this;
    }

    public function removeQuestion(Question $question): self
    {
        if ($this->questions->contains($question)) {
            $this->questions->removeElement($question);
            // set the owning side to null (unless already changed)
            if ($question->getConfig() === $this) {
                $question->setConfig(null);
            }
        }

        return $this;
    }

    public function getSchedule(): ?Schedule
    {
        return $this->schedule;
    }

    public function setSchedule(Schedule $schedule): self
    {
        $this->schedule = $schedule;

        // set the owning side of the relation if necessary
        if ($schedule->getConfig() !== $this) {
            $schedule->setConfig($this);
        }

        return $this;
    }

    /**
     * @return Collection|Member[]
     */
    public function getMembers(): Collection
    {
        return $this->members;
    }

    public function addMember(Member $member): self
    {
        if (!$this->members->contains($member)) {
            $this->members[] = $member;
            $member->setConfig($this);
        }

        return $this;
    }

    public function removeMember(Member $member): self
    {
        if ($this->members->contains($member)) {
            $this->members->removeElement($member);
            // set the owning side to null (unless already changed)
            if ($member->getConfig() === $this) {
                $member->setConfig(null);
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
            $standUp->setConfig($this);
        }

        return $this;
    }

    public function removeStandUp(StandUp $standUp): self
    {
        if ($this->standUps->contains($standUp)) {
            $this->standUps->removeElement($standUp);
            // set the owning side to null (unless already changed)
            if ($standUp->getConfig() === $this) {
                $standUp->setConfig(null);
            }
        }

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function __toString()
    {
        return $this->getName();
    }
}
