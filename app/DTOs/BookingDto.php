<?php

namespace App\DTOs;

use Carbon\Carbon;

class BookingDto
{
    public string $title;
    public string $start_time;
    public string $end_time;
    public int $participants;
    public ?string $information = null;
    public ?int $user_id;
    public int $room_id;
    public ?int $status_id = null;

    public static function fromRequest($request)
    {
        $dto = new self();
        $dto->title = $request->input('title');
        $dto->start_time = $request->input('start_time');
        $dto->end_time = $request->input('end_time');
        $dto->participants = $request->input('participants');
        $dto->information = $request->input('information');
        $dto->user_id = $request->input('user_id');
        $dto->room_id = $request->input('room_id');
        $dto->status_id = $request->input('status_id');

        return $dto;
    }

    public function toArray()
    {
        return [
            'title' => $this->title,
            'start_time' => $this->start_time,
            'end_time' => $this->end_time,
            'participants' => $this->participants,
            'information' => $this->information ?? null,
            'user_id' => $this->user_id,
            'room_id' => $this->room_id,
            'status_id' => $this->status_id ?? null,
        ];
    }
}
