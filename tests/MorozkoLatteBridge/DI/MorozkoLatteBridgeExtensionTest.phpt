<?php

declare(strict_types = 1);

namespace OopsTests\MorozkoLatteBridge;

use Nette\Configurator;
use Nette\DI\Container;
use Oops\MorozkoLatteBridge\LatteTemplatesCacheWarmer;
use Tester\Assert;
use Tester\TestCase;


require_once __DIR__ . '/../../bootstrap.php';


/**
 * @testCase
 */
final class MorozkoLatteBridgeExtensionTest extends TestCase
{

	public function testExtension(): void
	{
		$container = $this->createContainer('default');

		/** @var LatteTemplatesCacheWarmer $latteWarmer */
		$latteWarmer = $container->getByType(LatteTemplatesCacheWarmer::class, FALSE);
		Assert::notSame(NULL, $latteWarmer);

		$cacheWarmers = $container->getService('morozko.configuration')->getCacheWarmers();
		Assert::contains($latteWarmer, $cacheWarmers);

		$directoryProperty = (new \ReflectionClass($latteWarmer))->getProperty('directory');
		$directoryProperty->setAccessible(TRUE);
		$directory = $directoryProperty->getValue($latteWarmer);
		Assert::same(__DIR__ . '/../fixtures', $directory);
	}


	private function createContainer(string $configFile): Container
	{
		$configurator = new Configurator();
		$configurator->setTempDirectory(\TEMP_DIR);
		$configurator->addConfig(__DIR__ . '/fixtures/' . $configFile . '.neon');
		$configurator->addParameters([
			'appDir' => __DIR__ . '/..',
		]);

		return $configurator->createContainer();
	}

}


(new MorozkoLatteBridgeExtensionTest())->run();
