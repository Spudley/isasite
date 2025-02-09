<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\TransactionRepository;
use App\Entity\Enum\Status;

#[ORM\Entity(repositoryClass: TransactionRepository::class)]
#[ORM\Table(name: 'transactions')]
class Transaction
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    #[ORM\Column(type: 'string', length: 255, unique: true)]
    private ?string $ourTransactionId = null;

    #[ORM\Column(type: 'string', length: 255, unique: true)]
    private ?string $theirTransactionId = null;

    #[ORM\Column(type: 'string', length: 255, unique: true)]
    private ?string $fundName = null;

    #[ORM\Column(type: 'integer')]
    private ?int $units = null;

    #[ORM\Column(type: 'integer')]
    private ?int $pencePerUnit = null;

    #[ORM\Column(type: 'integer')]
    private ?int $status = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getOurTransactionId(): ?string
    {
        return $this->ourTransactionId;
    }

    public function setOurTransactionId(string $ourTransactionId): static
    {
        $this->ourTransactionId = $ourTransactionId;

        return $this;
    }

    public function getTheirTransactionId(): ?string
    {
        return $this->theirTransactionId;
    }

    public function setTheirTransactionId(string $theirTransactionId): static
    {
        $this->theirTransactionId = $theirTransactionId;

        return $this;
    }

    public function getFundName(): ?string
    {
        return $this->fundName;
    }

    public function setFundName(string $fundName): static
    {
        $this->fundName = $fundName;

        return $this;
    }

    public function getUnits(): ?int
    {
        return $this->units;
    }

    public function setUnits(int $units): static
    {
        $this->units = $units;

        return $this;
    }

    public function getPencePerUnit(): ?int
    {
        return $this->pencePerUnit;
    }

    public function setPencePerUnit(int $pencePerUnit): static
    {
        $this->pencePerUnit = $pencePerUnit;

        return $this;
    }

    public function getStatus(): ?Status
    {
        return Status::from($this->status);
    }

    public function setStatus(Status $status): static
    {
        $this->status = $status->value;

        return $this;
    }
}
