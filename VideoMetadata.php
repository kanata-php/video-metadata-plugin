<?php

use Kanata\Interfaces\KanataPluginInterface;
use Psr\Container\ContainerInterface;
use Kanata\Annotations\Plugin;
use Kanata\Annotations\Description;
use Kanata\Annotations\Author;
use VideoMetadata\Commands\GetMetadata;
use VideoMetadata\Commands\UpdateMetadata;

/**
 * @Plugin(name="VideoMetadata")
 * @Description(value="Manage video metadata")
 * @Author(name="Savio Resende",email="savio@savioresende.com.br")
 */

class VideoMetadata implements KanataPluginInterface
{
    protected ContainerInterface $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    /**
     * @return void
     */
    public function start(): void
    {
        add_filter('commands', function($app) {
            $app->add(new GetMetadata);
            $app->add(new UpdateMetadata);
            return $app;
        });
    }
}
