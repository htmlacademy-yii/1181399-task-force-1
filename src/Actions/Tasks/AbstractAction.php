<?php

namespace Htmlacademy\Actions\Tasks;

abstract class AbstractAction
{
    /**
     * Проверяет, может ли пользователь осуществить данное действие
     *
     * @param int $authorId
     * @param int|null $executorId
     * @param int $userId
     * @return bool True, если пользователь имеет право на действие
     */
    abstract public function can(int $authorId, ?int $executorId, int $userId): bool;

    /**
     * @return string Локализованное имя действия
     */
    abstract public static function getName(): string;

    /**
     * @return string Нелокализованное название действия в нашей системе
     */
    abstract public static function getSlug(): string;

    abstract public static function nextStatus(): string;
}
