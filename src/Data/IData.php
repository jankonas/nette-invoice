<?php declare(strict_types = 1);

namespace JanKonas\NetteInvoice\Data;

use DateTimeImmutable;

interface IData
{

	public function getInvoiceNumber(): string;

	public function getIssuanceDate(): DateTimeImmutable;

	public function getDueDate(): DateTimeImmutable;

	public function getPaymentMethod(): string;

	public function getItemsCurrency(): string;

	public function getTaxCurrency(): string;

	public function getExchangeRate(): ?float;

	public function getTaxableDate(): ?DateTimeImmutable;

	public function shouldVatBePayedByCustomer(): ?bool;

	public function getVatExemptionReason(): ?string;

	public function getBankTransferData(): ?IBankTransferData;

	public function getEetData(): ?IEetData;

	/** @return mixed[] */
	public function getExtraData(): array;

	public function containsVatData(): bool;

}
