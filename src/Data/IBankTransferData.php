<?php declare(strict_types = 1);

namespace JanKonas\NetteInvoice\Data;

interface IBankTransferData
{

	public function getVariableSymbol(): ?int;

	public function getConstantSymbol(): ?int;

	public function getSpecificSymbol(): ?int;

}
