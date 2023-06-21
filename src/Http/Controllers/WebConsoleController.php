<?php

namespace Sawirricardo\LaravelWebConsole\Http\Controllers;

use Illuminate\Support\Facades\Route;

class WebConsoleController
{
    public function index()
    {
        return view('web-console::web-console.index', [
            'url' => route(str_replace('index', 'interact', Route::currentRouteName())),
        ]);
    }

    public function interact()
    {
        request()->validate([
            'command' => ['required', 'string'],
            'working_directory' => ['required', 'string'],
        ]);

        chdir(request()->str('working_directory')->trim());

        $cmd = request()->str('command')->trim();

        if ($cmd->substr(0, 2) == 'cd') {
            if (chdir($cmd->substr(3))) {
                return response()->json([
                    'output' => "\r\n",
                    'working_directory' => getcwd(),
                ]);
            }

            return response()->json([
                'output' => 'cd: Unable to change directory'."\r\n",
                'working_directory' => getcwd(),
            ]);
        }

        $output = $this->process($cmd);

        if ($output === false) {
            return response()->json([
                'output' => "\r\n",
                'working_directory' => getcwd(),
            ]);
        }

        return response()->json([
            'output' => str($output)
                ->replace("\n", "\r\n")
                ->toString(),
            'working_directory' => getcwd(),
        ]);
    }

    private function process($command)
    {
        $process = proc_open($command.' 2>&1', [
            ['pipe', 'r'], // STDIN
            ['pipe', 'w'], // STDOUT
            ['pipe', 'w'],  // STDERR
        ], $pipes);

        if (! is_resource($process)) {
            throw new \Exception("Can't execute command.");
        }

        // Nothing to push to STDIN
        fclose($pipes[0]);

        $output = stream_get_contents($pipes[1]);
        fclose($pipes[1]);

        $error = stream_get_contents($pipes[2]);
        fclose($pipes[2]);

        // All pipes must be closed before "proc_close"
        $code = proc_close($process);

        return $output;
    }
}
