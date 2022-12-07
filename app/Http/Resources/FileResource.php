<?php

namespace App\Http\Resources;

use App\DropboxRefreshAccessToken;
use Illuminate\Http\Resources\Json\JsonResource;
use Spatie\Dropbox\Client;

class FileResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {

        // return parent::toArray($request);
        if(!empty($request)){
            $path = env('FILE_URL').$this->name;
            return [
                'id'            => $this->id,
                'name'          => $this->name,
                'path'          => $path ?? null,
                'extension'     => $this->extension,
                'created_at'    => $this->created_at->format('d-m-Y H:i:s'),
                'updated_at'    => $this->updated_at->format('d-m-Y H:i:s'),
                'created_by'    => $this->createdBy->name ?? null,
                'updated_by'    => $this->updatedBy->name ?? null,
            ];
        }

        return [];
    }
}
