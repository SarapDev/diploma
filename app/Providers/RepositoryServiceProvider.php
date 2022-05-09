<?php

declare(strict_types=1);

namespace App\Providers;

use App\Repositories\Attendance\AttendanceRepository;
use App\Repositories\Attendance\IAttendanceRepository;
use App\Repositories\Classes\ClassRepository;
use App\Repositories\Classes\IClassRepository;
use App\Repositories\Students\IStudentRepository;
use App\Repositories\Students\StudentRepository;
use Illuminate\Support\ServiceProvider;

final class RepositoryServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(IAttendanceRepository::class, AttendanceRepository::class);

        $this->app->bind(IClassRepository::class, ClassRepository::class);

        $this->app->bind(IStudentRepository::class, StudentRepository::class);
    }

    public function boot(): void
    {
        //
    }
}
