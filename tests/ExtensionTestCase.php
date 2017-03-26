<?php declare(strict_types = 1);

namespace JanKonas\NetteInvoice\Test;

use Nette\Configurator;
use Nette\DI\Container;
use Tester\Helpers;
use Tester\TestCase;

abstract class ExtensionTestCase extends TestCase
{

	protected function createContainer(?string $configName = null): Container
	{
		$configurator = new Configurator;
		if (!is_dir(__DIR__ . '/tmp')) {
			@mkdir(__DIR__ . '/tmp', 0777);
		}
		$tempDir = __DIR__ . '/tmp/' . getmypid();
		Helpers::purge($tempDir);
		$configurator->setTempDirectory($tempDir);
		$configurator->addConfig(__DIR__ . '/configs/base.neon');
		if ($configName !== null) {
			$configurator->addConfig(__DIR__ . '/configs/' . $configName . '.neon');
		}
		return $configurator->createContainer();
	}

}
