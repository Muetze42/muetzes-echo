<?php

namespace App\Console\Commands;

use App\Models\Bot;
use Illuminate\Console\Command;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Process;

class RestartBot extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'bot:restart {id}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Restart chatbot node';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $id = $this->argument('id');

        $bot = Bot::find($id);

        if (!$bot) {
            $this->error(__('Bot not found'));
            return 0;
        }

        $process = Process::fromShellCommandline('ps -aef | grep node');
        $process->run();
        if (!$process->isSuccessful()) {
            throw new ProcessFailedException($process);
        }

        $output = $process->getOutput();
        $lines = explode("\n", $output);

        foreach ($lines as $line) {
            if (str_contains($line, 'node app.js muetzes_echo_'.$id)) {
                $pid = preg_split('/\s+/', $line)[1];
                $array = explode(' ', $line);
                $running_node = end($array);

                $this->error('Stop Bot Task: '.$running_node);
                $process = Process::fromShellCommandline('kill '.$pid.' > /dev/null 2>&1 &');
                $process->run();
                if (!$process->isSuccessful()) {
                    throw new ProcessFailedException($process);
                }

                $this->warn('Starting muetzes_echo_'.$id);
                $process = Process::fromShellCommandline('cd '.config('services.node.npm_command').' muetzes_echo_'.$id.' > /dev/null 2>&1 &');
                $process->run();
                if (!$process->isSuccessful()) {
                    throw new ProcessFailedException($process);
                }
            }
        }

        return 0;
    }
}
