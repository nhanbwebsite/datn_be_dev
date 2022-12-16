<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class ProductsHaveComemntCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {

        if(!$this->collection->isEmpty()){
            // $request not empty
            foreach($this->collection as $key => $value){


                // foreach($value->comments as $key => $value2 ){
                //     $value2->replyComments = $value2->getRepcomnentByCommentID;

                //     foreach($value2->replyComments as $key3 => $value3){
                //             $value3['id'] = $value3->id;
                //             $value3['id_comment'] = $value3->id_comment;
                //             $value3['rep_comment'] = $value3->rep_comment;
                //             $value3['is_active'] = $value3->is_active;
                //             $value3['created_by'] = $value3->created_by;
                //             $value3['updated_by'] = $value3->updated_by;
                //             $value3['updated_by'] = $value3->updated_by;
                //             $value3['created_at'] = $value3->created_at->format('Y-m-d H:i:s');
                //             $value3['created_by'] = $value3->createdBy->name ?? null;
                //     }

                    // "id": 2,
                    // "id_comment": 3,
                    // "rep_comment": "test update reply comment nè ^^",
                    // "is_active": 1,
                    // "created_by": 1,
                    // "updated_by": 1,
                    // "created_at": "2022-12-07T12:27:29.000000Z",
                    // "updated_at": "2022-12-07T12:37:16.000000Z",
                    // "deleted_at": null,
                    // "deleted_by": null
                // }


            //    foreach( $value->comments as $key2 => $value2){
            //        foreach($value2->repComment as $key3 => $value3){
            //         $value3->createdBy->name;
            //        }
            //    }

            $cmt = [];
                foreach($value->comments as $key => $item){
                    $cmt[$key]['id'] = $item->id;
                    $cmt[$key]['user_id'] = $item->user_id;
                    $cmt[$key]['product_id'] = $item->product_id;
                    $cmt[$key]['content'] = $item->content;
                    $cmt[$key]['is_active'] = $item->is_active;
                    $cmt[$key]['created_by'] = $item->createdBy->name ?? null;
                    $cmt[$key]['updated_by'] = $item->updatedBy->name ?? null;

                    $cmt[$key]['rep_comment'] = [];
                    foreach($item->repComment as $k => $rep){
                        $cmt[$key]['rep_comment'][$k]['id'] = $rep->id;
                        $cmt[$key]['rep_comment'][$k]['rep_comment'] = $rep->rep_comment;
                        $cmt[$key]['rep_comment'][$k]['is_active'] = $rep->is_active;
                        $cmt[$key]['rep_comment'][$k]['created_by'] = $rep->createdBy->name ?? null;
                        $cmt[$key]['rep_comment'][$k]['updated_by'] = $rep->updatedBy->name ?? null;
                    }
                }


                $result['data'][] = [
                    'id'            => $value->id,
                    'code'          => $value->code,
                    'name'          => $value->name,
                    'slug'          => $value->slug,
                    'meta_title'    => $value->meta_title,
                    'meta_keywords' => $value->meta_keywords,
                    'meta_description' => $value->meta_description,
                    'countComment'  => count($value->comments),
                    'is_active'     => $value->is_active,
                    'created_at'    => $value->created_at->format('Y-m-d H:i:s'),
                    'updated_at'    => $value->updated_at->format('Y-m-d H:i:s'),
                    'created_by'    => $value->createdBy->name ?? null,
                    'updated_by'    => $value->updatedBy->name ?? null,
                ];



            }
            $result['paginator'] = [
                'currentPage' => !empty($this->currentPage()) ? $this->currentPage() : null,
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
