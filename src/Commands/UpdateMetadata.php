<?php

namespace VideoMetadata\Commands;

use Exception;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use VideoMetadata\Commands\Traits\WithAtomicParsley;

class UpdateMetadata extends Command
{
    use WithAtomicParsley;

    protected static $defaultName = 'vm:update-metadata';

    protected static $defaultDescription = 'Update a Video Metadata.';

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
                ),
                new InputOption(
                    name: 'field',
                    shortcut: null,
                    mode: InputOption::VALUE_REQUIRED,
                    description: 'Set the video metadata field',
                ),
                new InputOption(
                    name: 'value',
                    shortcut: null,
                    mode: InputOption::VALUE_REQUIRED,
                    description: 'Set the video metadata value',
                ),
            ]);
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $video = $input->getOption('video');
        $field = $input->getOption('field');
        $value = $input->getOption('value');
        $videoInfo = pathinfo($video);

        $io->info('Kanata - Video Metadata');

        try {
            $result = $this->updateMetadata($video, $field, $value);
        } catch (Exception $e) {
            $io->error('Failed to update metadata due to: ' . $e->getMessage());
            return Command::FAILURE;
        }

        $io->comment('Video: ' . array_get($videoInfo, 'basename'));
        if ($result) {
            $io->info('Video metadata updated successfully.');
            return Command::SUCCESS;
        }

        $io->error('Video metadata not updated.');
        return Command::FAILURE;
    }
}
