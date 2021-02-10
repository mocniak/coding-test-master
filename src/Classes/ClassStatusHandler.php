<?php


namespace App\Classes;

use App\Entity\Klass;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Events;
use Doctrine\Persistence\Event\LifecycleEventArgs;

/**
 * Sets the correct class status based on bookings and cancellations
 */
class ClassStatusHandler implements EventSubscriber
{
    const SCHEDULED = 'scheduled';
    const BOOKED = 'booked';
    const CANCELLED = 'cancelled';
    const FULL = 'full';

    public function updateClassStatus(Klass $class): void
    {
        $class->setStatus($this->getStatus($class));
    }

    private function getStatus(Klass $class): string
    {
        if ($class->getStudents()->count()) {
            if ($class->getStudents()->count() >= 4) {
                return self::FULL;
            }
            
            return self::BOOKED;
        } else {
            if ($class->getStartsAt()->diff(new DateTime())->days < 2) {
                return self::CANCELLED;
            }

            return self::SCHEDULED;
        }
    }

    public function getSubscribedEvents()
    {
        return [Events::prePersist, Events::preUpdate];
    }

    public function prePersist(LifecycleEventArgs $args): void
    {
        $class = $args->getObject();
        if (!$class instanceof Klass) {
            return;
        }

        $this->updateClassStatus($class);
    }

    public function preUpdate(LifecycleEventArgs $args): void
    {
        $class = $args->getObject();
        if (!$class instanceof Klass) {
            return;
        }

        $this->updateClassStatus($class);
    }
}
