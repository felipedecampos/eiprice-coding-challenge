<?php

declare(strict_types=1);

namespace App\Exports;

use Exception;
use Illuminate\Http\Response;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

/**
 * Class EiPriceReport
 * @package App\Exports
 */
class EiPriceReport implements WithMultipleSheets
{
    use Exportable;

    /**
     * @var Collection
     */
    protected $data;

    public function __construct(Collection $data)
    {
        $this->data = $data;
    }

    /**
     * @return array
     */
    public function sheets(): array
    {
        $sheets = [];

        $this->data->map(function ($report, $key) use (&$sheets) {
            switch ($key) {
                case 'productsReportData':
                    $sheets[] = new ProductsReport($report);
                    break;
                case 'storesReportData':
                    $sheets[] = new StoresReport($report);
                    break;
                case 'productsSummaryReportData':
                    $sheets[] = new ProductsSummaryReport($report);
                    break;
                default:
                    throw new Exception(
                        sprintf('Worksheet Report not implemented: %s', ($key ?? null)),
                        Response::HTTP_NOT_IMPLEMENTED
                    );
            }
        });

        return $sheets;
    }
}
