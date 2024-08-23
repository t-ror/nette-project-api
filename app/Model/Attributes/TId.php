<?php declare(strict_types = 1);

namespace App\Model\Attributes;

use Doctrine\ORM\Mapping as ORM;

trait TId
{

	/**
	 * @ORM\Id
	 * @ORM\Column(type="integer")
	 * @ORM\GeneratedValue(strategy="IDENTITY")
	 */
	private int $id;

	final public function getId(): int
	{
		return $this->id;
	}

}
