<?php
declare(strict_types = 1);
/**
 * @testCase
 * @phpVersion > 7.1.0
 */
namespace Klapuch\UI\Unit;

use Klapuch\Output;
use Klapuch\UI;
use Tester;
use Tester\Assert;

require __DIR__ . '/../bootstrap.php';

final class FlashMessages extends Tester\TestCase {
	public function testPrintingSingleMessage() {
		$storage = [];
		$messages = new UI\FlashMessages(new UI\PersistentFlashMessage($storage));
		$messages->flash('wrong', 'error,');
		Assert::same(
			'error,wrong',
			$messages->print(new Output\ArrayFormat([]))->serialization()
		);
	}

	public function testNoMessageToPrintAsPassedFormat() {
		$storage = [];
		$messages = new UI\FlashMessages(new UI\PersistentFlashMessage($storage));
		Assert::same(
			'',
			$messages->print(new Output\ArrayFormat([]))->serialization()
		);
	}

	public function testPrintingAllFlashedMessages() {
		$storage = [];
		$messages = new UI\FlashMessages(new UI\PersistentFlashMessage($storage));
		$messages->flash('fine - ', 'success,');
		$messages->flash('wrong', 'error,');
		Assert::same(
			'success,fine - error,wrong',
			$messages->print(new Output\ArrayFormat([]))->serialization()
		);
	}

	public function testPrintingUniqueMessages() {
		$storage = [];
		$messages = new UI\FlashMessages(new UI\PersistentFlashMessage($storage));
		$messages->flash('|fine|', '|success|');
		$messages->flash('|fine|', '|error|');
		$messages->flash('|wrong|', '|error|');
		$messages->flash('|wrong|', '|error|');
		Assert::same(
			'|success||fine||error||fine||error||wrong|',
			$messages->print(new Output\ArrayFormat([]))->serialization()
		);
	}
}

(new FlashMessages())->run();