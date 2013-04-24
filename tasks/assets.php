<?php

namespace Fuel\Tasks;

class Assets
{
	public static function run()
	{
		passthru('coffee -o public/assets/js/ -c public/assets/coffee/');
		passthru('sass --update public/assets/sass:public/assets/css');
		\Cli::write('Coffee and SaSS files compiled', 'green');
	}

	public static function watch()
	{
		\Cli::write('Now watching public/assets/coffee and public/assets/sass');
		\Cli::write('Ctl C to quit');
		$descriptorspec = [
			0 => ['pipe', 'r'],
			1 => ['pipe', 'w'],
			2 => ['file', '/tmp/error-output.txt', 'a']
		];

		$coffee_process = proc_open('coffee -o public/assets/js/ -cw public/assets/coffee/', $descriptorspec, $pipes);
		$sass_process = proc_open('sass --watch public/assets/sass:public/assets/css', $descriptorspec, $pipes2);

		$running = true;
		while ($running)
		{
			$a = proc_get_status($coffee_process);
			$b = proc_get_status($sass_process);
			$running = $a['running'] && $b['running'];
			sleep(2);
		}
	}
}