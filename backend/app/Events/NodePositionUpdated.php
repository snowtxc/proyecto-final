<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;


class NodePositionUpdated
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */


    public $nodoId;
    public $positionId;
    public $procesoId;
    public $etapaId;

    public function __construct($nodoId, $newNodoPosition, $procesoId, $etapaId)
    {
        $this->nodoId = $nodoId;
        $this->nodoPosition = $newNodoPosition;
        $this->procesoId;
        $this->etapaId = $etapaId;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('update-nodo-position.'.$this->$etapaId.'.'. $this->$procesoId);
    }

    public function broadcastWith(){
        return [
            "id" => $this->nodoId,
            "newPosition" => $this->nodoId
        ];
    }

}
