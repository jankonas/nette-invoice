<?php declare(strict_types = 1);

namespace JanKonas\NetteInvoice\Data;

use DateTimeImmutable;

interface IEetData
{

	public function getFik(): ?string;

	public function getBkp(): string;

	public function getPkp(): ?string;

	public function getRevenueDateTime(): DateTimeImmutable;

	public function getPremiseId(): string;

	public function getCashRegisterId(): string;

	public function getRevenueEvidenceMode(): string;

}
