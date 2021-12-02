<?php

namespace App\Entity;

use App\Repository\CategoryRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity(repositoryClass=CategoryRepository::class)
 * @UniqueEntity(
 *      fields={"name"},
 *      message="Cette catégorie a déjà été créée"
 * )
 */
class Category
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, unique=true)
     */
    private $name;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isActive;

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    private $unactiveDate;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $type;

    /**
     * @ORM\Column(type="string", length=7)
     */
    private $color;

    /**
     * @ORM\OneToMany(targetEntity=TransactionSplitting::class, mappedBy="category")
     */
    private $transactionSplittings;

    /**
     * @ORM\OneToMany(targetEntity=TransactionSplitting::class, mappedBy="recurringCategory")
     */
    private $recurringTransactionSplittings;

    public function __construct()
    {
        $this->transactionSplittings = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getIsActive(): ?bool
    {
        return $this->isActive;
    }

    public function setIsActive(bool $isActive): self
    {
        $this->isActive = $isActive;

        return $this;
    }

    public function getUnactiveDate(): ?\DateTimeInterface
    {
        return $this->unactiveDate;
    }

    public function setUnactiveDate(?\DateTimeInterface $unactiveDate): self
    {
        $this->unactiveDate = $unactiveDate;

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

    public function getColor(): ?string
    {
        return $this->color;
    }

    public function setColor(string $color): self
    {
        $this->color = $color;

        return $this;
    }

    /**
     * @return Collection|TransactionSplitting[]
     */
    public function getTransactionSplittings(): Collection
    {
        return $this->transactionSplittings;
    }

    public function addTransactionSplitting(TransactionSplitting $transactionSplitting): self
    {
        if (!$this->transactionSplittings->contains($transactionSplitting)) {
            $this->transactionSplittings[] = $transactionSplitting;
            $transactionSplitting->setCategory($this);
        }

        return $this;
    }

    public function removeTransactionSplitting(TransactionSplitting $transactionSplitting): self
    {
        if ($this->transactionSplittings->removeElement($transactionSplitting)) {
            // set the owning side to null (unless already changed)
            if ($transactionSplitting->getCategory() === $this) {
                $transactionSplitting->setCategory(null);
            }
        }

        return $this;
    }
}
