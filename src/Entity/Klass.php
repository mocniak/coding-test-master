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
    private ?ClassRating $rating;

    public function __construct(string $topic, \DateTimeImmutable $startsAt)
    {
        $this->students = new ArrayCollection();
        $this->rating = null;
        $this->topic = $topic;
        $this->startsAt = $startsAt;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function startsAt(): DateTimeInterface
    {
        return $this->startsAt;
    }

    public function changeStartsAt(DateTimeInterface $startsAt): self
    {
        $this->startsAt = $startsAt;

        return $this;
    }

    public function topic(): string
    {
        return $this->topic;
    }

    public function changeTopic(string $topic): self
    {
        $this->topic = $topic;

        return $this;
    }

    public function enroll(User $user): self
    {
        $this->students->add($user);

        return $this;
    }

    public function removeStudent(User $user): self
    {
        $this->students->removeElement($user);

        return $this;
    }

    public function rating(): ?ClassRating
    {
        return $this->rating;
    }
}
