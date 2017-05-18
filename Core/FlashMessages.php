<?php
declare(strict_types = 1);
namespace Klapuch\UI;

use Klapuch\Output;

/**
 * All flash messages
 */
final class FlashMessages implements FlashMessage {
	private $origin;

	public function __construct(FlashMessage $origin) {
		$this->origin = $origin;
	}

	public function flash(string $content, string $type): void {
		$this->origin->flash($content, $type);
	}

	public function print(Output\Format $format): Output\Format {
		$messages = [];
		while (($message = $this->origin->print($format)) !== $format) {
			$messages[] = $message;
		}
		return new Output\CombinedFormat(...array_unique($messages, SORT_REGULAR));
	}
}