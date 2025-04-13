<?php

declare(strict_types=1);

namespace App\Entity\User;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\DBAL\Types\Types;
use App\Entity\Account\Account;
use App\Entity\Label\Label;
use App\Repository\User\UserRepository;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;
use App\Validator\Constraint\UniqueEmail;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table(name: '`user`')]
#[ORM\UniqueConstraint(name: 'UNIQ_IDENTIFIER_EMAIL', fields: ['email'])] 
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 180)]
    #[Assert\NotBlank(message: 'Veuillez saisir un prénom.')]
    private ?string $firstname = null;

    #[ORM\Column(length: 180)]
    #[Assert\NotBlank(message: 'Veuillez saisir un nom.')]
    private ?string $lastname = null;

    #[ORM\Column(length: 180)]
    #[Assert\Email(message: 'Veuillez saisir une adresse email valide.')]
    #[Assert\NotBlank(message: 'Veuillez saisir une adresse email.')]
    #[UniqueEmail()]
    private ?string $email = null;

    /**
     * @var list<string> The user roles
     */
    #[ORM\Column]
    private array $roles = [];

    /**
     * @var string The hashed password
     */
    #[ORM\Column]
    #[Assert\NotBlank(message: 'Veuillez saisir un mot de passe.')]
    private ?string $password = null;

    /**
     * @var Collection<int, Label>
     */
    #[ORM\OneToMany(targetEntity: Label::class, mappedBy: 'createdBy', orphanRemoval: true)]
    private Collection $labels;

    /**
     * @var Collection<int, Account>
     */
    #[ORM\OneToMany(targetEntity: Account::class, mappedBy: 'owner', orphanRemoval: true)]
    private Collection $ownedAccounts;

    /**
     * @var Collection<int, Account>
     */
    #[ORM\OneToMany(targetEntity: Account::class, mappedBy: 'collaborator', orphanRemoval: true)]
    private Collection $collaboratedAccounts;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    #[Gedmo\Timestampable(on: 'create')]
    private ?\DateTimeInterface $createdAt = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    #[Gedmo\Timestampable(on: 'update')]
    private ?\DateTimeInterface $updatedAt = null;

    public function __construct()
    {
        $this->labels = new ArrayCollection();
        $this->ownedAccounts = new ArrayCollection();
        $this->collaboratedAccounts = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    public function setFirstname(string $firstname): static
    {
        $this->firstname = $firstname;

        return $this;
    }

    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    public function setLastname(string $lastname): static
    {
        $this->lastname = $lastname;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     *
     * @return list<string>
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    /**
     * @param list<string> $roles
     */
    public function setRoles(array $roles): static
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @return Collection<int, Label>
     */
    public function getLabels(): Collection
    {
        return $this->labels;
    }

    public function addLabel(Label $label): static
    {
        if (!$this->labels->contains($label)) {
            $this->labels->add($label);
            $label->setCreatedBy($this);
        }

        return $this;
    }

    public function removeLabel(Label $label): static
    {
        if ($this->labels->removeElement($label)) {
            // set the owning side to null (unless already changed)
            if ($label->getCreatedBy() === $this) {
                $label->setCreatedBy(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Account>
     */
    public function getOwnedAccounts(): Collection
    {
        return $this->ownedAccounts;
    }

    public function addOwnedAccount(Account $account): self
    {
        if (!$this->ownedAccounts->contains($account)) {
            $this->ownedAccounts->add($account);
            $account->setOwner($this);
        }

        return $this;
    }

    public function removeOwnedAccount(Account $account): self
    {
        if ($this->ownedAccounts->removeElement($account)) {
            // set the owning side to null (unless already changed)
            if ($account->getOwner() === $this) {
                $account->setOwner(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Account>
     */
    public function getCollaboratedAccounts(): Collection
    {
        return $this->collaboratedAccounts;
    }

    public function addCollaboratedAccount(Account $account): self
    {
        if (!$this->collaboratedAccounts->contains($account)) {
            $this->collaboratedAccounts->add($account);
            $account->setCollaborator($this);
        }

        return $this;
    }

    public function removeCollaboratedAccount(Account $account): self
    {
        if ($this->collaboratedAccounts->removeElement($account)) {
            // set the owning side to null (unless already changed)
            if ($account->getCollaborator() === $this) {
                $account->setCollaborator(null);
            }
        }

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials(): void
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }
}
