<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ApiResource(
 *     normalizationContext={"groups"={"question:read"}},
 *     denormalizationContext={"groups"={"question:write"}}
 * )
 * @ORM\Entity(repositoryClass="App\Repository\QuestionRepository")
 */
class Question
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @Groups({"question:read", "question:write","config:write", "config:read"})
     * @Assert\NotBlank()
     * @ORM\Column(type="string", length=500)
     */
    private $text;

    /**
     * @Groups({"question:read", "question:write", "config:write", "config:read"})
     * @ORM\Column(type="string", length=100)
     */
    private $color = 'gray';

    /**
     * @Groups({"question:read", "question:write", "config:read"})
     * @ORM\Column(type="integer")
     */
    private $sort = 0;

    /**
     * @Groups({"question:read", "question:write"})
     * @ORM\ManyToOne(targetEntity="App\Entity\StandUpConfig", inversedBy="questions")
     * @ORM\JoinColumn(nullable=false)
     */
    private $config;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getText(): ?string
    {
        return $this->text;
    }

    public function setText(string $text): self
    {
        $this->text = $text;

        return $this;
    }

    public function getColor(): ?string
    {
        return $this->color;
    }

    public function setColor(string $color): self
    {
        $this->color = $color;

        return $this;
    }

    public function getSort(): ?int
    {
        return $this->sort;
    }

    public function setSort(int $sort): self
    {
        $this->sort = $sort;

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

    public function __toString()
    {
        return $this->getText();
    }
}
