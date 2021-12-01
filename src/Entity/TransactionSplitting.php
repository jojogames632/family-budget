<?php

namespace App\Entity;

use App\Repository\TransactionSplittingRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=TransactionSplittingRepository::class)
 */
class TransactionSplitting
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Transaction::class, inversedBy="transactionSplittings")
     * @ORM\JoinColumn(nullable=false)
     */
    private $transaction;

    /**
     * @ORM\ManyToOne(targetEntity=Category::class, inversedBy="transactionSplittings")
     * @ORM\JoinColumn(nullable=false)
     */
    private $category;

    /**
     * @ORM\Column(type="float")
     */
    private $amount;

    /**
     * @ORM\ManyToOne(targetEntity=Category::class, inversedBy="recurringTransactionSplittings")
     */
    private $recurringCategory;

    /**
     * @ORM\Column(type="datetime")
     */
    private $bankDate;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTransaction(): ?Transaction
    {
        return $this->transaction;
    }

    public function setTransaction(?Transaction $transaction): self
    {
        $this->transaction = $transaction;

        return $this;
    }

    public function getCategory(): ?Category
    {
        return $this->category;
    }

    public function setCategory(?Category $category): self
    {
        $this->category = $category;

        return $this;
    }

    public function getAmount(): ?float
    {
        return $this->amount;
    }

    public function setAmount(float $amount): self
    {
        $this->amount = $amount;

        return $this;
    }

    public function getRecurringCategory(): ?Category
    {
        return $this->recurringCategory;
    }

    public function setRecurringCategory(?Category $recurringCategory): self
    {
        $this->recurringCategory = $recurringCategory;

        return $this;
    }

    public function getBankDate(): ?\DateTimeInterface
    {
        return $this->bankDate;
    }

    public function setBankDate(\DateTimeInterface $bankDate): self
    {
        $this->bankDate = $bankDate;

        return $this;
    }
}
