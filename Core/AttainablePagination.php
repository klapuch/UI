<?php
declare(strict_types = 1);
namespace Klapuch\UI;

use Klapuch\Output;

/**
 * Pagination kept in attainable limits
 */
final class AttainablePagination implements Pagination {
	private const BASE = 1;
	private $current;
	private $limit;
	private $total;

	public function __construct(int $current, int $limit, int $total) {
		$this->current = $current;
		$this->limit = $limit;
		$this->total = $total;
	}

	public function print(Output\Format $format): Output\Format {
		$current = $this->current($this->current);
		$last = $this->last($this->total);
		return $format->with('first', self::BASE)
			->with('last', $last)
			->with('current', $current)
			->with('previous', max(self::BASE, $current - 1))
			->with('next', min($last, $current + 1));
	}

	private function limit(int $limit): int {
		return max($limit, self::BASE);
	}

	private function last(int $total): int {
		return (int) ceil($total / $this->limit($this->limit));
	}

	private function current(int $current): int {
		return max(min($current, $this->last($this->total)), self::BASE);
	}
}