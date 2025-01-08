<?php

namespace App\Charts;

use ArielMejiaDev\LarapexCharts\LarapexChart;

class PieChartTopSellingCategory
{
    protected $chart;

    public function __construct(LarapexChart $chart)
    {
        $this->chart = $chart;
    }

    public function build($data): \ArielMejiaDev\LarapexCharts\PieChart
    {
        $labels = [];
        $totals = [];

        // Extract labels and totals from the data array
        foreach ($data as $categoryData) {
            $labels[] = $categoryData['name'];
            $totals[] = array_sum($categoryData['total']);
        }

        return $this->chart->pieChart()
            // ->setTitle('Top 3 scorers of the team.')
            // ->setSubtitle('Season 2021.')
            ->addData($totals)
            ->setLabels($labels);
    }
}
