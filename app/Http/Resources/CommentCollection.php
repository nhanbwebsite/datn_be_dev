<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;
use App\Models\Rep_comment;
class CommentCollection extends ResourceCollection
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
        if(!$this->collection->isEmpty()){
            // $request not empty
            foreach($this->collection as $key => $value){
                $result['data'][] = [
                    'id'            => $value->id,
                    'user_id'       => $value->user_id,
                    'user_name'     => $value->user->name,
                    'product_id'       => $value->product_id,
                    'role_id'       => $value->user->role_id,
                    'role'          => $value->user->role->name,
                    'product'       => $value->product,
                    'content'       => $value->content,
                    'rep_coment' => $value->getRepcomnentByCommentID,
                    'status_text' => $value->is_active = 1 ? 'Đã duyệt' : 'Chưa duyệt',
                    'is_active'     => $value->is_active,
                    'created_at'    => $value->created_at->format('Y-m-d H:i:s'),
                    'updated_at'    => $value->updated_at->format('Y-m-d H:i:s'),
                    'created_by'    => $value->createdBy->name ?? null,
                    'updated_by'    => $value->updatedBy->name ?? null,
                ];

                // 'rep_user_name' => $value->repComment->createdBy->name,
                foreach($result['data'][$key]['rep_coment'] as $key2 => $value2) {
                    $value2->rep_user_name = Rep_comment::getUserName($value2['created_by']);
                }
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
