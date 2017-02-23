<?php

class FacebookPostGroup extends Model
{
	public $table = 'facebook_post_group';

	public function getNearPostByScheduleId($scheduleID) {
		return $this->select('*')->where(['schedule_id'=>$scheduleID,'status'=>0])->first();
	}
}

?>