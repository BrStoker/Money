<?php

namespace App\Http\Controllers\Admin\Charts;

use Backpack\CRUD\app\Http\Controllers\ChartController;
use ConsoleTVs\Charts\Classes\Chartjs\Chart;

class WeeklyUsersChartController extends ChartController
{
    public function setup()
    {

        $this->chart = new Chart();

        //$this->chart->labels([
        //    'Сегодня','Неделя','Месяц'
        //]);

        $this->chart->load(backpack_url('charts/weekly-users'));

        //$this->chart->minimalist();
        //$this->chart->displayLegend(false);
    
    }
    
    public function data()
    {

        $created_today = \App\Models\User::whereDate('created_at', today())->count(); 

        $created_week = \App\Models\User::whereBetween('created_at', [today()->subDays(7), today()])->count(); 

        $created_month = \App\Models\User::whereBetween('created_at', [today()->subDays(30), today()])->count(); 

        $this->chart->dataset('Сегодня', 'bar', [
            $created_today,
        ])->color('rgba(205, 32, 31, 1)') ->backgroundColor('rgba(205, 32, 31, 0.4)');

        $this->chart->dataset('Неделя', 'bar', [
            $created_week,
        ])->color('rgba(205, 32, 31, 1)') ->backgroundColor('rgba(205, 32, 31, 0.6)');

        $this->chart->dataset('Месяц', 'bar', [
            $created_month,
        ])->color('rgba(205, 32, 31, 1)') ->backgroundColor('rgba(205, 32, 31, 0.8)');
     
    }
}