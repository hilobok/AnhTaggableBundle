<?php

namespace Anh\Bundle\TaggableBundle;

use Anh\Taggable\TaggableManager;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Event\PreFlushEventArgs;
use Doctrine\ORM\Event\OnClearEventArgs;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Anh\Taggable\TaggableSubscriber as BaseTaggableSubscriber;

/**
 * Wrapper for original subscriber to prevent ServiceCircularReferenceException
 * which thrown as subscriber depends on taggablemanager which depends
 * on default connection which depends on subscriber.
 * Feel free to contribute if you have more elegant solution.
 */
class TaggableSubscriber extends BaseTaggableSubscriber
{
    /**
     * Holds container
     * @var ContainerInterface
     */
    protected $container;

    /**
     * Constructor
     *
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    /**
     * Gets manager from DI container
     */
    private function createManager()
    {
        if ($this->manager === null) {
            $this->manager = $this->container->get('anh_taggable.manager');
        }
    }

    /**
     * {@inheritdoc}
     */
    public function postLoad(LifecycleEventArgs $args)
    {
        $this->createManager();
        parent::postLoad($args);
    }

    /**
     * {@inheritdoc}
     */
    public function postPersist(LifecycleEventArgs $args)
    {
        $this->createManager();
        parent::postPersist($args);
    }

    /**
     * {@inheritdoc}
     */
    public function preFlush(PreFlushEventArgs $args)
    {
        $this->createManager();
        parent::preFlush($args);
    }

    /**
     * {@inheritdoc}
     */
    public function preRemove(LifecycleEventArgs $args)
    {
        $this->createManager();
        parent::preRemove($args);
    }

    /**
     * {@inheritdoc}
     */
    public function onClear(OnClearEventArgs $args)
    {
        $this->createManager();
        parent::onClear($args);
    }
}
