<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class LoadFooterContentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        if(!empty($request)){
            $contentFooter = [];
            if(!empty($this->footerContent)){
                foreach($this->footerContent as $key => $footerContent){
                    $contentFooter[$key]['id'] = $footerContent->id;
                    $contentFooter[$key]['title'] = $footerContent->title;
                    $contentFooter[$key]['content'] = $footerContent->content;
                    $contentFooter[$key]['slug'] = $footerContent->slug;
                    $contentFooter[$key]['is_active'] = $footerContent->is_active;
                    $contentFooter[$key]['updated_at'] = $footerContent->updated_at->format('d-m-Y H:i:s');
                    $contentFooter[$key]['updated_by'] = $footerContent->updateBy->name ?? null;
                }
            }
            return [
                'id'=>$this->id,
                'name'=>$this->name,
                'slug'=>$this->slug,
                'is_active'=>$this->is_active,
                'content' => $contentFooter ?? null,
                'content_contact' => $contentFooter ?? null,
                'created_at' => $this->created_at->format('d-m-Y H:i:s'),
                'updated_at' => $this->updated_at->format('d-m-Y H:i:s'),
                'created_by'    => $this->createdBy->name ?? null,
                'updated_by'    => $this->updatedBy->name ?? null,
            ];
        }

       return [];
    }
}
