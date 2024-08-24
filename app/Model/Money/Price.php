<?php declare(strict_types = 1);

namespace App\Model\Money;

use Money\Currency;
use Money\Formatter\DecimalMoneyFormatter;
use Money\Money;
use Money\Parser\DecimalMoneyParser;

final class Price
{

	private Money $money;

	public function __construct(
		private string $price,
		private CurrencyCode $currencyCode,
		private int $decimals,
	)
	{
		$currency = new Currency($this->currencyCode->value);
		$this->money = $this->getParser()->parse($this->price, $currency);
	}

	public function getAmount(): string
	{
		$formatter = $this->getFormatter();

		return $formatter->format($this->money);
	}

	private function getParser(): DecimalMoneyParser
	{
		$currency = new Currency($this->currencyCode->value);

		return DecimalMoneyParserFactory::create($currency, $this->decimals);
	}

	private function getFormatter(): DecimalMoneyFormatter
	{
		$currency = new Currency($this->currencyCode->value);

		return DecimalMoneyFormatterFactory::create($currency, $this->decimals);
	}

}
