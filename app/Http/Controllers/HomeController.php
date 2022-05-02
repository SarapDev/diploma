<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;

final class HomeController extends Controller
{
    public function welcome(): Factory|View|Application
    {
        $viewData = $this->loadViewData();

        return view('welcome', $viewData);
    }
}
