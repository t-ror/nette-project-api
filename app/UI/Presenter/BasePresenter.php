<?php declare(strict_types = 1);

namespace App\UI\Presenter;

use Nette\Application\UI\Presenter;

class BasePresenter extends Presenter
{

	protected function beforeRender(): void
	{
		parent::beforeRender();
		$this->setLayout(__DIR__ . '/../@layout.latte');
	}

}
