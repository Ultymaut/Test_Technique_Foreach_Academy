<?php

namespace App\Entity;

use App\Repository\ExcuseRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

#[ORM\Entity(repositoryClass: ExcuseRepository::class)]
#[UniqueEntity('http_code')]
class Excuse
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $http_code = null;

    #[ORM\Column(length: 1000)]
    private ?string $tag = null;

    #[ORM\Column(length: 1000)]
    private ?string $message = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getHttpCode(): ?int
    {
        return $this->http_code;
    }

    public function setHttpCode(int $http_code): self
    {
        $this->http_code = $http_code;

        return $this;
    }

    public function getTag(): ?string
    {
        return $this->tag;
    }

    public function setTag(string $tag): self
    {
        $this->tag = $tag;

        return $this;
    }

    public function getMessage(): ?string
    {
        return $this->message;
    }

    public function setMessage(string $message): self
    {
        $this->message = $message;

        return $this;
    }

}
