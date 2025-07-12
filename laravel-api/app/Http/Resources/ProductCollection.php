<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Support\Facades\Crypt;

class ProductCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'data' => $this->collection, // the actual users
            "current_page"=> $this->currentPage(),
            "last_page"=> $this->lastPage(),
            "current_page"=> $this->currentPage(),
            "per_page" => $this->perPage(),
            "total"=> $this->total()
            
        ];
        
    }
}
