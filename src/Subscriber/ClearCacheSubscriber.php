<?php
/**
 * Created by Viktor Kostadinov (viktor.kostadinov@gmail.com)
 * Date: 09/06/2020
 * Time: 11:01 am
 */

namespace App\Subscriber;

use App\Entity\Area;
use App\Entity\Country;
use App\Entity\Instruction;
use App\Entity\InstructionContent;
use App\Entity\Question;
use App\Entity\QuestionLabel;
use App\Entity\State;
use App\Entity\ZipcodePartial;
use App\Helper\CloudCache;
use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Events;
use Doctrine\Persistence\Event\LifecycleEventArgs;

class ClearCacheSubscriber implements EventSubscriber
{
    /**
     * @var CloudCache
     */
    private $cloudCache;

    public function __construct(CloudCache $cloudCache)
    {
        $this->cloudCache = $cloudCache;
    }

    public function getSubscribedEvents()
    {
        return [
            Events::postPersist,
            Events::postRemove,
            Events::postUpdate,
        ];
    }

    public function postPersist(LifecycleEventArgs $args)
    {
        $this->removeCache($args);
    }

    public function postRemove(LifecycleEventArgs $args)
    {
        $this->removeCache($args);
    }

    public function postUpdate(LifecycleEventArgs $args)
    {
        $this->removeCache($args);
    }

    private function removeCache(LifecycleEventArgs $args)
    {
        $entity = $args->getObject();
        if ($entity instanceof Question || $entity instanceof QuestionLabel) {
            $this->cloudCache->clearCache(CloudCache::CACHE_KEY_QUESTIONS);
        }
        // The Instruction cache has ttl=1hr so it does not have to be cleared manually
    }
}