<?php

declare(strict_types=1);

namespace App\Repositories\Students;

interface IStudentRepository
{
    public function butchCreate(array $students): void;

    public function saveStudentClass(array $data): void;

    public function getAllStudentsByNamesArray(array $names): array;
}
