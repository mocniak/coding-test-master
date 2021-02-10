<?php


namespace App\Entity;

use App\Classes\ClassStatusHandler;
use DateTimeInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\KlassRepository;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=KlassRepository::class)
 * @ORM\Table
 */
class Klass
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private int $id;

    /**
     * @ORM\Column(type="datetime")
     */
    private DateTimeInterface $startsAt;

    /**
     * @ORM\Column(type="string")
     */
    private string $status = ClassStatusHandler::SCHEDULED;

    /**
     * @ORM\Column(type="string")
     */
    private string $topic;

    /**
     * @ORM\ManyToMany(targetEntity=User::class)
     */
    private Collection $students;

    /**
     * @ORM\OneToOne (targetEntity=ClassRating::class, mappedBy="class")
     */
    private ?ClassRating $rating = null;

    public function __construct()
    {
        $this->students = new ArrayCollection();
    }

    /**
     * @Groups("api")
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @Groups("api")
     */
    public function getStartsAt(): DateTimeInterface
    {
        return $this->startsAt;
    }

    public function setStartsAt(DateTimeInterface $startsAt): self
    {
        $this->startsAt = $startsAt;

        return $this;
    }

    /**
     * @Groups("api")
     */
    public function getStatus(): string
    {
        return $this->status;
    }

    public function setStatus(string $status): void
    {
        $this->status = $status;
    }

    /**
     * @Groups("api")
     */
    public function getTopic(): string
    {
        return $this->topic;
    }

    public function setTopic(string $topic): self
    {
        $this->topic = $topic;

        return $this;
    }

    /**
     * @Groups("api")
     */
    public function getStudents(): Collection
    {
        return $this->students;
    }

    public function addStudent(User $user): self
    {
        $this->students->add($user);

        return $this;
    }

    public function removeStudent(User $user): self
    {
        $this->students->removeElement($user);

        return $this;
    }

    /**
     * @Groups("api")
     */
    public function getRating(): ?ClassRating
    {
        return $this->rating;
    }
}
