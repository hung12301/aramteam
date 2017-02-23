<?php
	
	class AjaxController extends Controller
	{
		public function pauseSchedule ($scheduleID = null) {
			$result = [];
			// Require
			$FacebookSchedules = $this->model('FacebookSchedules');
			// Check
			if(!isset($_SESSION['user']) || $scheduleID == null || !is_numeric($scheduleID)) {
				$result['error'] = 'Có lỗi xảy ra';
				echo json_encode($result);
				return;
			}
			$data = $FacebookSchedules->select(['id','status'])->where(['id'=>$scheduleID,'user_id'=>$_SESSION['user']['id']])->first();
			if(empty($data)) {
				$result['error'] = 'Bạn không có quyền vào trang này';
				echo json_encode($result);
				return;
			}
			if($data['status'] == 0) {
				$FacebookSchedules->update(['id'=>$scheduleID],['status'=>1],false);
				$result['success'] = "Đã Tiếp tục";
				$result['status'] = 0;
				echo json_encode($result);
			} else {
				$FacebookSchedules->update(['id'=>$scheduleID],['status'=>0],false);
				$result['success'] = "Đã Tạm dừng";
				$result['status'] = 1;
				echo json_encode($result);
			}
		}

		public function deleteSchedule ($scheduleID = null) {
			$result = [];
			// Require
			$FacebookSchedules = $this->model('FacebookSchedules');
			// Check
			if(!isset($_SESSION['user']) || $scheduleID == null || !is_numeric($scheduleID)) {
				$result['error'] = 'Có lỗi xảy ra';
				echo json_encode($result);
				return;
			}
			$data = $FacebookSchedules->select('id')->where(['id'=>$scheduleID,'user_id'=>$_SESSION['user']['id']])->first();
			if(empty($data)) {
				$result['error'] = 'Bạn không có quyền vào trang này';
				echo json_encode($result);
				return;
			}
			$FacebookSchedules->delete($scheduleID);
			$result['success'] = 'Xóa thành công';
			echo json_encode($result);
		}

		public function upTopByScheduleId($facebookID = null, $scheduleID = null) {
			if(!isset($_SESSION['user']) || $scheduleID == null || $facebookID == null || !is_numeric($scheduleID)) {
				$result['error'] = 'Có lỗi xảy ra';
				echo json_encode($result);
				return;
			}
			// Require
			$FacebookSchedules = $this->model('FacebookSchedules');
			$FacebookPostGroup = $this->model('FacebookPostGroup');
			$data = $FacebookSchedules->select('id')->where(['id'=>$scheduleID,'user_id'=>$_SESSION['user']['id']])->first();
			if(empty($data)) {
				$result['error'] = 'Bạn không có quyền vào trang này';
				echo json_encode($result);
				return;
			}
			// Get All Posted
			$allPosted = Route::$DB->query("SELECT posted_id FROM facebook_post_group WHERE schedule_id=$scheduleID AND status=1 AND error_message IS NULL")->fetch();
			$list = '';
			foreach ($allPosted as $posted) {
				$list .= $posted['posted_id'] . '&';
			}
			$list = rtrim($list,"&");
			return Route::redirect('/up-top-bai-viet/' . $facebookID . '/' . $list);
		}

		public function test () {
			echo getenv("OPENSHIFT_MYSQL_DB_HOST") . '<br>';
			echo getenv("OPENSHIFT_MYSQL_DB_USERNAME") . '<br>';
			echo getenv("OPENSHIFT_MYSQL_DB_PASSWORD") . '<br>';
		}
	}

?>