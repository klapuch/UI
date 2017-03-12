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
	public function testThrowingOnOverLimit(int $current) {
		Assert::exception(function() use($current) {
			(new UI\LimitedPagination(
				new UI\FakePagination(),
				$current,
				100
			))->print(new Output\FakeFormat());
		}, \OverflowException::class, 'Max limit 100 has been overstepped');
	}

	/**
	 * @dataProvider inRangeLimits
	 */
	public function testPassingWithLimitInRange(int $current) {
		Assert::noError(function() use($current) {
			(new UI\LimitedPagination(
				new UI\FakePagination(),
				$current,
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