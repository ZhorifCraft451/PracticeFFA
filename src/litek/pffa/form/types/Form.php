<?php

/**
 * Copyright 2020-2022 LiTEK - Josewowgame2888
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *     http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

declare(strict_types=1);
namespace litek\pffa\form\types;

use Closure;
use pocketmine\Player;
use pocketmine\utils\Utils;
use function array_merge;

abstract class Form implements \pocketmine\form\Form
{
	protected const TYPE_MODAL = "modal";
	protected const TYPE_MENU = "form";
	protected const TYPE_CUSTOM_FORM = "custom_form";

	/** @var string */
	private $title;

	/** @var Closure|null */
	protected $onSubmit;
	/** @var Closure|null */
	protected $onClose;

	/**
	 * @param string $title
	 */
	public function __construct(string $title)
	{
		$this->title = $title;
	}

	/**
	 * @param string $title
	 * @return self
	 */
	public function setTitle(string $title): self
	{
		$this->title = $title;
		return $this;
	}

	/**
	 * @return string
	 */
	abstract protected function getType(): string;

	/**
	 * @return callable
	 */
	abstract protected function getOnSubmitCallableSignature(): callable;

	/**
	 * @return array
	 */
	abstract protected function serializeFormData(): array;

	/**
	 * @param Closure $onSubmit
	 * @return self
	 */
	public function onSubmit(Closure $onSubmit): self
	{
		Utils::validateCallableSignature($this->getOnSubmitCallableSignature(), $onSubmit);
		$this->onSubmit = $onSubmit;
		return $this;
	}

	/**
	 * @param Closure $onClose
	 * @return self
	 */
	public function onClose(Closure $onClose): self
	{
		Utils::validateCallableSignature(function (Player $player): void {
		}, $onClose);
		$this->onClose = $onClose;
		return $this;
	}

	/**
	 * @return array
	 */
	final public function jsonSerialize(): array
	{
		return array_merge(
			["title" => $this->title, "type" => $this->getType()],
			$this->serializeFormData()
		);
	}
}