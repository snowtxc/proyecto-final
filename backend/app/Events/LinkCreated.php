<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class LinkCreated
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */

    public $nodoFromId;
    public $nodoToId;
    public $procesoId;
    public $etapaId;

    public function __construct($nodoFromId, $nodoToId, $procesoId, $etapaId)
    {
        $this->nodoFromId = $nodoFromId;
        $this->nodoToId=  $nodoToId;
        $this->procesoId = $procesoId;
        $this->etapaId = $etapaId;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('create-link.'.$this->$etapaId.'.'. $this->$procesoId);
    }

    public function broadcastWith(){
        return [
            "nodo_from_id" => $this->nodoFromId,
            "nodo_to_id" => $this->nodoToId
        ];
    }

}
