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

final class AttainablePagination extends Tester\TestCase {
	/**
	 * 100 / 50 = 2
	 * Given page is more than 2
	 */
	public function testCurrentOverTotalLimitFallingToLast() {
		Assert::same(
			'|first|1||last|2||current|2||previous|1||next|2|',
			(new UI\AttainablePagination(
				9, 100, 50
			))->print(new Output\FakeFormat(''))->serialization()
		);
	}

	public function testCurrentUnderBaseLimitFallingToFirst() {
		Assert::same(
			'|first|1||last|2||current|1||previous|1||next|2|',
			(new UI\AttainablePagination(
				-3, 100, 50
			))->print(new Output\FakeFormat(''))->serialization()
		);
	}

	public function testOversteppedLimitFallingEverythingToBegin() {
		Assert::same(
			'|first|1||last|1||current|1||previous|1||next|1|',
			(new UI\AttainablePagination(
				1, 9, 100
			))->print(new Output\FakeFormat(''))->serialization()
		);
	}

	public function testFixedLimitConstraint() {
		Assert::same(
			'|first|1||last|20||current|1||previous|1||next|2|',
			(new UI\AttainablePagination(
				1, 2000, 98999
			))->print(new Output\FakeFormat(''))->serialization()
		);
	}

	public function testFirstPage() {
		Assert::same(
			'|first|1||last|2||current|1||previous|1||next|2|',
			(new UI\AttainablePagination(
				1, 100, 50
			))->print(new Output\FakeFormat(''))->serialization()
		);
	}

	/**
	 * 50 / 50 = 1 = current page (1)
	 */
	public function testFirstPageOnEdge() {
		Assert::same(
			'|first|1||last|1||current|1||previous|1||next|1|',
			(new UI\AttainablePagination(
				1, 50, 50
			))->print(new Output\FakeFormat(''))->serialization()
		);
	}

	public function testLastPage() {
		Assert::same(
			'|first|1||last|2||current|1||previous|1||next|2|',
			(new UI\AttainablePagination(
				1, 100, 50
			))->print(new Output\FakeFormat(''))->serialization()
		);
	}

	/**
	 * 100 / 50 = 2 = current page (2)
	 */
	public function testLastPageOnEdge() {
		Assert::same(
			'|first|1||last|2||current|2||previous|1||next|2|',
			(new UI\AttainablePagination(
				2, 100, 50
			))->print(new Output\FakeFormat(''))->serialization()
		);
	}

	public function testLastPageRoundedUp() {
		Assert::same(
			'|first|1||last|6||current|4||previous|3||next|5|',
			(new UI\AttainablePagination(
				4, 262, 50
			))->print(new Output\FakeFormat(''))->serialization()
		);
	}
}


(new AttainablePagination())->run();