<?php

namespace App\Entity;

use App\Repository\TransactionRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=TransactionRepository::class)
 */
class Transaction
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="date")
     */
    private $bankDate;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="float")
     */
    private $amount;

    /**
     * @ORM\Column(type="float")
     */
    private $balance;

    /**
     * @ORM\ManyToOne(targetEntity=Account::class, inversedBy="transactions")
     * @ORM\JoinColumn(nullable=false)
     */
    private $account;

    /**
     * @ORM\OneToMany(targetEntity=TransactionSplitting::class, mappedBy="transaction")
     */
    private $transactionSplittings;

    public function __construct()
    {
        $this->transactionSplittings = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

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

    public function getBalance(): ?float
    {
        return $this->balance;
    }

    public function setBalance(float $balance): self
    {
        $this->balance = $balance;

        return $this;
    }

    public function getAccount(): ?Account
    {
        return $this->account;
    }

    public function setAccount(?Account $account): self
    {
        $this->account = $account;

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
            $transactionSplitting->setTransaction($this);
        }

        return $this;
    }

    public function removeTransactionSplitting(TransactionSplitting $transactionSplitting): self
    {
        if ($this->transactionSplittings->removeElement($transactionSplitting)) {
            // set the owning side to null (unless already changed)
            if ($transactionSplitting->getTransaction() === $this) {
                $transactionSplitting->setTransaction(null);
            }
        }

        return $this;
    }
}
