<?php

namespace Spatie\Visit\Colorizers;

use Symfony\Component\Process\ExecutableFinder;
use Symfony\Component\Process\Process;

class JsonColorizer extends Colorizer
{
    public function canColorize(string $contentType): bool
    {
        if (! parent::canColorize($contentType)) {
            return false;
        }

        return $contentType === 'application/json';
    }

    public function getColorizerToolName(): string
    {
        return 'jq';
    }

    public function colorize(string $content): string
    {
        $file = tmpfile();
        $path = stream_get_meta_data($file)['uri'];
        file_put_contents($path, $content);

        $process = Process::fromShellCommandline("cat {$path} | {$this->getColorizerToolPath()} -C");

        $process->run();

        return $process->getOutput();
    }
}
