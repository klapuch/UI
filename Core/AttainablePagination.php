<?php
declare(strict_types = 1);
namespace Klapuch\UI;

use Klapuch\Output;

/**
 * Pagination kept in attainable limits
 */
final class AttainablePagination implements Pagination {
	private const BASE = 1;
	private const MAX_LIMIT = 100;
	private $current;
	private $total;
	private $limit;

	public function __construct(int $current, int $total, int $limit) {
		$this->current = $current;
		$this->total = $total;
		$this->limit = $limit;
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
		return max(min(self::MAX_LIMIT, $limit), 0);
	}

	private function last(int $total): int {
		return (int) ceil($total / $this->limit($this->limit));
	}

	private function current(int $current): int {
		return max(min($current, $this->last($this->total)), self::BASE);
	}
}