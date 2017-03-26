<?php declare(strict_types = 1);

namespace JanKonas\NetteInvoice;

use JanKonas\NetteInvoice\Data\IData;
use JanKonas\NetteInvoice\Item\IItem;
use JanKonas\NetteInvoice\Participant\IParticipant;

class InvoiceFactory
{

	/**
	 * @param IData $data
	 * @param IItem[] $items
	 * @param IParticipant|null $consumer
	 * @return Invoice
	 */
	public function createInvoice(IData $data, array $items, ?IParticipant $consumer = null): Invoice
	{
		return new Invoice(); // TODO
	}

}
