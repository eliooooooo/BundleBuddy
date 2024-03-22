<?php

namespace App\Entity;

use App\Repository\PanierRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PanierRepository::class)]
class Panier
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToMany(targetEntity: Package::class, inversedBy: 'paniers')]
    private Collection $package;

    #[ORM\OneToOne(targetEntity: User::class, inversedBy: 'panier', cascade: ['persist', 'remove'])]
    private $user;

    #[ORM\OneToOne(cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $User = null;

    public function __construct()
    {
        $this->package = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return Collection<int, Package>
     */
    public function getPackage(): Collection
    {
        return $this->package;
    }

    public function addPackage(Package $package): static
    {
        if (!$this->package->contains($package)) {
            $this->package->add($package);
        }

        return $this;
    }

    public function removePackage(Package $package): static
    {
        $this->package->removeElement($package);

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(User $user): self
    {
        $this->user = $user;

        // set the owning side of the relation if necessary
        if ($user->getPanier() !== $this) {
            $user->setPanier($this);
        }

        return $this;
    }
}
