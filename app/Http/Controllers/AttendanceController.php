<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Handlers\AttendanceHandler;
use App\Http\Handlers\AttendanceRecordHandler;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;

final class AttendanceController extends Controller
{
    public function __construct(
        public AttendanceHandler $attendanceHandler,
        public AttendanceRecordHandler $recordHandler
    ) {
    }

    public function list(): Factory|View|Application
    {
        return view('attendance', array_merge(['events' => $this->attendanceHandler->handle()], $this->loadViewData()));
    }

    public function report(string $event_id): Factory|View|Application
    {
        return view('report', array_merge(['users' => $this->recordHandler->handle($event_id)], $this->loadViewData()));
    }
}
