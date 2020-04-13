<?php

declare(strict_types=1);

namespace App\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use App\Services\V1\ReportService;

/**
 * Class ReportController
 * @package App\Http\Controllers\V1
 */
class ReportController extends Controller
{
    /**
     * @var ReportService
     */
    protected $service;

    /**
     * ReportController constructor.
     * @param ReportService $service
     */
    public function __construct(ReportService $service)
    {
        $this->service = $service;
    }

    /**
     * Download the report
     * @return \Maatwebsite\Excel\BinaryFileResponse|\Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function reportDownload()
    {
        return $this->service->reportDownload();
    }
}
