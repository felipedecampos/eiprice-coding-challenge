<?php

declare(strict_types=1);

namespace App\Models\V1;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Store
 * @package App\Classes\V1
 */
class Store extends Model
{
    /**
     * @var string
     */
    protected $name;

    /**
     * @var string
     */
    protected $product_uri;

    /**
     * @var Product
     */
    protected $product;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'product_uri',
        'product'
    ];
}
