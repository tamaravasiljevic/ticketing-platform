<?php

namespace App\Observers;

use App\Models\Event;
use App\Models\EventChangeLog;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class EventObserver
{
    /**
     * Handle the Event "updated" event.
     */
    public function updated(Event $event)
    {
        $userId = Auth::id();

        $changes = $event->getChanges();

        foreach ($changes as $field => $newValue) {
            if (in_array($field, ['updated_at', 'created_at'])) {
                continue; // skip system fields
            }

            $oldValue = $event->getOriginal($field);

            // notification through the email if start time of event changes
            // if start time is changed
//            if ($field === 'start_time') {
//                $customers = $event->tickets()->with('user')->get()->pluck('user')->unique('id');
//
//                foreach ($customers as $customer) {
//                    Mail::to($customer->email)
//                        ->send(new EventTimeChangedMail($event, $oldValue, $newValue));
//                }
//            }

            EventChangeLog::create([
                'event_id'    => $event->id,
                'user_id'     => $userId,
                'field_name'  => $field,
                'old_value'   => $oldValue,
                'new_value'   => $newValue,
                'description' => sprintf('Field %s changed from %s to %s', $field, $oldValue, $newValue),
            ]);


        }
    }
}
