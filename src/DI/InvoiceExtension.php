<?php declare(strict_types = 1);

namespace JanKonas\NetteInvoice\DI;

use JanKonas\NetteInvoice\InvoiceFactory;
use JanKonas\NetteInvoice\Participant\IParticipant;
use JanKonas\NetteInvoice\Participant\Participant;
use Nette\DI\CompilerExtension;
use Nette\DI\ServiceDefinition;
use Nette\DI\Statement;

class InvoiceExtension extends CompilerExtension
{

	/** @var mixed[][] */
	private $defaultConfig = [
		'invoice' => [
			'simplified' => false,
			'eet' => false,
		],
	];

	public function loadConfiguration(): void
	{
		$builder = $this->getContainerBuilder();
		$builder->addDefinition($this->prefix('factory'), $this->createFactory(
			$this->getInvoiceConfig(),
			$this->getSupplierFromConfig()
		));
	}

	/**
	 * @param bool[] $invoiceConfig
	 * @param IParticipant|Statement|string|null $supplier
	 * @return ServiceDefinition
	 */
	private function createFactory(array $invoiceConfig, $supplier): ServiceDefinition
	{
		$factory = new ServiceDefinition();
		$factory->setClass(InvoiceFactory::class, [
			$invoiceConfig['simplified'],
			$invoiceConfig['eet'],
		]);
		if ($supplier !== null) {
			$factory->addSetup('setSupplier', [$supplier]);
		}
		return $factory;
	}

	/**
	 * @return bool[]
	 * @throws InvalidConfigurationException
	 */
	private function getInvoiceConfig(): array
	{
		$config = parent::getConfig();
		if (isset($config['invoice'])) {
			$config = array_merge($this->defaultConfig['invoice'], $config['invoice']);
		} else {
			$config = $this->defaultConfig['invoice'];
		}
		if (count($config) !== 2) {
			$msg = 'Section \'invoice\' of configuration can contain only \'simplified\' and \'eet\' directives';
			throw new InvalidConfigurationException($msg);
		}
		if (!is_bool($config['simplified']) || !is_bool($config['eet'])) {
			$msg = 'In section \'invoice\', \'simplified\' and \'eet\' directives must be boolean';
			throw new InvalidConfigurationException($msg);
		}
		return $config;
	}

	/**
	 * @return IParticipant|Statement|string|null
	 * @throws InvalidConfigurationException
	 */
	private function getSupplierFromConfig()
	{
		$config = parent::getConfig();
		if (!isset($config['supplier'])) {
			return null;
		}
		if (
			$config['supplier'] instanceof Statement
			|| (is_string($config['supplier']) && substr($config['supplier'], 0, 1) === '@')
		) {
			return $config['supplier'];
		}
		if (
			!isset($config['supplier']['name'])
			|| !isset($config['supplier']['street'])
			|| !isset($config['supplier']['streetNumber'])
			|| !isset($config['supplier']['city'])
		) {
			$msg = 'Minimal supplier configuration contains \'name\', \'street\', \'streetNumber\' and \'city\'';
			throw new InvalidConfigurationException($msg);
		}
		$supplier = new Participant(
			$this->convertScalarToString($config['supplier']['name'], 'Supplier name'),
			$this->convertScalarToString($config['supplier']['street'], 'Supplier street'),
			$this->convertScalarToString($config['supplier']['streetNumber'], 'Supplier street number'),
			$this->convertScalarToString($config['supplier']['city'], 'Supplier city')
		);
		unset(
			$config['supplier']['name'],
			$config['supplier']['street'],
			$config['supplier']['streetNumber'],
			$config['supplier']['city']
		);
		foreach ($config['supplier'] as $key => $value) {
			switch ($key) {
				case 'extraData':
					if (!is_array($value)) {
						throw new InvalidConfigurationException('Supplier extra data should be array');
					}
					$supplier->setExtraData($value);
					break;
				case 'vatPayer':
					if (!is_bool($value)) {
						throw new InvalidConfigurationException('Supplier \'vatPayer\' should be boolean');
					}
					$supplier->setVatPayer($value);
					break;
				default:
					$value = $this->convertScalarToString($value, 'Supplier ' . $key);
					$supplier->{'set' . ucfirst($key)}($value);
			}
		}
		return $supplier;
	}

	/**
	 * @param mixed $variable
	 * @param string|null $description
	 * @return string
	 * @throws InvalidConfigurationException
	 */
	private function convertScalarToString($variable, ?string $description = null): string
	{
		if (is_scalar($variable)) {
			return (string) $variable;
		}
		$msg = $description === null ?
			'Non scalar type found where string was expected' : $description . ' should be string';
		throw new InvalidConfigurationException($msg);
	}

}
