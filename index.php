<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require('vendor/autoload.php');

use Htmlacademy\Enums\Actions;
use Htmlacademy\Models\Task;

$task = new Task(1,2);

assert($task->getActions(1) === null);

assert($task->setStatus('non-existent') === false);
assert($task->setStatus(Task::STATUS_NEW));

assert($task->getActions(3) === []);

assert($task->getActions(1) == [Actions::ACCEPT], 'Действия должны совпадать');
assert($task->getActions(2) == [Actions::REMOVE]);
assert($task->getNextStatus(Actions::REMOVE) === Task::STATUS_CANCELED, 'Статус должен стать "Отменен"');
assert($task->getNextStatus(Actions::ACCEPT) === Task::STATUS_WIP, 'Статус должен стать "В работе"');

$task->setStatus(Task::STATUS_CANCELED);
assert($task->getActions(1) === [] && $task->getActions(2) === []);
assert($task->getNextStatus(Actions::REMOVE) === null);

$task->setStatus(Task::STATUS_FAILED);
assert($task->getActions(1) === [] && $task->getActions(2) === []);
assert($task->getNextStatus(Actions::REMOVE) === null);

$task->setStatus(Task::STATUS_DONE);
assert($task->getActions(1) === [] && $task->getActions(2) === []);
assert($task->getNextStatus(Actions::REMOVE) === null);

$task->setStatus(Task::STATUS_WIP);
assert($task->getActions(1) == [Actions::DONE]);
assert($task->getNextStatus(Actions::DONE) === Task::STATUS_DONE);
assert($task->getActions(2) == [Actions::DECLINE]);
assert($task->getNextStatus(Actions::DECLINE) === Task::STATUS_FAILED);
