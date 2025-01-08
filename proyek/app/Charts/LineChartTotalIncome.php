<?php

namespace App\Charts;

use ArielMejiaDev\LarapexCharts\LarapexChart;

class LineChartTotalIncome
{
    protected $chart;

    public function __construct(LarapexChart $chart)
    {
        $this->chart = $chart;
    }

    public function build($data): \ArielMejiaDev\LarapexCharts\LineChart
    {
        return $this->chart->lineChart()
            // ->setTitle('Sales during 2021.')
            // ->setSubtitle('Physical sales vs Digital sales.')
            // ->addData('Physical sales', [40, 93, 35, 42, 18, 82])
            ->addData('Total Income', $data['total'])
            ->setXAxis($data['labels']);
    }
}
