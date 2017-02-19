<?php
declare(strict_types = 1);
namespace Klapuch\UI;

use Klapuch\Output;

interface Pagination {
	/**
	 * Print the pagination
	 * @param \Klapuch\Output\Format
	 * @return \Klapuch\Output\Format
	 */
	public function print(Output\Format $format): Output\Format;
}