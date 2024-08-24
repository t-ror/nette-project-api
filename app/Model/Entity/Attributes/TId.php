<?php declare(strict_types = 1);

namespace App\Model\Entity\Attributes;

use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;

trait TId
{

	#[Id]
	#[GeneratedValue(strategy: 'IDENTITY')]
	#[Column(type: 'integer', nullable: false)]
	private int $id;

	final public function getId(): int
	{
		return $this->id;
	}

}
