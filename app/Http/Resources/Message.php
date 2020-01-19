<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class Message extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'data' => [
                'message_id' => $this->id,
                'subject' => $this->subject,
                'content' => $this->content,
                'recipientEmail' => $this->recipientEmail,
                'frequency' => $this->frequency,
                'submissionsNumber' => $this->submissionsNumber,
                'startDate' => $this->startDate->format('m/d/Y'),
                'expirationDate' => $this->expirationDate->format('m/d/Y'),
                'last_updated' => $this->updated_at->diffForHumans(),
            ],
            'links' => [
                'self' => $this->path(),
            ]
        ];
    }
}
