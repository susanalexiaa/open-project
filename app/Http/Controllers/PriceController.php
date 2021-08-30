<?php

namespace App\Http\Controllers;

use App\Domains\Price\Models\Price;
use App\Domains\Price\Models\PriceType;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class PriceController extends Controller
{
    public function index(){
        $priceTypes = PriceType::query()->orderBy('sort')->get();
        return view('pages.prices.index', compact('priceTypes'));
    }

    public function create(){
        $priceType = new PriceType();
        return view('pages.prices.edit', compact('priceType'));
    }

    public function edit(PriceType $priceType){
        return view('pages.prices.edit', compact('priceType'));
    }

    public function destroy(PriceType $priceType){
        $priceType->delete();
        return redirect(route('prices.index'));
    }

    public function editPrice(Price $price){
        return view('pages.prices.editPrice', compact('price'));
    }

    public function destroyPrice(Price $price){
        $price->delete();
        return redirect(route('prices.edit', $price->priceType));
    }
}
