<?php

namespace App\Entity;

use App\Repository\AccountHistoryRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=AccountHistoryRepository::class)
 */
class AccountHistory
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="datetime")
     */
    private $lastDayDate;

    /**
     * @ORM\Column(type="float")
     */
    private $lastDayBalance;

    /**
     * @ORM\ManyToOne(targetEntity=Account::class, inversedBy="accountHistories")
     * @ORM\JoinColumn(nullable=false)
     */
    private $account;

    /**
     * @ORM\Column(type="float")
     */
    private $incomeAmount;

    /**
     * @ORM\Column(type="float")
     */
    private $spentAmount;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLastDayDate(): ?\DateTimeInterface
    {
        return $this->lastDayDate;
    }

    public function setLastDayDate(\DateTimeInterface $lastDayDate): self
    {
        $this->lastDayDate = $lastDayDate;

        return $this;
    }

    public function getLastDayBalance(): ?float
    {
        return $this->lastDayBalance;
    }

    public function setLastDayBalance(float $lastDayBalance): self
    {
        $this->lastDayBalance = $lastDayBalance;

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

    public function getIncomeAmount(): ?float
    {
        return $this->incomeAmount;
    }

    public function setIncomeAmount(float $incomeAmount): self
    {
        $this->incomeAmount = $incomeAmount;

        return $this;
    }

    public function getSpentAmount(): ?float
    {
        return $this->spentAmount;
    }

    public function setSpentAmount(float $spentAmount): self
    {
        $this->spentAmount = $spentAmount;

        return $this;
    }
}
