<?php

namespace App\Domains\Directory\Repositories;

use App\Domains\Directory\Models\Locality;
use App\Domains\Foundation\Repositories\BaseRepository;
use Illuminate\Http\JsonResponse;

class LocalityRepository extends BaseRepository
{
    protected $modelClass = Locality::class;

    public function all()
    {
        return $this->model->all();
    }

    public function findByName(string $name)
    {
        $elements = [];
        $items = $this->model->where('OFFNAME', 'like', '%' . $name . '%')
            ->orderBy('AOLEVEL')
            ->select(['id', 'OFFNAME', 'AOGUID', 'PARENTGUID', "AOLEVEL", "SHORTNAME"])
            ->with('parent')
            ->take(10)
            ->get();
        foreach ($items as $item){
            $name = $item->OFFNAME;
            if (!empty($item->parent)){
                $name .=  ', ' . $item->parent->OFFNAME . ' ' . $item->parent->SHORTNAME;
            }
            $element = [
                'id' => $item->id,
                'text' => $name
            ];
            $elements[] = $element;
        }
        return $elements;
    }

    public function getChildElement($hash): JsonResponse
    {
        $childs = Locality::where('PARENTGUID', $hash)->get();
        return response()->json([
            'items' => $childs
        ], JsonResponse::HTTP_OK);
    }


}
