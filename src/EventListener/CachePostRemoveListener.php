<?php

/**
 * Created by PhpStorm.
 * User: SAM Johnny
 * Date: 05/05/2022
 * Time: 17:46
 */

namespace App\EventListener;

use App\Entity\User;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Psr\Cache\InvalidArgumentException;
use Symfony\Contracts\Cache\CacheInterface;

class CachePostRemoveListener
{

    private CacheInterface $cache;

    /**
     * @param CacheInterface $cache
     */
    public function __construct(CacheInterface $cache)
    {
        $this->cache = $cache;
    }


    /**
     * @throws InvalidArgumentException
     */
    public function postRemove(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();

        if ($entity instanceof User) {
            $this->cache->delete('user_item' . $entity->getId());
            $this->cache->delete('users_collection');
        }
    }
}