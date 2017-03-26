<?php declare(strict_types = 1);

namespace JanKonas\NetteInvoice;

use JanKonas\NetteInvoice\Data\IData;
use JanKonas\NetteInvoice\Data\IncompleteDataException;
use JanKonas\NetteInvoice\Item\IItem;
use JanKonas\NetteInvoice\Participant\IParticipant;
use JanKonas\NetteInvoice\Participant\MissingParticipantException;

class InvoiceFactory
{

	/** @var bool */
	private $simplifiedInvoice;

	/** @var bool */
	private $eetInvoice;

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
	 */
	public function createInvoice(IData $data, array $items, ?IParticipant $consumer = null): Invoice
	{
		if ($consumer === null && !$this->simplifiedInvoice) {
			throw new MissingParticipantException('Consumer must be supplied when simplified invoice is not allowed');
		}
		if ($this->eetInvoice && !$data->containsEetData()) {
			throw new IncompleteDataException('Supplied data does not contain EET data');
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

}
