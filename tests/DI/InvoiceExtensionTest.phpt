<?php declare(strict_types = 1);

namespace JanKonas\NetteInvoice\Test\DI;

use JanKonas\NetteInvoice\InvoiceFactory;
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
	}

	public function testInverseDataConfiguration(): void
	{
		$container = $this->createContainer('inverse-invoice-config');
		/** @var InvoiceFactory $factory */
		$factory = $container->getByType(InvoiceFactory::class);
		Assert::type(InvoiceFactory::class, $factory);
		Assert::true($factory->isInvoiceSimplified());
		Assert::true($factory->isEetRequired());
	}

}

$test = new InvoiceExtensionTest();
$test->run();
