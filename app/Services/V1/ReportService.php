<?php

declare(strict_types=1);

namespace App\Services\V1;

use App\Classes\V1\Channels\GoogleReport;
use App\Exports\EiPriceReport;
use App\Models\V1\Channel;
use App\Models\V1\Product;
use App\Models\V1\ProductSummaryReport;
use App\Models\V1\StoreReport;
use DateTime;
use Exception;
use Illuminate\Http\Response;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Facades\Excel;

/**
 * Class ReportService
 * @package App\Services\V1\Auth
 */
class ReportService
{
    /**
     * @var Collection
     */
    protected $baseProducts;

    /**
     * @var Collection
     */
    protected $products;

    /**
     * @var Collection
     */
    protected $channels;

    /**
     * @var Collection
     */
    protected $data;

    /**
     * ReportService constructor.
     * @param Channel $channel
     * @param Product $product
     * @throws Exception
     */
    public function __construct(Channel $channel, Product $product)
    {
        $this->channels     = $channel->get();
        $this->baseProducts = $product->select(['ean', 'description', 'price'])->get();
        $this->products     = $product->get();
        $this->data         = collect([]);

        $this->prepareDataReport();
    }

    /**
     * Download the report
     * @return \Maatwebsite\Excel\BinaryFileResponse|\Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function reportDownload()
    {
        $this->prepareProductsReportData();
        $this->prepareStoresReportData();
        $this->prepareProductsSummaryReportData();

        $dateTime = (new DateTime('now'))->format('Y_m_d_H_i_s');

        return Excel::download(new EiPriceReport($this->data), "eiprice_report_{$dateTime}.xlsx");
    }

    /**
     * Prepare all report data from the channels with web scrapping
     * @throws Exception
     */
    private function prepareDataReport()
    {
        foreach ($this->products as $product) {
            if (is_null($product->channels ?? null)) {
                $product->setAttribute('channels', collect([]));
            }

            foreach ($this->channels as $channel) {
                /** @var Channel $ch */
                $ch = new Channel($channel->toArray());

                $ch->setAttribute('report', $this->getChannelReport($ch));

                if (is_null($ch->stores ?? null)) {
                    $ch->setAttribute('stores', collect([]));
                }

                $ch->setAttribute(
                    'stores',
                    $ch->stores->merge(($ch->report)::getStores($product))
                );

                $product->setAttribute(
                    'channels',
                    $product->channels->merge(collect([$ch]))
                );
            }
        }
    }

    /**
     * Get the custom client (channel report) to get the (stores x products)
     * For now we just have Google channel set up, but in the future you can create new clients (channels)
     * @param Channel $channel
     * @return string
     * @throws Exception
     */
    private function getChannelReport(Channel $channel): string
    {
        $report = null;

        switch ($channel->key ?? null) {
            case 'google':
                $report = GoogleReport::class;
                break;
        }

        if (is_null($report)) {
            throw new Exception(
                sprintf('Channel Report not implemented: %s', ($channel->key ?? null)),
                Response::HTTP_NOT_IMPLEMENTED
            );
        }

        return $report;
    }

    /**
     * Prepare data to create the first worksheet (Products) for the report
     */
    private function prepareProductsReportData()
    {
        $this->data->put('productsReportData', $this->baseProducts);
    }

    /**
     * Prepare data to create the second worksheet (Stores) for the report
     */
    private function prepareStoresReportData()
    {
        $storesReport = collect([]);

        $this->products->map(function ($product) use (&$storesReport) {
            $product->channels->map(function ($channel) use ($product, &$storesReport) {
                $channel->stores->map(function ($store) use ($product, $channel, &$storesReport) {
                    $basePrice    = (float) $product->price;
                    $channelPrice = (float) $store->product->price;
                    $now          = new DateTime('now');

                    $storeReport = new StoreReport([
                        'product'       => $product->description,
                        'ean'           => $product->ean,
                        'channel'       => $channel->name,
                        'store'         => $store->name,
                        'base_price'    => $basePrice,
                        'channel_price' => $channelPrice,
                        'gap'           => abs(round((float) ((($channelPrice / $basePrice) * 100) - 100), 2)),
                        'status'        => $this->getStatus($basePrice, $channelPrice),
                        'created_at'    => $now,
                    ]);

                    $storesReport = $storesReport->merge(collect([$storeReport]));
                });
            });
        });

        $this->data->put('storesReportData', $storesReport);
    }

    /**
     * Get status based in following rules:
     * MAIS CARO:    QUANDO O PRECO REF > PRECO CANAL
     * MAIS BARATO:	 QUANDO O PRECO REF < PRECO CANAL
     * IGUAL:        QUANDO O PRECO REF = PRECO CANAL
     *
     * @param float $basePrice
     * @param float $channelPrice
     * @return string
     */
    private function getStatus(float $basePrice, float $channelPrice): string
    {
        switch (true) {
            case $basePrice > $channelPrice:
                $status = 'MAIS CARO';
                break;
            case $basePrice < $channelPrice:
                $status = 'MAIS BARATO';
                break;
            default:
                $status = 'IGUAL';
        }

        return $status;
    }

    /**
     * Prepare data to create the third worksheet (Products summary) for the report
     */
    private function prepareProductsSummaryReportData()
    {
        $productsSummaryReport = collect([]);

        $this->baseProducts->map(function ($product) use (&$productsSummaryReport) {
            $basePrice = (float) $product->price;
            $now = new DateTime('now');

            $productIndex = $this->products->search(function ($p) use ($product) {
                return $p->ean === ($product->ean ?? null);
            });

            $currentProduct = $this->products->get($productIndex);

            $min = (float) $currentProduct->channels->map->stores->map(function ($s) {
                return $s->map->product->min('price');
            })->first();

            $max = (float) $currentProduct->channels->map->stores->map(function ($s) {
                return $s->map->product->max('price');
            })->first();

            $productSummaryReport = new ProductSummaryReport([
                'product'         => $product->description,
                'ean'             => $product->ean,
                'base_price'      => $basePrice,
                'min'             => $min,
                'max'             => $max,
                'average'         => round((float) (($min + $max) / 2), 2),
                'quantity_stores' => $currentProduct->channels->sum(function ($channels) {
                    return $channels->stores->count();
                }),
                'created_at'      => $now,
            ]);

            $productsSummaryReport = $productsSummaryReport->merge(collect([$productSummaryReport]));
        });

        $this->data->put('productsSummaryReportData', $productsSummaryReport);
    }
}
