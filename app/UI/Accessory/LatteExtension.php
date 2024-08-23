<?php declare(strict_types = 1);

namespace App\UI\Accessory;

use Latte\Extension;

final class LatteExtension extends Extension
{

	/**
	 * @return array<string, callable>
	 */
	public function getFilters(): array
	{
		return [];
	}

	/**
	 * @return array<string, callable>
	 */
	public function getFunctions(): array
	{
		return [];
	}

}
