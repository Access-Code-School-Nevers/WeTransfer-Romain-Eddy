<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\FileTransferRepository")
 */
class FileTransfer
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $mailFrom;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $nameFrom;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $mailTo;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $nameTo;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $fileName;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMailFrom(): ?string
    {
        return $this->mailFrom;
    }

    public function setMailFrom(string $mailFrom): self
    {
        $this->mailFrom = $mailFrom;

        return $this;
    }

    public function getNameFrom(): ?string
    {
        return $this->nameFrom;
    }

    public function setNameFrom(string $nameFrom): self
    {
        $this->nameFrom = $nameFrom;

        return $this;
    }

    public function getMailTo(): ?string
    {
        return $this->mailTo;
    }

    public function setMailTo(string $mailTo): self
    {
        $this->mailTo = $mailTo;

        return $this;
    }

    public function getNameTo(): ?string
    {
        return $this->nameTo;
    }

    public function setNameTo(string $nameTo): self
    {
        $this->nameTo = $nameTo;

        return $this;
    }

    public function getFileName(): ?string
    {
        return $this->fileName;
    }

    public function setFileName(string $fileName): self
    {
        $this->fileName = $fileName;

        return $this;
    }
}
