<?php declare(strict_types = 1);

namespace JanKonas\NetteInvoice\Data;

interface IData
{

	public function containsVatData(): bool;

	public function containsEetData(): bool;

}
