<?php

namespace App\Http\Controllers\Admin\Charts;

use Backpack\CRUD\app\Http\Controllers\ChartController;
use ConsoleTVs\Charts\Classes\Chartjs\Chart;

class WeeklyArticleChartController extends ChartController
{
    public function setup()
    {

        $this->chart = new Chart();
        $this->chart->load(backpack_url('charts/weekly-article'));

        //$this->chart->minimalist(false);
        //$this->chart->displayLegend(true);
    
    }
    
    public function data()
    {

        $created_today = \App\Models\Article::whereDate('created_at', today())->count(); 

        $created_week = \App\Models\Article::whereBetween('created_at', [today()->subDays(7), today()])->count(); 

        $created_month = \App\Models\Article::whereBetween('created_at', [today()->subDays(30), today()])->count(); 

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