<?php

declare(strict_types = 1);

namespace OopsTests\MorozkoLatteBridge;

use Latte;
use Nette\Bridges\ApplicationLatte\ILatteFactory;
use Oops\MorozkoLatteBridge\LatteTemplatesCacheWarmer;
use Oops\Morozko\CacheWarmupFailedException;
use Tester\Assert;
use Tester\TestCase;


require_once __DIR__ . '/../bootstrap.php';


/**
 * @testCase
 */
final class LatteTemplatesCacheWarmerTest extends TestCase
{

	public function testCacheWarmer(): void
	{
		$latte = \Mockery::mock(Latte\Engine::class);
		$latte->shouldReceive('getCompiler')
			->zeroOrMoreTimes()
			->andReturn(new Latte\Compiler());

		$latte->shouldReceive('warmupCache')
			->with(__DIR__ . '/fixtures/successful.latte')
			->once();

		$latte->shouldReceive('warmupCache')
			->with(__DIR__ . '/fixtures/failing.latte')
			->once()
			->andThrow(new Latte\CompileException('Unknown macro.'));

		$latteFactory = \Mockery::mock(ILatteFactory::class);
		$latteFactory->shouldReceive('create')->andReturn($latte);

		$cacheWarmer = new LatteTemplatesCacheWarmer(__DIR__, $latteFactory);
		Assert::throws(function () use ($cacheWarmer): void {
			$cacheWarmer->warmup();
		}, CacheWarmupFailedException::class, 'Unknown macro.');

		\Mockery::close();
	}

}


(new LatteTemplatesCacheWarmerTest())->run();
