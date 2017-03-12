<?php
declare(strict_types = 1);
namespace Klapuch\UI;

use Klapuch\Output;

/**
 * Pagination limited to defined limit
 */
final class LimitedPagination implements Pagination {
	private $origin;
	private $current;
	private $max;

	public function __construct(Pagination $origin, int $current, int $max = 100) {
		$this->origin = $origin;
		$this->current = $current;
		$this->max = $max;
	}

	public function print(Output\Format $format): Output\Format {
		if($this->current > $this->max) {
			throw new \OverflowException(
				sprintf('Max limit %d has been overstepped', $this->max)
			);
		}
		return $this->origin->print($format);
	}
}