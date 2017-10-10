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
		$range = $this->range();
		return array_reduce(
			array_filter(array_keys($this->range()), 'is_string'),
			function(Output\Format $format, string $move) use ($range): Output\Format {
				return $format->with($move, $range[$move]);
			},
			$format
		);
	}

	public function range(): array {
		$moves = [
			'first' => self::BASE,
			'prev' => $this->previous($this->current($this->current)),
			'last' => $this->last($this->total),
			'next' => $this->next($this->current($this->current), $this->last($this->total)),
		];
		$range = array_intersect(
			$moves,
			range(self::BASE, $this->last($this->total))
		) + array_diff(range(self::BASE, $this->last($this->total)), $moves);
		asort($range);
		return $range;
	}

	private function limit(int $perPage): int {
		return min($this->defaultLimit, $perPage) ?: $this->defaultLimit;
	}

	private function next(int $current, int $last): int {
		return min($last, $current + 1);
	}

	private function previous(int $current): int {
		return max(self::BASE, $current - 1);
	}

	private function last(int $total): int {
		return (int) ceil($total / $this->limit($this->perPage));
	}

	private function current(int $current): int {
		return max(min($current, $this->last($this->total)), self::BASE);
	}
}