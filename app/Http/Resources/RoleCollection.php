<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class RoleCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        // return parent::toArray($request);
        if(!empty($request)){
            // $request not empty
            foreach($this->collection as $value){
                $result['data'][] = [
                    'name' => $value->name,
                    'code' => $value->code,
                    'level' => $value->level,
                    'is_active' => $value->is_active,
                    'deleted' => $value->deleted,
                    'created_at' => $value->created_at->format('Y-m-d H:i:s'),
                    'updated_at' => $value->updated_at->format('Y-m-d H:i:s'),
                    'created_by' => $value->created_by,
                    'updated_by' => $value->updated_by,
                ];
            }
            $result['paginator'] = [
                'currentPage' => $this->currentPage(),
                'totalPages' => $this->lastPage(),
                'perPage' => $this->perPage(),
                'count' => $this->count(),
                'total' => $this->total(),
                'nextPageUrl' => $this->nextPageUrl(),
                'prevPageUrl' => $this->previousPageUrl(),
            ];
            return $result;
        }

        // $request is empty
        return [];
    }
}
