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
	private $perPage;
	private $total;
	private $defaultLimit;

	public function __construct(
		int $current,
		int $perPage,
		int $total,
		int $defaultLimit = 100
	) {
		$this->current = $current;
		$this->perPage = $perPage;
		$this->total = $total;
		$this->defaultLimit = $defaultLimit;
	}

	public function print(Output\Format $format): Output\Format {
		$current = $this->current($this->current);
		$last = $this->last($this->total);
		return $format->with('first', self::BASE)
			->with('last', $last)
			->with('previous', max(self::BASE, $current - 1))
			->with('next', min($last, $current + 1));
	}

	public function range(): array {
		return [self::BASE, $this->last($this->total)];
	}

	private function limit(int $perPage): int {
		return min($this->defaultLimit, $perPage) ?: $this->defaultLimit;
	}

	private function last(int $total): int {
		return (int) ceil($total / $this->limit($this->perPage));
	}

	private function current(int $current): int {
		return max(min($current, $this->last($this->total)), self::BASE);
	}
}