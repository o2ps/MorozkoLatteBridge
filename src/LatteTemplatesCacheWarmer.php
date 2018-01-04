<?php

declare(strict_types = 1);

namespace Oops\MorozkoLatteBridge;

use Latte\CompileException;
use Latte\Engine;
use Nette\Bridges\ApplicationLatte\ILatteFactory;
use Nette\Utils\Finder;
use Oops\Morozko\CacheWarmer;
use Oops\Morozko\CacheWarmupFailedException;


final class LatteTemplatesCacheWarmer implements CacheWarmer
{

	/**
	 * @var string
	 */
	private $directory;

	/**
	 * @var Engine
	 */
	private $latte;


	public function __construct(string $directory, ILatteFactory $latteFactory)
	{
		$this->directory = $directory;
		$this->latte = $this->createLatte($latteFactory);
	}


	public function warmup(): void
	{
		$exceptions = [];

		/** @var \SplFileInfo[] $templates */
		$templates = Finder::findFiles('*.latte')
			->from($this->directory);

		foreach ($templates as $templateFileInfo) {
			try {
				$this->latte->warmupCache($templateFileInfo->getRealPath());

			} catch (CompileException $exception) {
				$exceptions[] = $exception;
			}
		}

		if ( ! empty($exceptions)) {
			$exceptionMessages = \array_map(function (CompileException $e): string {
				return $e->getMessage();
			}, $exceptions);
			throw new CacheWarmupFailedException(\implode("\n", $exceptionMessages));
		}
	}


	private function createLatte(ILatteFactory $latteFactory): Engine
	{
		$latte = $latteFactory->create();

		if (\class_exists(\Nette\Bridges\CacheLatte\CacheMacro::class)) {
			$latte->getCompiler()->addMacro('cache', new \Nette\Bridges\CacheLatte\CacheMacro());
		}

		if (\class_exists(\Nette\Bridges\ApplicationLatte\UIMacros::class)) {
			\Nette\Bridges\ApplicationLatte\UIMacros::install($latte->getCompiler());
		}

		if (\class_exists(\Nette\Bridges\FormsLatte\FormMacros::class)) {
			\Nette\Bridges\FormsLatte\FormMacros::install($latte->getCompiler());
		}

		return $latte;
	}

}
