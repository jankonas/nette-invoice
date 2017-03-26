<?php declare(strict_types = 1);

namespace JanKonas\NetteInvoice\Participant;

class Participant implements IParticipant
{

	/** @var string */
	private $name;

	/** @var string */
	private $street;

	/** @var string */
	private $streetNumber;

	/** @var string */
	private $city;

	/** @var string|null */
	private $zipCode;

	/** @var string|null */
	private $country;

	/** @var string|null */
	private $in;

	/** @var string|null */
	private $taxIn;

	/** @var string|null */
	private $businessRegisterInfo;

	/** @var string|null */
	private $accountNumber;

	/** @var mixed[] */
	private $extraData;

	/** @var bool|null */
	private $forcedIsVatPayer;

	public function __construct(
		string $name,
		string $street,
		string $streetNumber,
		string $city,
		?string $zipCode = null,
		?string $country = null,
		?string $in = null,
		?string $taxIn = null,
		?string $businessRegisterInfo = null,
		?string $accountNumber = null
	)
	{
		$this->name = $name;
		$this->street = $street;
		$this->streetNumber = $streetNumber;
		$this->city = $city;
		$this->zipCode = $zipCode;
		$this->country = $country;
		$this->in = $in;
		$this->taxIn = $taxIn;
		$this->businessRegisterInfo = $businessRegisterInfo;
		$this->accountNumber = $accountNumber;
		$this->extraData = [];
	}

	public function getName(): string
	{
		return $this->name;
	}

	public function getStreet(): string
	{
		return $this->street;
	}

	public function getStreetNumber(): string
	{
		return $this->streetNumber;
	}

	public function getCity(): string
	{
		return $this->city;
	}

	public function getZipCode(): ?string
	{
		return $this->zipCode;
	}

	public function setZipCode(string $zipCode): void
	{
		$this->zipCode = $zipCode;
	}

	public function getCountry(): ?string
	{
		return $this->country;
	}

	public function setCountry(string $country): void
	{
		$this->country = $country;
	}

	public function getIn(): ?string
	{
		return $this->in;
	}

	public function setIn(string $in): void
	{
		$this->in = $in;
	}

	public function getTaxIn(): ?string
	{
		return $this->taxIn;
	}

	public function setTaxIn(string $taxIn): void
	{
		$this->taxIn = $taxIn;
	}

	public function getBusinessRegisterInfo(): ?string
	{
		return $this->businessRegisterInfo;
	}

	public function setBusinessRegisterInfo(string $businessRegisterInfo): void
	{
		$this->businessRegisterInfo = $businessRegisterInfo;
	}

	public function getAccountNumber(): ?string
	{
		return $this->accountNumber;
	}

	public function setAccountNumber(string $accountNumber): void
	{
		$this->accountNumber = $accountNumber;
	}

	/** @return mixed[] */
	public function getExtraData(): array
	{
		return $this->extraData;
	}

	/** @param mixed[] $extraData */
	public function setExtraData(array $extraData): void
	{
		$this->extraData = $extraData;
	}

	public function isVatPayer(): bool
	{
		if ($this->forcedIsVatPayer !== null) {
			return $this->forcedIsVatPayer;
		}
		return $this->taxIn !== null;
	}

	public function setVatPayer(bool $isVatPayer): void
	{
		$this->forcedIsVatPayer = $isVatPayer;
	}

}
