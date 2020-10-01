<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require('vendor/autoload.php');

use Htmlacademy\Enums\Actions;
use Htmlacademy\Models\TaskStateMachine;

$task = new TaskStateMachine(1, 2);

assert($task->getActions(1) === null);

assert($task->setStatus('non-existent') === false);
assert($task->setStatus(TaskStateMachine::STATUS_NEW));

assert($task->getActions(3) === []);

assert($task->getActions(1) == [Actions::ACCEPT], 'Действия должны совпадать');
assert($task->getActions(2) == [Actions::REMOVE]);
assert($task->getNextStatus(Actions::REMOVE) === TaskStateMachine::STATUS_CANCELED, 'Статус должен стать "Отменен"');
assert($task->getNextStatus(Actions::ACCEPT) === TaskStateMachine::STATUS_WIP, 'Статус должен стать "В работе"');

$task->setStatus(TaskStateMachine::STATUS_CANCELED);
assert($task->getActions(1) === [] && $task->getActions(2) === []);
assert($task->getNextStatus(Actions::REMOVE) === null);

$task->setStatus(TaskStateMachine::STATUS_FAILED);
assert($task->getActions(1) === [] && $task->getActions(2) === []);
assert($task->getNextStatus(Actions::REMOVE) === null);

$task->setStatus(TaskStateMachine::STATUS_DONE);
assert($task->getActions(1) === [] && $task->getActions(2) === []);
assert($task->getNextStatus(Actions::REMOVE) === null);

$task->setStatus(TaskStateMachine::STATUS_WIP);
assert($task->getActions(1) == [Actions::DONE]);
assert($task->getNextStatus(Actions::DONE) === TaskStateMachine::STATUS_DONE);
assert($task->getActions(2) == [Actions::DECLINE]);
assert($task->getNextStatus(Actions::DECLINE) === TaskStateMachine::STATUS_FAILED);
