<?php
declare(strict_types = 1);
/**
 * @testCase
 * @phpVersion > 7.1
 */
namespace Klapuch\UI\Unit;

use Klapuch\UI;
use Klapuch\Output;
use Tester;
use Tester\Assert;

require __DIR__ . '/../bootstrap.php';

final class LimitedPagination extends Tester\TestCase {
	/**
	 * @dataProvider outOfRangeLimits
	 */
	public function testThrowingOnOverLimit(int $limit) {
		Assert::exception(function() use($limit) {
			(new UI\LimitedPagination(
				new UI\FakePagination(),
				$limit,
				100
			))->print(new Output\FakeFormat());
		}, \OverflowException::class, 'Max limit 100 has been overstepped');
	}

	/**
	 * @dataProvider inRangeLimits
	 */
	public function testPassingWithLimitInRange(int $limit) {
		Assert::noError(function() use($limit) {
			(new UI\LimitedPagination(
				new UI\FakePagination(),
				$limit,
				100
			))->print(new Output\FakeFormat());
		});
	}

	protected function inRangeLimits(): array {
		return [[0], [99], [100]];
	}

	protected function outOfRangeLimits(): array {
		return [[101], [1000]];
	}
}


(new LimitedPagination())->run();