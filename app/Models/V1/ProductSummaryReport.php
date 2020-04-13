<?php

declare(strict_types=1);

namespace App\Models\V1;

use DateTimeInterface;
use Illuminate\Database\Eloquent\Model;

/**
 * Class ProductSummaryReport
 * @package App\Classes\V1
 */
class ProductSummaryReport extends Model
{
    /**
     * @var string
     */
    public $product;

    /**
     * @var float
     */
    public $ean;

    /**
     * @var float
     */
    public $base_price;

    /**
     * @var float
     */
    public $min;

    /**
     * @var float
     */
    public $max;

    /**
     * @var float
     */
    public $average;

    /**
     * @var string
     */
    public $quantity_stores;

    /**
     * DateTime
     */
    public $created_at;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'product',
        'ean',
        'base_price',
        'min',
        'max',
        'average',
        'quantity_stores',
        'created_at',
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
        'created_at',
    ];

    /**
     * Prepare a date for array / JSON serialization.
     *
     * @param  DateTimeInterface $date
     * @return string
     */
    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('d/m/Y H:i:s');
    }
}
