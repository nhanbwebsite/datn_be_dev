<?php

namespace App\Http\Resources;

use App\DropboxRefreshAccessToken;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Spatie\Dropbox\Client;

class FileCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        // return parent::toArray($request);\
        if(!$this->collection->isEmpty()){
            // $request not empty
            foreach($this->collection as $value){
                $path = env('FILE_URL').$value->name;
                $result['data'][] = [
                    'id'            => $value->id,
                    'name'          => $value->name,
                    'path'          => $path ?? null,
                    'extension'     => $value->extension,
                    'created_at'    => $value->created_at->format('d-m-Y H:i:s'),
                    'updated_at'    => $value->updated_at->format('d-m-Y H:i:s'),
                    'created_by'    => $value->createdBy->name ?? null,
                    'updated_by'    => $value->updatedBy->name ?? null,
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
        $result['data'] = [];
        $result['paginator'] = [];
        return $result;
    }
}
