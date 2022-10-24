<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CommentResource extends JsonResource
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
            return [
                'id'            => $this->id,
                'parent_id'     => $this->parent_id ?? null,
                'user_id'       => $this->user_id,
                'user_name'     => $this->user->name,
                'post_id'       => $this->post_id,
                'post_title'     => $this->post->title,
                'role_id'       => $this->user->role_id,
                'role'          => $this->user->role->name,
                'content'       => $this->content,
                'is_active'     => $this->is_active,
                'is_delete'     => $this->is_delete,
                'created_at'    => $this->created_at->format('d-m-Y H:i:s'),
                'updated_at'    => $this->updated_at->format('d-m-Y H:i:s'),
                'created_by'    => $this->createdBy($this->created_by)->name ?? null,
                'updated_by'    => $this->updatedBy($this->updated_by)->name ?? null,
            ];
        }

        return [];
    }
}
