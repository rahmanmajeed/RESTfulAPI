<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Category;
use App\Seller;
use App\Transaction;

class Product extends Model
{
    use SoftDeletes;

    const AVAILABLE_PRODUCT="available";
    const NOT_AVAILABLE_PRODUCT="notavailable";

    protected $fillable=['name','description','quantity','status','image','seller_id'];
    protected $dates=['deleted_at'];

    /** product availability check */
    /**
     * @return bool
    */
    public function isAvailable()
    {
        return $this->status=Product::AVAILABLE_PRODUCT;
    }

    public function categories()
    {
        return $this->belongsToMany(Category::class);
    }
    
    public function seller()
    {
        return $this->belongsTo(Seller::class);
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }
}
