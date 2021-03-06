<?php

namespace Htmlacademy\Database;

use SplFileObject;

final class CsvParser
{
    private $input;
    private $addFields;
    private $tableName;
    private $output;
    private $combinedFile;

    /**
     * CsvParser constructor.
     * @param SplFileObject $inputCsv
     * @param array $addFields
     * @param string $tableName
     * @param SplFileObject $output
     */
    public function __construct(SplFileObject $inputCsv, array $addFields, string $tableName, SplFileObject $output)
    {
        $this->input = $inputCsv;
        $this->addFields = $addFields;
        $this->tableName = $tableName;
        $this->output = $output;
    }

    /**
     * Соединяет файлы
     * @param SplFileObject $combinedFile
     */
    public function combineWith(SplFileObject $combinedFile)
    {
        $this->combinedFile = $combinedFile;
    }

    /**
     * Приводит к скл
     *
     * @return string
     */
    public function toSql()
    {
        $outputFile = $this->output;
        $columns = $this->getColumns();

        $outputFile->fwrite('set autocommit=0;');
        $outputFile->fwrite('begin;');

        while ($line = $this->getLine()) {
            if (count($line) < 1 || $line[0] === null) {
                continue;
            }
            $outputLine = $this->prepareStatement($columns, $line);
            $outputFile->fwrite($outputLine);
        }

        $outputFile->fwrite('commit;');
        $outputFile->fwrite('set autocommit=1;');

        return $outputFile->getPathname();
    }

    /**
     * Колонки
     * @return array
     */
    private function getColumns()
    {
        $columns = $this->getColumnsFromFile($this->input);

        if (isset($this->combinedFile)) {
            $combinedColumns = $this->getColumnsFromFile($this->combinedFile);
            $columns = array_merge($columns, $combinedColumns);
        }

        if (isset($this->addFields)) {
            $columns = array_merge($columns, array_keys($this->addFields));
        }

        $output = [];
        foreach ($columns as $column) {
            // Убираем ZWNBSP
            $column = str_replace('﻿', '', $column);
            $output[] = "`{$column}`";
        }

        return $output;
    }

    /**
     * Подготавливает sql запрос
     * @param array $columns
     * @param array $line
     * @return string
     */
    private function prepareStatement(array $columns, array $line): string
    {
        $cols = implode(', ', $columns);
        $values = [];
        foreach ($line as $key => $value) {
            if (is_string($value)) {
                $values[] = "'$value'";
                continue;
            }
            $values[] = $value;
        }
        $values = implode(', ', $values);
        return "insert into {$this->tableName} ({$cols}) values ($values);" . PHP_EOL;
    }

    /**
     * возвращает следующую строку
     * @return array|false
     * @throws \Exception
     */
    private function getLine()
    {
        if ($this->input->eof()) {
            return false;
        }

        $line = $this->input->fgetcsv();
        if (isset($this->combinedFile)) {
            $combinedLine = $this->combinedFile->fgetcsv();
            $line = array_merge($line, $combinedLine);
        }

        if (isset($this->addFields)) {
            foreach ($this->addFields as $field => $value) {
                $line[] = random_int($value[0], $value[1]);
            }
        }

        return $line;
    }

    /**
     * Возвращает колонки из файла.
     * @param SplFileObject $file
     * @return array|false
     */
    private function getColumnsFromFile(SplFileObject $file)
    {
        $file->seek(0);
        return $file->fgetcsv();
    }
}
