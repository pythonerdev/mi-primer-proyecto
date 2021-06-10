<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Locale
 * 
 * @ORM\Entity(repositoryClass=LocaleRepository::class)
 *
 * 
 * @ORM\Table(name="locale")
 * @ORM\Entity
 */
class Locale
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     * 
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="string", length=80, nullable=false)
     * 
     */
    private $description;

    /**
     * @var string
     *
     * @ORM\Column(name="type", type="string", length=2, nullable=false)
     * 
     */
    private $type;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
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


}
