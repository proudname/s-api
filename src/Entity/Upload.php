<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UploadRepository")
 */
class Upload
{

    public function __construct()
    {
        $this->isComplete = 0;
    }

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $path;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $param1;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $param2;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $param3;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $status;

    /**
     * @ORM\Column(type="integer")
     */
    private $isComplete;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPath(): ?string
    {
        return $this->path;
    }

    public function setPath(?string $path): self
    {
        $this->path = $path;

        return $this;
    }

    public function getParam1(): ?string
    {
        return $this->param1;
    }

    public function setParam1(?string $param1): self
    {
        $this->param1 = $param1;

        return $this;
    }

    public function getParam2(): ?string
    {
        return $this->param2;
    }

    public function setParam2(?string $param2): self
    {
        $this->param2 = $param2;

        return $this;
    }

    public function getParam3(): ?string
    {
        return $this->param3;
    }

    public function setParam3(?string $param3): self
    {
        $this->param3 = $param3;

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function getIsComplete()
    {
        return $this->isComplete;
    }

    public function setIsComplete($isComplete): self
    {
        $this->isComplete = $isComplete;

        return $this;
    }
}
