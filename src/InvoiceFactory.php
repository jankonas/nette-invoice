<?php declare(strict_types = 1);

namespace JanKonas\NetteInvoice;

use JanKonas\NetteInvoice\Data\IData;
use JanKonas\NetteInvoice\Data\IncompleteDataException;
use JanKonas\NetteInvoice\Data\InvalidDataException;
use JanKonas\NetteInvoice\Item\IItem;
use JanKonas\NetteInvoice\Participant\IParticipant;
use JanKonas\NetteInvoice\Participant\MissingParticipantException;

class InvoiceFactory
{

	/** @var bool */
	private $simplifiedInvoice;

	/** @var bool */
	private $eetInvoice;

	/** @var IParticipant|null */
	private $supplier;

	public function __construct(bool $simplifiedInvoice, bool $eetInvoice)
	{
		$this->simplifiedInvoice = $simplifiedInvoice;
		$this->eetInvoice = $eetInvoice;
	}

	/**
	 * @param IData $data
	 * @param IItem[] $items
	 * @param IParticipant|null $consumer
	 * @return Invoice
	 * @throws MissingParticipantException
	 * @throws IncompleteDataException
	 * @throws InvalidDataException
	 */
	public function createInvoice(IData $data, array $items, ?IParticipant $consumer = null): Invoice
	{
		if ($this->getSupplier() === null) {
			throw new MissingParticipantException('No supplier set');
		}
		if ($this->getSupplier()->isVatPayer() && !$data->containsVatData()) {
			throw new IncompleteDataException('Supplier is VAT payer but supplied data does not contain VAT data');
		}
		if ($consumer === null && !$this->simplifiedInvoice) {
			throw new MissingParticipantException('Consumer must be supplied when simplified invoice is not allowed');
		}
		if ($this->simplifiedInvoice && $data->shouldVatBePayedByCustomer() === true) {
			throw new InvalidDataException('Invoice cannot be simplified when tax should be payed by customer');
		}
		if ($data->getItemsCurrency() !== $data->getTaxCurrency() && $data->getExchangeRate() === null) {
			throw new InvalidDataException('Items currency does not match tax currency but no exchange rate given');
		}
		if ($this->eetInvoice && $data->getEetData() === null) {
			throw new IncompleteDataException('Supplied data does not contain EET data');
		}
		$eetData = $data->getEetData();
		if ($eetData !== null && $eetData->getFik() === null && $eetData->getPkp() === null) {
			throw new IncompleteDataException('EET data must contain one of FIK and PKP codes');
		}
		return new Invoice(); // TODO
	}

	public function isInvoiceSimplified(): bool
	{
		return $this->simplifiedInvoice;
	}

	public function setSimplifiedInvoice(bool $simplifiedInvoice): void
	{
		$this->simplifiedInvoice = $simplifiedInvoice;
	}

	public function isEetRequired(): bool
	{
		return $this->eetInvoice;
	}

	public function setEetInvoice(bool $eetInvoice): void
	{
		$this->eetInvoice = $eetInvoice;
	}

	public function getSupplier(): ?IParticipant
	{
		return $this->supplier;
	}

	public function setSupplier(IParticipant $supplier): void
	{
		$this->supplier = $supplier;
	}

}
