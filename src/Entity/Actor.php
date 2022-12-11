<?php

namespace App\Entity;

use DateTime;
use App\Entity\Program;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\ActorRepository;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\HttpFoundation\File\File;
use Doctrine\Common\Collections\ArrayCollection;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: ActorRepository::class)]
#[Vich\Uploadable] 
class Actor
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\ManyToMany(targetEntity: Program::class, inversedBy: 'actors')]
    private Collection $programs;

    #[ORM\Column(length: 255, nullable: true)]
    #[Assert\Length(max:255)]
    private ?string $poster = null;

    #[ORM\Column(length: 255)]
    private ?string $slug;

    #[Vich\UploadableField(mapping: 'actor_file', fileNameProperty: 'poster')]
    #[Assert\File(
        maxSize: '1M',
        mimeTypes: ['image/jpg', 'image/jpeg', 'image.png', 'image/webp'],
    )]
    private ?File $actorFile = null;

    public function __construct()
    {
        $this->programs = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }
    public function getPoster(): ?string
    {
        return $this->poster;
    }
    public function setPoster(string $poster): void
    {
        $this->poster = $poster;
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

    /**
     * @return Collection<int, Program>
     */
    public function getPrograms(): Collection
    {
        return $this->programs;
    }

    public function addProgram(Program $program): self
    {
        if (!$this->programs->contains($program)) {
            $this->programs->add($program);
        }

        return $this;
    }

    public function removeProgram(Program $program): self
    {
        $this->programs->removeElement($program);

        return $this;
    }
    public function setActorFile(File $image = null): Actor
    {
        $this->actorFile = $image;
        if($image) {
            $this->updatededAt = new DateTime('now');
        }
        return $this;
    }

    public function getActorFile(): ?File
    {
        return $this->actorFile;
    }
    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): self
    {
        $this->slug = $slug;

        return $this;
    }
}
