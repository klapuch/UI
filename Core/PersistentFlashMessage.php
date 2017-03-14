<?php
declare(strict_types = 1);
namespace Klapuch\UI;

use Klapuch\Output;

/**
 * Flash message persisted in some sort of storage
 */
final class PersistentFlashMessage implements FlashMessage {
	private const IDENTIFIER = '_flashMessage';
	private $sessions;

	public function __construct(array &$sessions) {
		$this->sessions = &$sessions;
	}

	public function flash(string $content, string $type): void {
		$this->sessions[self::IDENTIFIER][] = [$type => $content];
	}

	public function print(Output\Format $format): Output\Format {
		if(!$this->printable($this->sessions))
			return $format;
		$message = array_shift($this->sessions[self::IDENTIFIER]);
		return $format->with('type', key($message))
			->with('content', current($message));
	}

	/**
	 * Is there something to print?
	 * @param array $storage
	 * @return bool
	 */
	private function printable(array $storage): bool {
		return isset($storage[self::IDENTIFIER]) && $storage[self::IDENTIFIER];
	}
}