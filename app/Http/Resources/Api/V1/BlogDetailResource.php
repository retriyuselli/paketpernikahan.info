<?php

namespace App\Http\Resources\Api\V1;

use Illuminate\Http\Request;

class BlogDetailResource extends BlogResource
{
    public function toArray(Request $request): array
    {
        return array_merge(parent::toArray($request), [
            'content' => $this->content,
        ]);
    }
}
