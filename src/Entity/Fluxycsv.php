<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\FluxycsvRepository")
 */
class Fluxycsv
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="Please, upload the csv file.")
     * @Assert\File(mimeTypes={ "application/csv" })
     * 
     */
    private $name;

    /**
     * @ORM\Column(type="array", nullable=false)
     */
    private $actualname;

    /**
     * @ORM\Column(type="array", nullable=true)
     */
    private $csvkeys;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $nblines;

    /**
     * @ORM\Column(type="boolean")
     */
    private $uploaded;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $path;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $filesize;


    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $tablename;

    /**
    * @ORM\Column(type="string", nullable=true)
    */
    private $created;

    /**
    *
    * 
    * @ORM\Column(type="string", nullable=true)
    */
    private $updated;


    public function getId()
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


    public function getActualName(): ?string
    {
        return $this->actualname;
    }

    public function setActualName(string $actualname): self
    {
        $this->actualname = $actualname;

        return $this;
    }


    public function getCsvkeys(): ?array
    {
        return $this->csvkeys;
    }

    public function setCsvkeys(?array $csvkeys): self
    {
        $this->csvkeys = $csvkeys;

        return $this;
    }

    public function getNblines(): ?int
    {
        return $this->nblines;
    }

    public function setNblines(?int $nblines): self
    {
        $this->nblines = $nblines;

        return $this;
    }

    public function getUploaded(): ?bool
    {
        return $this->uploaded;
    }

    public function setUploaded(bool $uploaded): self
    {
        $this->uploaded = $uploaded;

        return $this;
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

    public function getFilesize(): ?string
    {
        return $this->filesize;
    }

    public function setFilesize(?string $filesize): self
    {
        $this->filesize = $filesize;

        return $this;
    }

    public function getTablename(): ?string
    {
        return $this->tablename;
    }

    public function setTablename(?string $tablename): self
    {
        $this->tablename = $tablename;

        return $this;
    }
 
    public function getCreated(): ?string
    {
        return $this->created;
    }

    public function setCreated($created): self
    {
        $this->created = $created;

        return $this;
    }

    public function getUpdated(): ?string
    {
        return $this->updated;
    }

    public function setUpdated($updated): self
    {
        $this->updated = $updated;

        return $this;
    }
 



}
