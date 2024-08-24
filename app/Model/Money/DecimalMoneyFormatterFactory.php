<?php declare(strict_types = 1);

namespace App\Model\Money;

use InvalidArgumentException;
use Money\Currencies\CurrencyList;
use Money\Currency;
use Money\Formatter\DecimalMoneyFormatter;

final class DecimalMoneyFormatterFactory
{

	private static ?DecimalMoneyFormatter $service = null;

	public static function create(Currency $currency, int $decimals): DecimalMoneyFormatter
	{
		if ($decimals < 0) {
			throw new InvalidArgumentException('Decimals must be grater than 0');
		}

		if (self::$service === null) {
			self::$service = new DecimalMoneyFormatter(new CurrencyList(
				[$currency->getCode() => $decimals],
			));
		}

		return self::$service;
	}

}
