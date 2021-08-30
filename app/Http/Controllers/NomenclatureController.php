<?php

namespace App\Http\Controllers;

use App\Domains\Nomenclature\Models\Nomenclature;
use App\Domains\Nomenclature\Models\NomenclatureCategory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class NomenclatureController extends Controller
{
    public function index(){
        return view('pages.nomenclatures.index');
    }

    public function create(){
        return view('pages.nomenclatures.edit', ['nomenclature' => new Nomenclature()]);
    }

    public function edit(Nomenclature $nomenclature){
        return view('pages.nomenclatures.edit', compact('nomenclature'));
    }

    public function destroy(Nomenclature $nomenclature){
        $nomenclature->delete();
        return redirect(route('nomenclatures.index'));
    }

    public function createCategory(){
        return view('pages.nomenclatures.categories.edit', ['category' => new NomenclatureCategory()]);
    }

    public function editCategory(NomenclatureCategory $category){
        return view('pages.nomenclatures.categories.edit', compact('category'));
    }

    public function destroyCategory(NomenclatureCategory $category){
        $category->delete();
        return redirect(route('nomenclatures.index'));
    }

}
