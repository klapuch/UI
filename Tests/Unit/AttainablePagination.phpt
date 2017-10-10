<?php
declare(strict_types = 1);
/**
 * @testCase
 * @phpVersion > 7.1
 */
namespace Klapuch\UI\Unit;

use Klapuch\Output;
use Klapuch\UI;
use Tester;
use Tester\Assert;

require __DIR__ . '/../bootstrap.php';

final class AttainablePagination extends Tester\TestCase {
	/**
	 * 100 / 50 = 2
	 * Given page is more than 2
	 */
	public function testCurrentOverTotalLimitFallingToLast() {
		Assert::same(
			'|first|1||prev|1||next|2||last|2|',
			(new UI\AttainablePagination(
				9,
				50,
				100
			))->print(new Output\FakeFormat(''))->serialization()
		);
	}

	public function testCurrentUnderBaseLimitFallingToFirst() {
		Assert::same(
			'|first|1||prev|1||next|2||last|2|',
			(new UI\AttainablePagination(
				-3,
				50,
				100
			))->print(new Output\FakeFormat(''))->serialization()
		);
	}

	public function testOversteppedLimitFallingEverythingToBegin() {
		Assert::same(
			'|first|1||prev|1||next|1||last|1|',
			(new UI\AttainablePagination(
				1,
				100,
				9
			))->print(new Output\FakeFormat(''))->serialization()
		);
	}

	public function testAppliedMaxLimit() {
		Assert::same(
			'|first|1||prev|1||next|2||last|200|',
			(new UI\AttainablePagination(
				1,
				5000,
				20000,
				100
			))->print(new Output\FakeFormat(''))->serialization()
		);
	}

	public function testFirstPage() {
		Assert::same(
			'|first|1||prev|1||next|2||last|2|',
			(new UI\AttainablePagination(
				1,
				50,
				100
			))->print(new Output\FakeFormat(''))->serialization()
		);
	}

	/**
	 * 50 / 50 = 1 = current page (1)
	 */
	public function testFirstPageOnEdge() {
		Assert::same(
			'|first|1||prev|1||next|1||last|1|',
			(new UI\AttainablePagination(
				1,
				50,
				50
			))->print(new Output\FakeFormat(''))->serialization()
		);
	}

	public function testLastPage() {
		Assert::same(
			'|first|1||prev|1||next|2||last|2|',
			(new UI\AttainablePagination(
				1,
				50,
				100
			))->print(new Output\FakeFormat(''))->serialization()
		);
	}

	/**
	 * 100 / 50 = 2 = current page (2)
	 */
	public function testLastPageOnEdge() {
		Assert::same(
			'|first|1||prev|1||next|2||last|2|',
			(new UI\AttainablePagination(
				2,
				50,
				100
			))->print(new Output\FakeFormat(''))->serialization()
		);
	}

	public function testLastPageRoundedUp() {
		Assert::same(
			'|first|1||prev|3||next|5||last|6|',
			(new UI\AttainablePagination(
				4,
				50,
				262
			))->print(new Output\FakeFormat(''))->serialization()
		);
	}

	public function testUnrealLimitWithMaxLimitFallback() {
		Assert::same(
			'|first|1||prev|1||next|2||last|2|',
			(new UI\AttainablePagination(
				1,
				0,
				200,
				100
			))->print(new Output\FakeFormat(''))->serialization()
		);
	}

	public function testRangeFromTo() {
		Assert::same(
			[
				'first' => 1,
				'prev' => 2,
				2 => 3,
				'next' => 4,
				'last' => 5,
			],
			(new UI\AttainablePagination(3, 5, 25))->range()
		);
	}
}


(new AttainablePagination())->run();