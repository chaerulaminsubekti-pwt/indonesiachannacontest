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

            return;
        }

        Participant::ensureNoUrut($participant);
    }

    public function deleted(Participant $participant): void {}

    public function updated(Participant $participant): void
    {
        if ($participant->status === Participant::STATUS_REJECTED) {
            $participant->timestamps = false;
            $participant->no_urut = null;
            $participant->saveQuietly();

            return;
        }

        if (! $participant->isDirty('event_class_id') && ! $participant->isDirty('status')) {
            return;
        }

        Participant::ensureNoUrut($participant);
    }
}
