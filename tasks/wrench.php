<?php

namespace Fuel\Tasks;

class Wrench
{
	public static function run()
	{
		\Config::load('wrench', true);
		foreach (\Config::get('wrench.coffee', []) as $coffee)
		{
			\Cli::write('coffeescript '.$coffee['source'].' => '.$coffee['target']);
			passthru('coffee -o '.$coffee['target'].' -c '.$coffee['source']);
		}

		foreach (\Config::get('wrench.sass', []) as $sass)
		{
			\Cli::write('sass '.$sass['source'].' => '.$sass['target']);
			passthru('sass --update '.$sass['source'].':'.$sass['target']);
		}

		\Cli::write('Coffee and sass files compiled', 'green');
	}

	public static function watch()
	{
		\Cli::write('Now watching for coffeescript and sass file changes');
		\Cli::write('Add directories by editing the wrench config');
		\Cli::write('Ctl C to quit');
		$descriptorspec = [
			0 => ['pipe', 'r'],
			1 => ['pipe', 'w'],
			2 => ['file', '/tmp/error-output.txt', 'a']
		];

		$processes = [];
		$pipes = [];
		\Config::load('wrench', true);
		foreach (\Config::get('wrench.coffee', []) as $coffee)
		{
			\Cli::write(\Cli::color('[coffee] ', 'blue').\Cli::color($coffee['source'], 'green').' => '.\Cli::color($coffee['target'], 'white'));
			$pipes[] = '';
			$processes[] = proc_open('coffee -o '.$coffee['target'].' -cw '.$coffee['source'], $descriptorspec, $pipes[count($pipes) - 1]);
		}

		foreach (\Config::get('wrench.sass', []) as $sass)
		{
			\Cli::write(\Cli::color('[sass] ', 'yellow').\Cli::color($sass['source'], 'green').' => '.\Cli::color($sass['target'], 'white'));
			$pipes[] = '';
			$processes[] = proc_open('sass --watch '.$sass['source'].':'.$sass['target'], $descriptorspec, $pipes[count($pipes) - 1]);
		}

		while (true)
		{
			// loop through all the processes and exit if any has died
			foreach ($processes as $process)
			{
				proc_get_status($process) or exit();
			}
			// sleep for a bit so the system isn't using a lot more resources
			sleep(2);
		}
	}
}