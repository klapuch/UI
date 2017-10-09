<?php
declare(strict_types = 1);
namespace Klapuch\UI;

use Klapuch\Output;

/**
 * Fake
 */
final class FakePagination implements Pagination {
	private $range;

	public function __construct(array $range = null) {
		$this->range = $range;
	}

	public function print(Output\Format $format): Output\Format {
		return $format;
	}

	public function range(): array {
		return $this->range;
	}
}
