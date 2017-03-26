<?php declare(strict_types = 1);

namespace JanKonas\NetteInvoice\DI;

use JanKonas\NetteInvoice\InvoiceFactory;
use Nette\DI\CompilerExtension;
use Nette\DI\ServiceDefinition;

class InvoiceExtension extends CompilerExtension
{

	/** @var mixed[][] */
	private $defaultConfig = [
		'invoice' => [
			'simplified' => false,
			'eet' => false,
		],
	];

	/** @return mixed[] */
	public function getConfig(): array
	{
		$config = parent::getConfig();
		return [
			'invoice' => array_merge(
				$this->defaultConfig['invoice'],
				isset($config['invoice']) ? $config['invoice'] : []
			),
		];
	}

	public function loadConfiguration(): void
	{
		$builder = $this->getContainerBuilder();
		$config = $this->getConfig();
		$builder->addDefinition($this->prefix('factory'), $this->createFactory($config['invoice']));
	}

	/**
	 * @param bool[] $invoiceConfig
	 * @return ServiceDefinition
	 */
	private function createFactory(array $invoiceConfig): ServiceDefinition
	{
		$factory = new ServiceDefinition();
		$factory->setClass(InvoiceFactory::class, [
			$invoiceConfig['simplified'],
			$invoiceConfig['eet'],
		]);
		return $factory;
	}

}
