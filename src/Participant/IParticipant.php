<?php declare(strict_types = 1);

namespace JanKonas\NetteInvoice\Participant;

interface IParticipant
{

	public function getName(): string;

	public function getStreet(): string;

	public function getStreetNumber(): string;

	public function getCity(): string;

	public function getZipCode(): ?string;

	public function getCountry(): ?string;

	public function getIN(): ?string;

	public function getTaxIN(): ?string;

	public function getBusinessRegisterInfo(): ?string;

	public function getAccountNumber(): ?string;

	/** @return mixed[] */
	public function getExtraData(): array;

	public function isVatPayer(): bool;

}
