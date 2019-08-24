<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\CommitRepository")
 */
class Commit
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $repository;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $branch;

    /**
     * @ORM\Column(type="integer")
     */
    private $commits;

    /**
     * @ORM\Column(type="datetime")
     */
    private $date;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getRepository(): ?string
    {
        return $this->repository;
    }

    public function setRepository(?string $repository): self
    {
        $this->repository = $repository;

        return $this;
    }

    public function getBranch(): ?string
    {
        return $this->branch;
    }

    public function setBranch(string $branch): self
    {
        $this->branch = $branch;

        return $this;
    }

    public function getCommits(): ?int
    {
        return $this->commits;
    }

    public function setCommits(int $commits): self
    {
        $this->commits = $commits;

        return $this;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): self
    {
        $this->date = $date;

        return $this;
    }
}
