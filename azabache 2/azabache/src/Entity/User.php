<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use App\Entity\Producer;
use App\Entity\Exporter;

/**
 * User
 * 
 * @ORM\Entity(repositoryClass=UserRepository::class)
 * 
 * @ORM\Table(name="user")
 * @ORM\Entity
 */
class User implements UserInterface
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * 
     * 
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     * 
     */
    private $email;

    /**
     * @ORM\Column(type="string", length=2)
     */
    private $locale;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="last_connection", type="datetime", nullable=true)
     * 
     */
    private $lastConnection;

    /**
     * @ORM\Column(type="json")
     */
    private $roles = [];

    /**
    * @var string
    *
    * @ORM\Column(name="username", type="string", length=30, nullable=false)
    * 
    */
    private $username;

    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     */
    private $password;

    /**
     * @ORM\Column(type="string")
     */
    private $avatarfilename;

    /**
     * @var bool
     *
     * @ORM\Column(name="active", type="boolean", nullable=false)
     */
    private $active = '1';    

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * @var string 
     * @ORM\Column(type="string")
     * 
     */
    private $name;
    
    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }
    
    /**
    * @var string 
    * @ORM\Column(type="string")
    * 
    */
    private $surname;

    public function getSurname(): ?string
    {
        return $this->surname;
    }

    public function setSurname(string $surname): self
    {
        $this->surname = $surname;

        return $this;
    }
    
    /**
    * @var string 
    * @ORM\Column(type="string")
    */
    private $iniciales;

    public function getIniciales(): ?string
    {
        return $this->iniciales;
    }

    public function setIniciales(string $iniciales): self
    {
        $this->iniciales = $iniciales;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getPassword(): string
    {
        return (string) $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getLocale(): string
    {
        return (string) $this->locale;
    }

    public function setLocale(string $locale): self
    {
        $this->locale = $locale;

        return $this;
    }
    
    public function getLastConnection(): ?\DateTimeInterface
    {
        return $this->lastConnection;
    }

    public function setLastConnection(?\DateTimeInterface $lastConnection): self
    {
        $this->lastConnection = $lastConnection;

        return $this;
    }

    public function getActive(): ?bool
    {
        return $this->active;
    }

    public function setActive(bool $active): self
    {
        $this->active = $active;

        return $this;
    }

    public function getavatarFilename()
    {
        return $this->avatarfilename;
    }

    public function setavatarFilename($avatarfilename)
    {
        $this->avatarfilename = $avatarfilename;

        return $this;
    }  

    /**
     * @see UserInterface
     */
    public function getSalt()
    {
        // not needed when using the "bcrypt" algorithm in security.yaml
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function __toString()
    {
        return $this->getName().' '.$this->getSurname().' ('.$this->getUsername().')';
    }
    



 
    
      






}
