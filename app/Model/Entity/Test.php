<?php declare(strict_types = 1);

namespace App\Model\Entity;

use App\Model\Attributes\TId;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 */
class Test
{

	use TId;

	/** @ORM\Column(type="string", length=255, nullable=false) */
	private string $name;

	public function __construct(string $name)
	{
		$this->name = $name;
	}

	public function getName(): string
	{
		return $this->name;
	}

	public function setName(string $name): void
	{
		$this->name = $name;
	}

}
