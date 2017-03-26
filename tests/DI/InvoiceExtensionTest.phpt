<?php declare(strict_types = 1);

namespace JanKonas\NetteInvoice\Test\DI;

use JanKonas\NetteInvoice\InvoiceFactory;
use JanKonas\NetteInvoice\Participant\Participant;
use JanKonas\NetteInvoice\Test\ExtensionTestCase;
use Tester\Assert;

require_once __DIR__ . '/../bootstrap.php';

/** @testCase */
class InvoiceExtensionTest extends ExtensionTestCase
{

	public function testDefaultConfiguration(): void
	{
		$container = $this->createContainer();
		/** @var InvoiceFactory $factory */
		$factory = $container->getByType(InvoiceFactory::class);
		Assert::type(InvoiceFactory::class, $factory);
		Assert::false($factory->isInvoiceSimplified());
		Assert::false($factory->isEetRequired());
		Assert::null($factory->getSupplier());
	}

	public function testInverseInvoiceConfiguration(): void
	{
		$container = $this->createContainer('invoice-inverse');
		/** @var InvoiceFactory $factory */
		$factory = $container->getByType(InvoiceFactory::class);
		Assert::type(InvoiceFactory::class, $factory);
		Assert::true($factory->isInvoiceSimplified());
		Assert::true($factory->isEetRequired());
	}

	/**
	 * @codingStandardsIgnoreLine Exception message must be on same row
	 * @throws \JanKonas\NetteInvoice\DI\InvalidConfigurationException Section 'invoice' of configuration can contain only 'simplified' and 'eet' directives
	 */
	public function testInvalidInvoiceConfiguration1(): void
	{
		$this->createContainer('invoice-invalid1');
	}

	/**
	 * @codingStandardsIgnoreLine Exception message must be on same row
	 * @throws \JanKonas\NetteInvoice\DI\InvalidConfigurationException In section 'invoice', 'simplified' and 'eet' directives must be boolean
	 */
	public function testInvalidInvoiceConfiguration2(): void
	{
		$this->createContainer('invoice-invalid2');
	}

	public function testMinimalSupplierConfiguration(): void
	{
		$container = $this->createContainer('supplier-minimal');
		/** @var InvoiceFactory $factory */
		$factory = $container->getByType(InvoiceFactory::class);
		$supplier = $factory->getSupplier();
		Assert::type(Participant::class, $supplier);
		Assert::same('Company Name', $supplier->getName());
		Assert::same('Street', $supplier->getStreet());
		Assert::same('1', $supplier->getStreetNumber());
		Assert::same('City', $supplier->getCity());
		Assert::null($supplier->getZipCode());
		Assert::null($supplier->getCountry());
		Assert::null($supplier->getIn());
		Assert::null($supplier->getTaxIn());
		Assert::null($supplier->getBusinessRegisterInfo());
		Assert::null($supplier->getAccountNumber());
		Assert::same([], $supplier->getExtraData());
		Assert::false($supplier->isVatPayer());
	}

	public function testFullSupplierConfiguration(): void
	{
		$container = $this->createContainer('supplier-full');
		/** @var InvoiceFactory $factory */
		$factory = $container->getByType(InvoiceFactory::class);
		$supplier = $factory->getSupplier();
		Assert::type(Participant::class, $supplier);
		Assert::same('Company Name', $supplier->getName());
		Assert::same('Street', $supplier->getStreet());
		Assert::same('1', $supplier->getStreetNumber());
		Assert::same('City', $supplier->getCity());
		Assert::same('zip', $supplier->getZipCode());
		Assert::same('country', $supplier->getCountry());
		Assert::same('12345678', $supplier->getIn());
		Assert::same('CZ12345678', $supplier->getTaxIn());
		Assert::same('register A, file 123', $supplier->getBusinessRegisterInfo());
		Assert::same('12345678/5678', $supplier->getAccountNumber());
		Assert::same([
			'something' => 'aaa',
			'else' => 'bbb',
			'subArray' => [1, 2],
		], $supplier->getExtraData());
		Assert::false($supplier->isVatPayer());
	}

	public function testClassSupplierConfiguration(): void
	{
		$container = $this->createContainer('supplier-class');
		/** @var InvoiceFactory $factory */
		$factory = $container->getByType(InvoiceFactory::class);
		$supplier = $factory->getSupplier();
		Assert::type(Participant::class, $supplier);
		Assert::same('Company Name', $supplier->getName());
		Assert::same('Street', $supplier->getStreet());
		Assert::same('1', $supplier->getStreetNumber());
		Assert::same('City', $supplier->getCity());
	}

	public function testServiceSupplierConfiguration(): void
	{
		$container = $this->createContainer('supplier-service');
		/** @var InvoiceFactory $factory */
		$factory = $container->getByType(InvoiceFactory::class);
		$supplier = $factory->getSupplier();
		Assert::type(Participant::class, $supplier);
		Assert::same('Company Name', $supplier->getName());
		Assert::same('Street', $supplier->getStreet());
		Assert::same('1', $supplier->getStreetNumber());
		Assert::same('City', $supplier->getCity());
		Assert::same('Country', $supplier->getCountry());
		Assert::true($supplier->isVatPayer());
	}

	/**
	 * @codingStandardsIgnoreLine Exception message must be on same row
	 * @throws \JanKonas\NetteInvoice\DI\InvalidConfigurationException Minimal supplier configuration contains 'name', 'street', 'streetNumber' and 'city'
	 */
	public function testIncompleteSupplierConfiguration(): void
	{
		$this->createContainer('supplier-incomplete');
	}

	/**
	 * @throws \JanKonas\NetteInvoice\DI\InvalidConfigurationException Supplier country should be string
	 */
	public function testInvalidSupplierConfiguration1(): void
	{
		$this->createContainer('supplier-invalid1');
	}

	/**
	 * @throws \JanKonas\NetteInvoice\DI\InvalidConfigurationException Supplier extra data should be array
	 */
	public function testInvalidSupplierConfiguration2(): void
	{
		$this->createContainer('supplier-invalid2');
	}

	/**
	 * @throws \JanKonas\NetteInvoice\DI\InvalidConfigurationException Supplier 'vatPayer' should be boolean
	 */
	public function testInvalidSupplierConfiguration3(): void
	{
		$this->createContainer('supplier-invalid3');
	}

	/**
	 * @throws \TypeError
	 */
	public function testInvalidSupplierConfiguration4(): void
	{
		$container = $this->createContainer('supplier-invalid4');
		$container->getByType(InvoiceFactory::class);
	}

}

$test = new InvoiceExtensionTest();
$test->run();
