<?php
/**
 * User: matteo
 * Date: 03/11/13
 * Time: 23.31
 * Just for fun...
 */

namespace CypressLab\GitWalrus\Event;

use CypressLab\GitWalrus\Application;
use GitElephant\Objects\Branch;
use GitElephant\Objects\Commit;
use GitElephant\Objects\Object;
use JMS\Serializer\EventDispatcher\EventSubscriberInterface;
use JMS\Serializer\EventDispatcher\ObjectEvent;
use JMS\Serializer\EventDispatcher\PreSerializeEvent;

class SerializerSubscriber implements EventSubscriberInterface
{
    /**
     * @var \CypressLab\GitWalrus\Application
     */
    private $app;

    public function __construct(Application $app)
    {
        $this->app = $app;
    }

    /**
     * Returns the events to which this class has subscribed.
     *
     * Return format:
     *     [
     *         ['event' => 'the-event-name', 'method' => 'onEventName', 'class' => 'some-class', 'format' => 'json'],
     *         [...],
     *     ]
     *
     * The class may be omitted if the class wants to subscribe to events of all classes.
     * Same goes for the format key.
     *
     * @return array
     */
    public static function getSubscribedEvents()
    {
        return [
            [
                'event' => 'serializer.post_serialize',
                'method' => 'onPostSerializeObject',
                'class' => 'GitElephant\Objects\Object'
            ],
            [
                'event' => 'serializer.post_serialize',
                'method' => 'onPostSerializeTree',
                'class' => 'GitElephant\Objects\Tree'
            ],
            [
                'event' => 'serializer.post_serialize',
                'method' => 'onPostSerializeBranch',
                'class' => 'GitElephant\Objects\Branch'
            ],
            [
                'event' => 'serializer.post_serialize',
                'method' => 'onPostSerializeCommit',
                'class' => 'GitElephant\Objects\Commit'
            ]
        ];
    }

    /**
     * @param ObjectEvent $event
     */
    public function onPostSerializeObject(ObjectEvent $event)
    {
        /** @var \GitElephant\Objects\Object $object */
        $object = $event->getObject();
        $link = $this->app->url('tree_object', ['ref' => 'master', 'path' => $object->getFullPath()]);
        $event->getVisitor()->addData('url', $link);
    }

    /**
     * @param ObjectEvent $event
     */
    public function onPostSerializeTree(ObjectEvent $event)
    {
        /** @var \GitElephant\Objects\Tree $tree */
        $tree = $event->getObject();
        $event->getVisitor()->addData('root', $tree->isRoot());
        if ($tree->isBlob()) {
            $event->getVisitor()->addData('binary_data', $tree->getBinaryData());
        }
    }

    /**
     * @param ObjectEvent $event
     */
    public function onPostSerializeBranch(ObjectEvent $event)
    {
        /** @var Branch $branch */
        $branch = $event->getObject();
        $branchUrl = $this->app->url('branch', ['name' => $branch->getName()]);
        $event->getVisitor()->addData('url', $branchUrl);
    }

    /**
     * @param ObjectEvent $event
     */
    public function onPostSerializeCommit(ObjectEvent $event)
    {
        /** @var Commit $commit */
        $commit = $event->getObject();
        $commitUrl = $this->app->url('commit', ['sha' => $commit->getSha()]);
        $event->getVisitor()->addData('url', $commitUrl);
    }
}
