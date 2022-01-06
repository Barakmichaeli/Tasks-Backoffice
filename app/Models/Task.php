<?php

namespace App\Models;

use App\Exceptions\Base;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{

    const DELETE_MIN_DUE_DATE_DAYS = 6;

    public $timestamps = false;

    use HasFactory;

    /**
     * @return \stdClass
     */
    public function getViewObject(): \stdClass {
        $view_object = new \stdClass();
        $view_object->id = $this->id;
        $view_object->subject = $this->getViewSubject();
        $view_object->description = $this->description;
        $view_object->status = $this->getViewStatus();
        $view_object->ddate = $this->ddate ? date("Y-m-d", $this->ddate) : null;
        $view_object->created = date("Y-m-d H:i", $this->adate);
        $view_object->last_modified = $this->edate ? date("Y-m-d H:i", $this->edate) : null;
        return $view_object;
    }

    /**
     * @return \stdClass
     */
    private function getViewSubject(): \stdClass {
        $subject = new \stdClass();
        $subject->id = $this->subject_id;
        $subject->title = "Subject";
        return $subject;
    }

    /**
     * @return \stdClass
     */
    private function getViewStatus(): \stdClass {
        $status = new \stdClass();
        $status->id = $this->status_id;
        $status->title = "Status";
        return $status;
    }

    public function delete() {
        $this->checkBeforeDelete();
        return parent::delete();
    }

    /**
     * @throws Base
     */
    public function checkBeforeDelete() {
        if ($this->ddate && $this->ddate < strtotime(sprintf("+%s days", self::DELETE_MIN_DUE_DATE_DAYS))) {
            $ex = new Base();
            $ex->setHttpErrorCode(409);
            $ex->setDescription(sprintf("Task due date is less then %s days", self::DELETE_MIN_DUE_DATE_DAYS));
            throw $ex;
        }
    }
}
