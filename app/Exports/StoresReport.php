<?php

declare(strict_types=1);

namespace App\Exports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;

/**
 * Class StoresReport
 * @package App\Exports
 */
class StoresReport implements WithHeadings, WithTitle, ShouldAutoSize, FromCollection
{
    use Exportable;

    /**
     * @var Collection
     */
    protected $data;

    /**
     * StoresReport constructor.
     * @param Collection $data
     */
    public function __construct(Collection $data)
    {
        $this->data = $data;
    }

    /**
     * @return Collection
     */
    public function collection(): Collection
    {
        return $this->data;
    }

    /**
     * @return string
     */
    public function title(): string
    {
        return 'Stores report';
    }

    public function headings(): array
    {
        return [
            'Product',
            'EAN',
            'Channel',
            'Store',
            'Base price',
            'Channel price',
            'GAP',
            'Status',
            'Created date',
        ];
    }
}
