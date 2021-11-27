<?php

namespace App\Entity;

use App\Repository\BudgetRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=BudgetRepository::class)
 */
class Budget
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\OneToOne(targetEntity=Category::class, cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $category;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $movement;

    /**
     * @ORM\Column(type="float")
     */
    private $plannedAmount;

    /**
     * @ORM\Column(type="float")
     */
    private $deadlineNumber;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $deadlineWord;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCategory(): ?Category
    {
        return $this->category;
    }

    public function setCategory(Category $category): self
    {
        $this->category = $category;

        return $this;
    }

    public function getMovement(): ?string
    {
        return $this->movement;
    }

    public function setMovement(string $movement): self
    {
        $this->movement = $movement;

        return $this;
    }

    public function getPlannedAmount(): ?float
    {
        return $this->plannedAmount;
    }

    public function setPlannedAmount(float $plannedAmount): self
    {
        $this->plannedAmount = $plannedAmount;

        return $this;
    }

    public function getDeadlineNumber(): ?float
    {
        return $this->deadlineNumber;
    }

    public function setDeadlineNumber(float $deadlineNumber): self
    {
        $this->deadlineNumber = $deadlineNumber;

        return $this;
    }

    public function getDeadlineWord(): ?string
    {
        return $this->deadlineWord;
    }

    public function setDeadlineWord(string $deadlineWord): self
    {
        $this->deadlineWord = $deadlineWord;

        return $this;
    }
}
