<?php

namespace App\Entity;

use App\Repository\NbpompeRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: NbpompeRepository::class)]
class Nbpompe
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $countPompe = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $dateNbpompe = null;

    #[ORM\ManyToOne(inversedBy: 'nbpompes')]
    private ?User $utilisateur = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCountPompe(): ?int
    {
        return $this->countPompe;
    }

    public function setCountPompe(int $countPompe): static
    {
        $this->countPompe = $countPompe;

        return $this;
    }

    public function getDateNbpompe(): ?\DateTimeImmutable
    {
        return $this->dateNbpompe;
    }

    public function setDateNbpompe(\DateTimeImmutable $dateNbpompe): static
    {
        $this->dateNbpompe = $dateNbpompe;

        return $this;
    }

    public function getUtilisateur(): ?User
    {
        return $this->utilisateur;
    }

    public function setUtilisateur(?User $utilisateur): static
    {
        $this->utilisateur = $utilisateur;

        return $this;
    }
}
