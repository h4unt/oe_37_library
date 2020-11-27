<?php

namespace App\Http\Controllers;

use App\Charts\BorrowMonthChart;
use App\Charts\BorrowQuarterChart;
use App\Charts\BorrowYearChart;
use App\Enums\BorrowStatus;
use App\Models\Borrow;
use Illuminate\Http\Request;
use App\Models\User;
use Carbon\Carbon;
use App\Repositories\RepositoryInterface\BorrowRepositoryInterface;
use App\Repositories\RepositoryInterface\UserRepositoryInterface;

class DashboardController extends Controller
{
    protected $borrowRepository;
    protected $userRepository;

    public function __construct(
        BorrowRepositoryInterface $borrowRepository,
        UserRepositoryInterface $userRepository
    )
    {
        $this->borrowRepository = $borrowRepository;
        $this->userRepository = $userRepository;
    }

    public function index()
    {
        $dataMonth = $this->borrowRepository->getDataMonth();
        $dataYear = $this->borrowRepository->getDataYear();
        
        $monthChart = new BorrowMonthChart;
        $monthChart->labels($dataMonth['mLabels']);
        $monthChart->dataset(trans('admin.chart.labelMonth'), 'bar', $dataMonth['mdata'])->backgroundColor('red');

        $quarterChart = new BorrowQuarterChart;
        $quarterChart->labels($this->borrowRepository->getLabelsQuarter());
        $quarterChart->dataset(trans('admin.chart.labelQuarter'), 'line', $this->borrowRepository->countQuarter());

        $yearChart = new BorrowYearChart;
        $yearChart->labels($dataYear['yLabels']);
        $yearChart->dataset(trans('admin.chart.labelYear'), 'line', $dataYear['ydata'])->backgroundColor('orange');

        return view('admin', compact('monthChart', 'quarterChart', 'yearChart'));
    }

    public function userCountChart()
    {
        $user_count_chart = $this->userRepository->userCountChart();
        $arrLabels = [];
        $arrData = [];
        foreach ($user_count_chart as $labels => $data) {
            $arrLabels[] = trans('admin.chart.month') . $labels;
            $arrData[] = $data;
        }

        return response()->json(array("labels" => $arrLabels, "data" => $arrData));
    }
}
