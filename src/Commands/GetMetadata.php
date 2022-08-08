<?php

namespace VideoMetadata\Commands;

use Exception;
use Spatie\TemporaryDirectory\TemporaryDirectory;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use VideoMetadata\Commands\Traits\WithFfmpeg;

class GetMetadata extends Command
{
    use WithFfmpeg;

    protected static $defaultName = 'vm:get-metadata';

    protected static $defaultDescription = 'Get a Video Metadata.';

    protected function configure(): void
    {
        $this->setHelp(self::$defaultDescription)
            ->setDescription(self::$defaultDescription)
            ->setDefinition([
                new InputOption(
                    name: 'video',
                    shortcut: null,
                    mode: InputOption::VALUE_REQUIRED,
                    description: 'Set the video full path',
                )
            ]);
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $video = $input->getOption('video');
        $videoInfo = pathinfo($video);

        $io->info('Kanata - Video Metadata');

        try {
            $metadata = $this->getMetadata($video);
        } catch (Exception $e) {
            $io->error('Failed to get metadata due to: ' . $e->getMessage());
            return Command::FAILURE;
        }

        $io->comment('Video: ' . array_get($videoInfo, 'basename'));
        $io->horizontalTable(
            headers: array_keys($metadata),
            rows: [$metadata],
        );

        return Command::SUCCESS;
    }
}
