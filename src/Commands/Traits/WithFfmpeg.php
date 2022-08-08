<?php

namespace VideoMetadata\Commands\Traits;

use Exception;
use League\Flysystem\Adapter\Local;
use League\Flysystem\Filesystem;
use Spatie\TemporaryDirectory\TemporaryDirectory;
use Symfony\Component\Process\ExecutableFinder;
use Symfony\Component\Process\Process;

trait WithFfmpeg
{
    /**
     * @param string $video
     * @return array|null
     * @throws Exception
     */
    public function getMetadata(string $video): ?array
    {
        $executableFinder = new ExecutableFinder();
        $ffmpegPath = $executableFinder->find('ffmpeg');

        if (null === $ffmpegPath) {
            throw new Exception('System is missing ffmpeg!');
        }

        // step 1: temp file
        $tempDir = (new TemporaryDirectory)->create();
        $tempDir->location(ROOT_FOLDER . '/temp');
        $metadataFile = $tempDir->path('metadata.txt');

        // step 2: run command
        $process = new Process([
            $ffmpegPath,
            '-i',
            $video,
            '-f',
            'ffmetadata',
            $metadataFile,
        ]);
        $process->run();

        if (!$process->isSuccessful()) {
            $tempDir->delete();
            throw new Exception('Error while getting video metadata: ' . PHP_EOL . $process->getErrorOutput());
        }

        // step 3: get metadata
        $metadataInfo = pathinfo($metadataFile);
        $adapter = new Local(array_get($metadataInfo, 'dirname'));
        $filesystem = new Filesystem($adapter);
        $metadata = $filesystem->read(array_get($metadataInfo, 'basename'));

        $tempDir->delete();

        return $this->processMetadata($metadata);
    }

    private function processMetadata(string $metadata): array
    {
        $itemsToAvoid = [';', '#'];
        $result = [];

        $exploded = explode(PHP_EOL, $metadata);

        foreach ($exploded as $row) {
            if (in_array(substr($row, 0, 1), $itemsToAvoid)) {
                continue;
            }

            $explodedRow = explode('=', $row);

            if (null === $explodedRow || !isset($explodedRow[0]) || !isset($explodedRow[1])) {
                continue;
            }

            $result[$explodedRow[0]] = $explodedRow[1];
        }

        return $result;
    }
}