<?php

require('./vendor/autoload.php');

$inputParameters = [
    [
        'filename' => 'cities',
        'addFields' => [],
        'tableName' => 'cities'
    ],
    [
        'filename' => 'users',
        'addFields' => ['city_id' => [1, 1000]],
        'tableName' => 'users',
        'combine' => 'profiles'
    ],
    [
        'filename' => 'categories',
        'addFields' => [],
        'tableName' => 'categories'
    ],
    [
        'filename' => 'tasks',
        'addFields' => ['author_id' => [1, 19], 'city_id' => [1, 1000]],
        'tableName' => 'tasks'
    ],
    [
        'filename' => 'replies',
        'addFields' => ['user_id' => [1, 19], 'task_id' => [1, 10]],
        'tableName' => 'applications'
    ],
    [
        'filename' => 'opinions',
        'addFields' => ['user_id' => [1, 19], 'task_id' => [1, 10], 'author_id' => [1, 19]],
        'tableName' => 'feedback'
    ],
];

$outputFile = new SplFileObject(__DIR__ . '/seed.sql', 'w');
foreach ($inputParameters as $parameters)
{
    $filename = __DIR__ . "/data/{$parameters['filename']}.csv";
    $file = new SplFileObject($filename);
    $parser = new \Htmlacademy\Database\CsvParser($file, $parameters['addFields'], $parameters['tableName'], $outputFile);
    if (isset($parameters['combine'])) {
        $parser->combineWith(new SplFileObject(__DIR__ . "/data/{$parameters['combine']}.csv"));
    }

    $parser->toSql();
}
