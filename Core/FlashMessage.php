<?php
declare(strict_types = 1);
namespace Klapuch\UI;

use Klapuch\Output;

interface FlashMessage {
	/**
	 * Flash the message
	 * @param string $content
	 * @param string $type
	 * @return void
	 */
	public function flash(string $content, string $type): void;

	/**
	 * Print the message
	 * @param \Klapuch\Output\Format $format
	 * @return \Klapuch\Output\Format
	 */
	public function print(Output\Format $format): Output\Format;
}