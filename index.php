<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once('./vendor/autoload.php');
use Htmlacademy\Task;

$task = new Task(1,1);
$task->status = Task::STATUS_NEW;

assert($task->status === Task::STATUS_NEW, 'Статус должен совпадать');
assert($task->getActions() == [Task::ACTION_ACCEPT, Task::ACTION_REMOVE], 'Действия должны совпадать');
assert($task->getNextStatus(Task::ACTION_REMOVE) === Task::STATUS_CANCELED, 'Статус должен стать "Отменен"');
assert($task->getNextStatus(Task::ACTION_ACCEPT) === Task::STATUS_WIP, 'Статус должен стать "В работе"');

$task->status = Task::STATUS_CANCELED;
assert($task->getActions() === []);
assert($task->getNextStatus(Task::ACTION_REMOVE) === null);

$task->status = Task::STATUS_FAILED;
assert($task->getActions() === []);
assert($task->getNextStatus(Task::ACTION_REMOVE) === null);

$task->status = Task::STATUS_DONE;
assert($task->getActions() === []);
assert($task->getNextStatus(Task::ACTION_REMOVE) === null);

$task->status = Task::STATUS_WIP;
assert($task->getActions() == [Task::ACTION_DONE, Task::ACTION_DECLINE]);
assert($task->getNextStatus(Task::ACTION_DONE) === Task::STATUS_DONE);
assert($task->getNextStatus(Task::ACTION_DECLINE) === Task::STATUS_FAILED);
