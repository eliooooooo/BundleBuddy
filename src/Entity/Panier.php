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

    #[ORM\ManyToMany(targetEntity: User::class, mappedBy: 'Panier')]
    private Collection $users;

    public function __construct()
    {
        $this->package = new ArrayCollection();
        $this->users = new ArrayCollection();
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

    /**
     * @return Collection<int, User>
     */
    public function getUsers(): Collection
    {
        return $this->users;
    }

    public function addUser(User $user): static
    {
        if (!$this->users->contains($user)) {
            $this->users->add($user);
            $user->addPanier($this);
        }

        return $this;
    }

    public function removeUser(User $user): static
    {
        if ($this->users->removeElement($user)) {
            $user->removePanier($this);
        }

        return $this;
    }
}
