<?php

namespace Resta\Support;

use Symfony\Component\Process\Process as ProcessHandler;
use Symfony\Component\Process\Exception\ProcessFailedException;

class Process
{
    /**
     * @var $arguments
     */
    protected $arguments;

    /**
     * Process constructor.
     * @param $command
     * @param $args
     */
    public function __construct($command,$args)
    {
        $this->arguments[] = 'php';
        $this->arguments[] = 'api';
        $this->arguments = array_merge($this->arguments,explode(" ",$command));
        $this->arguments[] = strtolower(app);
        $this->arguments[] = $args;
    }

    public function handle()
    {
        $process = new ProcessHandler($this->arguments,root.'');
        $process->start();

        foreach ($process as $type => $data) {
            if ($process::OUT !== $type) {
                return false;
            }

            return true;
        }
    }
}