<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ContactResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        if ($this -> photo ===null){
            $this->photo =asset('user-default.jpg');
        }else{
            $this->photo=asset('storage/photo/'.$this->photo);
        }
        return [
            'name'=>$this->name,
            'phone'=>$this->phone,
            'photo'=>$this->photo,
            'apyaw'=>"hello min ga lar par"
        ];
    }
}
