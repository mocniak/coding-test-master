<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity
 * @ORM\Table
 */
class ClassRating
{
    /**
     * @ORM\Id
     * @ORM\OneToOne(targetEntity=Klass::class, inversedBy="rating")
     */
    private Klass $class;

    /**
     * @ORM\Column(type="smallint")
     * @Assert\Range(min=1, max=5)
     */
    private int $rating;

    public function __construct(Klass $class, int $rating)
    {
        $this->class = $class;
        $this->rating = $rating;
    }

    /**
     * @Groups("api")
     */
    public function getClassId(): int
    {
        return $this->class->getId();
    }

    /**
     * @Groups("api")
     */
    public function getRating(): int
    {
        return $this->rating;
    }
}
