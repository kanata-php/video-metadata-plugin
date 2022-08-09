<?php

namespace VideoMetadata\Commands\Traits;

use Exception;
use Symfony\Component\Process\ExecutableFinder;
use Symfony\Component\Process\Process;

trait WithAtomicParsley
{
    /**
     * @param string $video
     * @param string $field
     * @param string $value
     * @return bool
     * @throws Exception
     */
    public function updateMetadata(string $video, string $field, string $value): bool
    {
        $executableFinder = new ExecutableFinder();
        $atomicParsley = $executableFinder->find('AtomicParsley');

        if (null === $atomicParsley) {
            throw new Exception('System is missing AtomicParsley!');
        }

        $command = [
            $atomicParsley,
            $video,
            '--' . $field,
            $value,
            '--overWrite',
        ];
        $process = new Process(
            command: $command,
            timeout: null
        );
        $process->start();

        $error = false;
        logger()->info('Executing Video Metadata Update...');
        foreach ($process as $type => $data) {
            if ($process::OUT === $type) {
                logger()->info($data);
            } else { // $process::ERR === $type
                $error = true;
                logger()->error($data);
            }
        }
        logger()->info('Video Metadata Update executed.');

        if ($error) {
            return false;
        }

        return true;
    }
}