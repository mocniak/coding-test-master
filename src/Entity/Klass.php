<?php

namespace App\Entity;

use App\Exception\StudentEnrolledToFullKlassException;
use App\Repository\KlassRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=KlassRepository::class)
 * @ORM\Table
 */
class Klass
{
    const SCHEDULED = 'scheduled';
    const BOOKED = 'booked';
    const CANCELLED = 'cancelled';
    const FULL = 'full';

    const MAXIMUM_CAPACITY = 4;
    const DURATION = 'PT1H'; // \DateInterval format
    const CANCELLATION_DEADLINE = 'P2D'; // \DateInterval format

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private int $id;

    /**
     * @ORM\Column(type="datetime_immutable")
     */
    private \DateTimeImmutable $startsAt;

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

    public function id(): int
    {
        return $this->id;
    }

    public function startsAt(): \DateTimeImmutable
    {
        return $this->startsAt;
    }

    public function changeStartsAt(\DateTimeImmutable $startsAt): self
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
        if (count($this->students) + 1 > self::MAXIMUM_CAPACITY) {
            throw new StudentEnrolledToFullKlassException();
        }

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

    /**
     * @return ArrayCollection|Collection
     */
    public function students()
    {
        return $this->students;
    }

    public function status(): string
    {
        if ($this->students()->count()) {
            if ($this->students()->count() >= 4) {
                return self::FULL;
            }

            return self::BOOKED;
        } else {
            if ($this->startsAt->sub(new \DateInterval(self::CANCELLATION_DEADLINE)) <= new \DateTimeImmutable()) {
                return self::CANCELLED;
            }

            return self::SCHEDULED;
        }
    }
}
