<?php

namespace App\Observers;

use App\Models\Participant;

class ParticipantObserver
{
    public function created(Participant $participant): void
    {
        if ($participant->status === Participant::STATUS_REJECTED) {
            $participant->timestamps = false;
            $participant->no_urut = null;
            $participant->saveQuietly();
        }

        Participant::renumberSequence($participant->event_id, $participant->event_class_id);
    }

    public function deleted(Participant $participant): void
    {
        Participant::renumberSequence($participant->event_id, $participant->event_class_id);
    }

    public function updated(Participant $participant): void
    {
        if (! $participant->isDirty('event_class_id') && ! $participant->isDirty('status')) {
            return;
        }

        if ($participant->status === Participant::STATUS_REJECTED) {
            $participant->timestamps = false;
            $participant->no_urut = null;
            $participant->saveQuietly();
        }

        $oldClassId = $participant->getOriginal('event_class_id');

        if ((int) $oldClassId !== (int) $participant->event_class_id) {
            Participant::renumberSequence($participant->event_id, $oldClassId ? (int) $oldClassId : null);
        }

        Participant::renumberSequence($participant->event_id, $participant->event_class_id);
    }
}
