<?php
declare(strict_types = 1);
namespace Klapuch\UI;

use Klapuch\Output;

interface Pagination {
	/**
	 * Print the pagination
	 * @param \Klapuch\Output\Format $format
	 * @return \Klapuch\Output\Format
	 */
	public function print(Output\Format $format): Output\Format;

	/**
	 * Range as [1, 30] or [1,2,3,4,...]
	 * @return array
	 */
	public function range(): array;
}