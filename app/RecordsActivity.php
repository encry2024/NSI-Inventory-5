<?php namespace App;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;
use ReflectionClass;

trait RecordsActivity
{
    protected static function bootRecordsActivity()
    {
        foreach (static::getModelEvents() as $event) {
            static::$event(function ($model) use ($event) {
                $model->recordActivity($event);
            });
        }
    }

    public function recordActivity($event)
    {
        $model = strtolower(class_basename($this));

        if ($event == "created") {
            $activity = new Activity();
            $activity->subject_id = $this->id;
            $activity->subject_type = get_class($this);
            $activity->name = $this->getActivityName($this, $event);
            $activity->user_id = Auth::guest()?0:Auth::user()->id;

            if ($model == "category") {
                $activity->old_value = $this->name;
            } elseif ($model == "information") {
                $activity->old_value = $this->value;
            } elseif ($model == "field") {
                $activity->old_value = $this->category_label;
            } elseif ($model == "devicelog") {
                $activity->old_value = $this->action;
            } elseif ($model == "owner") {
                $activity->old_value = $this->fullName();
            } else {
                $activity->old_value = $this->name;
            }

            $activity->save();
        } elseif ($event == "updates") {
            if ($model == "category") {
                $activity = new Activity();

                $activity->subject_id = $this->id;
                $activity->subject_type = get_class($this);
                $activity->name = $this->getActivityName($this, $event);
                $activity->old_value = $this->name;
                $activity->new_value = Input::get('name');
                $activity->user_id = Auth::guest()?0:Auth::user()->id;
                $activity->save();

                $this->name = Input::get('name');
                $this->save();
            } elseif ($model == "device") {
                $activity = new Activity();

                $activity->subject_id = $this->id;
                $activity->subject_type = get_class($this);
                $activity->name = $this->getActivityName($this, $event);
                $activity->old_value = $this->name;
                $activity->new_value = Input::get('phone_number');
                $activity->user_id = Auth::guest()?0:Auth::user()->id;
                $activity->save();

                $this->phone_number = Input::get('name');
                $this->save();
            } elseif ($model == "information") {
                foreach (Input::all() as $key => $value) {
                    if (strpos($key, 'info') !== false) {
                        $key = explode('-', $key);
                        $info_id = $key[1];

                        $activity = new Activity();
                        $information = Information::find($info_id);

                        $activity->subject_id = $information->id;
                        $activity->subject_type = get_class($information);
                        $activity->name = $information->getActivityName($information, $event);
                        $activity->old_value = $information->value;
                        $activity->user_id = Auth::guest() ? 0 : Auth::user()->id;
                        $activity->save();

                        $information->value = $value;
                        $information->save();

                        $act = Activity::find($activity->id);
                        $act->new_value = $information->value;
                        $act->save();
                    }
                }
            }
        } elseif ($event == "deleted") {
            $activity = new Activity();
            $activity->subject_id = $this->id;
            $activity->subject_type = get_class($this);
            $activity->name = $this->getActivityName($this, $event);
            $activity->user_id = Auth::guest()?0:Auth::user()->id;

            if ($model == "field") {
                $activity->old_value = $this->category_label;
            } elseif ($model == "information") {
                $activity->old_value = $this->value;
            } else {
                $activity->old_value = $this->name;
            }

            $activity->save();
        }
    }

    protected function getActivityName($model, $action)
    {
        $name = strtolower(class_basename($model));

        return "{$action}_{$name}";
    }

    protected static function getModelEvents()
    {
        if (isset(static::$recordEvents)) {
            return static::$recordEvents;
        }

        return ['created', 'updated', 'deleted'];
    }
}
