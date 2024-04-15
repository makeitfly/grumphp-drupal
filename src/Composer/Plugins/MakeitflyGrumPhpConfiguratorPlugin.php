<?php

namespace Makeitfly\GrumPhpDrupal\Composer\Plugins;

use Composer\Composer;
use Composer\EventDispatcher\EventSubscriberInterface;
use Composer\IO\IOInterface;
use Composer\Plugin\PluginInterface;

class MakeitflyGrumPhpConfiguratorPlugin implements PluginInterface, EventSubscriberInterface
{
    /**
     * @var Composer
     */
    private $composer;

    /**
     * @var
     */
    private $io;

    /**
     * Apply plugin modifications to Composer
     *
     * @param Composer $composer
     * @param IOInterface $io
     */
    public function activate(Composer $composer, IOInterface $io)
    {
        $this->composer = $composer;
        $this->io = $io;
    }

    public static function getSubscribedEvents()
    {
        return [
            'post-package-install' => 'configureGrumPhp',
            'post-package-update' => 'configureGrumPhp',
        ];
    }

    public function configureGrumPhp()
    {
        $configFiles = [
            '/../../../grumphp.yml.dist' => './grumphp.yml.dist',
            '/../../../phpstan.neon' => './phpstan.neon'
        ];

        foreach ($configFiles as $source => $destination) {
            if (file_exists($destination)) {
                continue;
            }

            $this->io->write('<fg=green>Copying configuration file...</fg=green>');
            if (!copy(__DIR__ . $source, $destination)) {
                $this->io->write('<fg=red>Copying config failed!</fg=red>');
                continue;
            }

            $this->io->write('<fg=green>Copying config success!</fg=green>');
        }
    }

    public function deactivate(Composer $composer, IOInterface $io): void
    {
    }

    public function uninstall(Composer $composer, IOInterface $io): void
    {
    }
}
