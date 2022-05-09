<?php

declare(strict_types=1);

namespace App\Repositories\Students;

use App\Models\Student;
use Illuminate\Support\Facades\DB;

final class StudentRepository implements IStudentRepository
{
    public function butchCreate(array $students): void
    {
        try {
            Student::insert($students);
        } catch (\Exception $e) {
            dd($e->getMessage());
        }
    }

    public function saveStudentClass(array $data): void
    {
        try {
            DB::table('student_class')->insert($data);
        } catch (\Exception $e) {
            dd($e);
        }
    }

    public function getAllStudentsByNamesArray(array $names): array
    {
        return Student::whereIn('fullname', $names)->get()->toArray();
    }
}
