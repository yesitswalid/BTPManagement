<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;


/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 * @UniqueEntity("matricule", message="Cette matricule existe déjà.")
 */
class User
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $nom;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $prenom;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $matricule;

    /**
     * @ORM\OneToMany(targetEntity=Pointage::class, mappedBy="utilisateur", orphanRemoval=true)
     */
    private $pointage;

    public function __construct()
    {
        $this->pointage = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(string $prenom): self
    {
        $this->prenom = $prenom;

        return $this;
    }

    public function getMatricule(): ?string
    {
        return $this->matricule;
    }

    public function setMatricule(string $matricule): self
    {
        $this->matricule = $matricule;

        return $this;
    }

    /**
     * @return Collection<int, Pointage>
     */
    public function getPointage(): Collection
    {
        return $this->pointage;
    }

    public function addPointage(Pointage $pointage): self
    {
        if (!$this->pointage->contains($pointage)) {
            $this->pointage[] = $pointage;
            $pointage->setUtilisateur($this);
        }
        return $this;
    }

    public function removeChantier(Pointage $pointage): self
    {
        if ($this->pointage->removeElement($pointage)) {
            // set the owning side to null (unless already changed)
            if ($pointage->getUtilisateur() === $this) {
                $pointage->setUtilisateur(null);
            }
        }
        return $this;
    }

    /**
     * @param $year
     * @param $week
     * @return int
     */
    public function calculateTotalDurationForWeek($year, $week): int
    {
        // Récupérer les pointages de l'utilisateur pour la semaine donnée
        return array_sum(array_map(function (Pointage $pointage) use($year, $week)
        {
            $pointageYear = $pointage->getDate()->format('Y');
            $pointageWeek = $pointage->getDate()->format('W');
            return ($pointageYear == $year && $pointageWeek == $week) ? $pointage->getDuree() : 0;
        }, $this->pointage->toArray()));
    }

    public function __toString(): string
    {
        return $this->nom . " " . $this->prenom;
    }
}
