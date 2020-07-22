<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require('vendor/autoload.php');
use Htmlacademy\Models\Task;

$task = new Task(1,2);

assert($task->getActions(1) === null);

assert($task->setStatus('non-existent') === false);
assert($task->setStatus(Task::STATUS_NEW));

assert($task->getActions(3) === []);

assert($task->getActions(1) == [Task::ACTION_ACCEPT], 'Действия должны совпадать');
assert($task->getActions(2) == [Task::ACTION_REMOVE]);
assert($task->getNextStatus(Task::ACTION_REMOVE) === Task::STATUS_CANCELED, 'Статус должен стать "Отменен"');
assert($task->getNextStatus(Task::ACTION_ACCEPT) === Task::STATUS_WIP, 'Статус должен стать "В работе"');

$task->setStatus(Task::STATUS_CANCELED);
assert($task->getActions(1) === [] && $task->getActions(2) === []);
assert($task->getNextStatus(Task::ACTION_REMOVE) === null);

$task->setStatus(Task::STATUS_FAILED);
assert($task->getActions(1) === [] && $task->getActions(2) === []);
assert($task->getNextStatus(Task::ACTION_REMOVE) === null);

$task->setStatus(Task::STATUS_DONE);
assert($task->getActions(1) === [] && $task->getActions(2) === []);
assert($task->getNextStatus(Task::ACTION_REMOVE) === null);

$task->setStatus(Task::STATUS_WIP);
assert($task->getActions(1) == [Task::ACTION_DONE]);
assert($task->getNextStatus(Task::ACTION_DONE) === Task::STATUS_DONE);
assert($task->getActions(2) == [Task::ACTION_DECLINE]);
assert($task->getNextStatus(Task::ACTION_DECLINE) === Task::STATUS_FAILED);
