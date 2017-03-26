<?php declare(strict_types = 1);

namespace JanKonas\NetteInvoice\Test\Participant;

use JanKonas\NetteInvoice\Participant\Participant;
use stdClass;
use Tester\Assert;
use Tester\TestCase;

require_once __DIR__ . '/../bootstrap.php';

/** @testCase */
class ParticipantTest extends TestCase
{

	public function testConstructor(): void
	{
		$participant = new Participant('name', 'street', 'number', 'city');
		Assert::same('name', $participant->getName());
		Assert::same('street', $participant->getStreet());
		Assert::same('number', $participant->getStreetNumber());
		Assert::same('city', $participant->getCity());
		Assert::null($participant->getZipCode());
		Assert::null($participant->getCountry());
		Assert::null($participant->getIn());
		Assert::null($participant->getTaxIn());
		Assert::null($participant->getBusinessRegisterInfo());
		Assert::null($participant->getAccountNumber());
		Assert::same([], $participant->getExtraData());
		Assert::false($participant->isVatPayer());

		$participant = new Participant(
			'name',
			'street',
			'number',
			'city',
			'zip',
			'country',
			'in',
			'tax',
			'register',
			'account'
		);
		Assert::same('name', $participant->getName());
		Assert::same('street', $participant->getStreet());
		Assert::same('number', $participant->getStreetNumber());
		Assert::same('city', $participant->getCity());
		Assert::same('zip', $participant->getZipCode());
		Assert::same('country', $participant->getCountry());
		Assert::same('in', $participant->getIn());
		Assert::same('tax', $participant->getTaxIn());
		Assert::same('register', $participant->getBusinessRegisterInfo());
		Assert::same('account', $participant->getAccountNumber());
		Assert::same([], $participant->getExtraData());
		Assert::true($participant->isVatPayer());
	}

	public function testGettersAndSetters(): void
	{
		$participant = new Participant('name', 'street', 'number', 'city');
		$participant->setZipCode('zip');
		Assert::same('zip', $participant->getZipCode());
		$participant->setCountry('country');
		Assert::same('country', $participant->getCountry());
		$participant->setIn('in');
		Assert::same('in', $participant->getIn());
		$participant->setTaxIn('tax');
		Assert::same('tax', $participant->getTaxIn());
		$participant->setBusinessRegisterInfo('register');
		Assert::same('register', $participant->getBusinessRegisterInfo());
		$participant->setAccountNumber('account');
		Assert::same('account', $participant->getAccountNumber());
		$data = [1, true, false, 0.2, 'a', 'a' => 'b', [1, 2, 'a' => 3], new stdClass()];
		$participant->setExtraData($data);
		Assert::same($data, $participant->getExtraData());
	}

	public function testIsVatPayer(): void
	{
		$participant = new Participant('name', 'street', 'number', 'city');
		Assert::false($participant->isVatPayer());
		$participant->setTaxIn('');
		Assert::true($participant->isVatPayer());
		$participant->setTaxIn('tax in');
		Assert::true($participant->isVatPayer());
		$participant->setVatPayer(false);
		Assert::false($participant->isVatPayer());

		$participant = new Participant('name', 'street', 'number', 'city');
		Assert::false($participant->isVatPayer());
		$participant->setVatPayer(true);
		Assert::true($participant->isVatPayer());
		$participant->setVatPayer(false);
		Assert::false($participant->isVatPayer());
	}

}

$test = new ParticipantTest();
$test->run();
