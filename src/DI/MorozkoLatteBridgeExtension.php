<?php

declare(strict_types = 1);

namespace Oops\MorozkoLatteBridge\DI;

use Nette\DI\CompilerExtension;
use Nette\DI\Helpers;
use Oops\MorozkoLatteBridge\LatteTemplatesCacheWarmer;


final class MorozkoLatteBridgeExtension extends CompilerExtension
{

	private $defaults = [
		'directory' => '%appDir%',
	];


	public function loadConfiguration()
	{
		$builder = $this->getContainerBuilder();
		$config = $this->validateConfig($this->defaults);
		$directory = Helpers::expand($config['directory'], $builder->parameters);

		$builder->addDefinition($this->prefix('warmer'))
			->setType(LatteTemplatesCacheWarmer::class)
			->setFactory(LatteTemplatesCacheWarmer::class, [$directory]);
	}

}
