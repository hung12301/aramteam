<?php

class FacebookJoinGroup extends Model
{
	public $table = 'facebook_join_group';

	public function getNearJoinByScheduleId ($scheduleID) {
		return $this->select('*')->where(['schedule_id'=>$scheduleID,'status'=>0])->first();
	}
}

?>