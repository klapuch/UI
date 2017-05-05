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

final class PersistentFlashMessage extends Tester\TestCase {
	private $sessions = [];
	/** @var \Klapuch\UI\Message */
	private $message;

	public function setUp() {
		$this->message = new UI\PersistentFlashMessage($this->sessions);
	}

	public function testSingleFlashing() {
		$this->message->flash('fine', 'success');
		Assert::count(1, $this->sessions);
		Assert::same(['success' => 'fine'], current(current($this->sessions)));
	}

	public function testMultipleFlashingWithoutOverwriting() {
		$this->message->flash('fine', 'success');
		$this->message->flash('wrong', 'danger');
		Assert::count(2, $this->sessions['_flashMessage']);
		Assert::same(['success' => 'fine'], current(current($this->sessions)));
		next($this->sessions['_flashMessage']);
		Assert::same(['danger' => 'wrong'], current(current($this->sessions)));
	}

	public function testMultipleSameFlashingWithoutOverwriting() {
		$this->message->flash('fine', 'success');
		$this->message->flash('fine', 'success');
		Assert::count(2, $this->sessions['_flashMessage']);
		Assert::same(['success' => 'fine'], current(current($this->sessions)));
		next($this->sessions['_flashMessage']);
		Assert::same(['success' => 'fine'], current(current($this->sessions)));
	}

	public function testEmptyPrinting() {
		$format = new Output\FakeFormat('');
		Assert::same($format, $this->message->print($format));
	}

	public function testPrintingSingleMessage() {
		$this->message->flash('fine', 'success,');
		Assert::same(
			'success,fine',
			$this->message->print(new Output\ArrayFormat([]))->serialization()
		);
	}

	public function testPrintingMultipleMessagesOneByOne() {
		$this->message->flash('fine', 'success,');
		$this->message->flash('wrong', 'danger,');
		Assert::same(
			'success,fine',
			$this->message->print(new Output\ArrayFormat([]))->serialization()
		);
		Assert::same(
			'danger,wrong',
			$this->message->print(new Output\ArrayFormat([]))->serialization()
		);
	}

	public function testFlashingAfterRemovingWithoutDifferentBehavior() {
		$this->message->flash('wrong', 'danger');
		$this->message->print(new Output\FakeFormat(''));
		$this->message->flash('cool', 'warning');
		Assert::count(1, $this->sessions);
		Assert::same(['warning' => 'cool'], current(current($this->sessions)));
	}

	public function testRemovingOnlyMessages() {
		$this->sessions['foo'] = 'bar';
		$this->sessions['bar'] = 'foo';
		$initialState = $this->sessions;
		$this->message->flash('fine', 'success');
		$this->message->flash('wrong', 'danger');
		$this->message->print(new Output\FakeFormat(''));
		$this->message->print(new Output\FakeFormat(''));
		Assert::same($initialState + ['_flashMessage' => []], $this->sessions);
	}

	public function testPrintingMoreThanPossibleWithPassedFormat() {
		$this->message->flash('fine', 'success');
		$this->message->print(new Output\FakeFormat(''));
		$format = new Output\FakeFormat();
		Assert::same($format, $this->message->print($format));
	}
}

(new PersistentFlashMessage())->run();