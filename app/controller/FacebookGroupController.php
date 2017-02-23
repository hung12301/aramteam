<?php
	
	require_once ROOT . '/app/controller/FacebookController.php';

	class FacebookGroupController extends Controller
	{
		public function lenlichdang () {

			if(!isset($_SESSION['user']) || empty($_POST)) {
				Session::setFlash('error', "Bạn chưa đăng nhập hoặc không có quyền vào trang này");
				return Route::redirect('/tu-dong-dang-nhom');
			}

			if(empty($_POST['facebook_id']) || $_POST['facebook_id'] == '') {
				Session::setFlash('error', "Bạn chưa chọn tài khoản Facebook");
				return Route::redirect('/tu-dong-dang-nhom');
			}

			if(!isset($_POST['groups']) || empty($_POST['groups'])) {
				Session::setFlash('error', "Không có nhóm nào được chọn");
				return Route::redirect('/tu-dong-dang-nhom');
			}

			if($_POST['link'] == '') {
				Session::setFlash('error', "Bạn chưa nhập link");
				return Route::redirect('/tu-dong-dang-nhom');
			}

			// Require
			$FacebookPostGroup = $this->model('FacebookPostGroup');
			$FacebookSchedules = $this->model('FacebookSchedules');
			$FacebookContents = $this->model('FacebookContents');

			// Check if exist post group in facebook account
			// if($FacebookSchedules->select('id')->where(['facebook_id'=>$_POST['facebook_id'],'type'=>'post-group', 'done'=>0])->count() != 0){
			// 	Session::setFlash('error', "Tài khoản này đang thực hiện đăng nhóm rồi");
			// 	return Route::redirect('/tu-dong-dang-nhom');
			// }

			// Create new Schedule
			$FacebookSchedules->insert([
				'user_id'=> $_SESSION['user']['id'],
				'facebook_id'=>$_POST['facebook_id'],
				'type'=> 'post-group',
				'pause'=>$_POST['pause'],
				'pause_time'=>$_POST['pause-time'] * 60 * 60,
				'status'=>1,
				'distance'=>$_POST['distance'],
				'auto_repeat'=>isset($_POST['repeat']) ? 1 : 0
			]);

			// Create new Content ID
			$FacebookContents->insert([
				'user_id'=>$_SESSION['user']['id'],
				'type'=>'link',
				'link'=>$_POST['link'],
				'description'=>$_POST['description']
			]);
			
			// Get last schedule and last content
			$lastScheduleID = $FacebookSchedules->select('id')->where(['user_id'=>$_SESSION['user']['id']])->orderBy('id', 'desc')->first()['id'];
			$lastContentID = $FacebookContents->select('id')->where(['user_id'=>$_SESSION['user']['id']])->orderBy('id', 'desc')->first()['id'];
			// Insert selected group
			foreach($_POST['groups'] as $group_id) {
				$FacebookPostGroup->insert([
					'schedule_id'=>$lastScheduleID,
					'facebook_id'=>$_POST['facebook_id'],
					'group_id'=>$group_id,
					'content_id'=>$lastContentID
				]);
			}

			Session::setFlash('success', 'Lên lịch đăng thành công');
			return Route::redirect('/');
		}

		public function thamgianhom () {

			if(!isset($_SESSION['user'])) {
				return Route::redirect('/');
			}

			if(empty($_POST['groups'])) {
				Session::setFlash('error', 'Bạn chưa chọn nhóm nào');
				return Route::redirect('/tu-dong-tham-gia-nhom');
			}

			if($_POST['facebook_id'] == 0) {
				Session::setFlash('error', 'Bạn chưa chọn tài khoản');
				return Route::redirect('/tu-dong-tham-gia-nhom');
			}

			// Require
			$FacebookJoinGroup = $this->model('FacebookJoinGroup');
			$FacebookSchedules = $this->model('FacebookSchedules');

			// Check if exist join group in facebook account
			// if($FacebookSchedules->select('id')->where(['facebook_id'=>$_POST['facebook_id'],'type'=>'join-group'])->count() != 0) {
			// 	Session::setFlash('error', "Tài khoản này đang thực hiện tham gia nhóm rồi ! Vui lòng chọn tài khoản khác");
			// 	return Route::redirect('/tu-dong-tham-gia-nhom');
			// }

			// Create new Schedule
			$FacebookSchedules->insert([
				'user_id'=> $_SESSION['user']['id'],
				'facebook_id'=>$_POST['facebook_id'],
				'type'=> 'join-group',
				'status'=>1,
				'pause'=>$_POST['pause'],
				'pause_time'=>$_POST['pause-time'] * 60 * 60,
				'distance'=>$_POST['distance']
			]);

			// Get last schedule and last content
			$lastScheduleID = $FacebookSchedules->select('id')->where(['user_id'=>$_SESSION['user']['id']])->orderBy('id', 'desc')->first()['id'];

			foreach ($_POST['groups'] as $group_id) {
				$FacebookJoinGroup->insert([
					'schedule_id'=>$lastScheduleID,
					'facebook_id'=>$_POST['facebook_id'],
					'group_id'=>$group_id
				]);
			}

			Session::setFlash('success', 'Lên lịch thành công');
			return Route::redirect('/');
		}

		public function xemchitietdangnhom ($scheduleID) {

			if(!isset($_SESSION['user'])) {
				return Route::redirect('/');
			}

			$FacebookPostGroup = $this->model('FacebookPostGroup');
			$FacebookContents = $this->model('FacebookContents');
			$FacebookAccounts = $this->model('FacebookAccounts');
			$FacebookSchedules = $this->model('FacebookSchedules');

			$data['groups'] = $FacebookPostGroup->select('*')->where(['schedule_id'=>$scheduleID])->get();
			$data['posted'] = 0;
			foreach ($data['groups'] as $value) {
				if($value['status'] == 1) $data['posted']++;
			}
			$data['schedule'] = $FacebookSchedules->select('*')->where(['id'=>$scheduleID])->first();
			$data['facebookAccount'] = $FacebookAccounts->select('*')->where(['facebook_id'=>$data['schedule']['facebook_id']])->first();
			$data['content'] = $FacebookContents->select('*')->where(['id'=>$data['groups'][0]['content_id']])->first();
			return $this->view('/facebook-group/view-more-post-group',$data);
		}

		public function xemchitietthamgianhom ($scheduleID) {
			
			if(!isset($_SESSION['user'])) {
				return Route::redirect('/');
			}

			$FacebookJoinGroup = $this->model('FacebookJoinGroup');
			$FacebookAccounts = $this->model('FacebookAccounts');
			$FacebookSchedules = $this->model('FacebookSchedules');

			$data['groups'] = $FacebookJoinGroup->select('*')->where(['schedule_id'=>$scheduleID])->get();
			$data['joined'] = 0;
			foreach ($data['groups'] as $value) {
				if($value['status'] == 1) $data['joined']++;
			}
			$data['schedule'] = $FacebookSchedules->select('*')->where(['id'=>$scheduleID])->first();
			$data['facebookAccount'] = $FacebookAccounts->select('*')->where(['facebook_id'=>$data['schedule']['facebook_id']])->first();
			return $this->view('/facebook-group/view-more-join-group',$data);
		}

		public function suathamgianhom ($scheduleID = null) {
			if(!isset($_SESSION['user']) || $scheduleID == null) return Route::redirect('/');

			if($_POST) {
				$FacebookSchedules = $this->model('FacebookSchedules');

				if($FacebookSchedules->select('id')->where(['user_id'=>$_SESSION['user']['id'], 'id'=>$scheduleID])->count() == 0) {
					Session::setFlash('error','Bạn không có quyền sửa tác vụ này');
					return Route::back();
				}

				$FacebookSchedules->update(['id'=>$scheduleID],[
					'distance'=>$_POST['distance'],
					'pause'=>$_POST['pause'],
					'pause_time'=>$_POST['pause-time']*60*60,
				]);
				Session::setFlash('success','Sửa thành công');
			}
			return Route::back();
		}

		public function suadangnhom ($scheduleID, $contentID = null) {
			if(!isset($_SESSION['user']) || $scheduleID == null || $contentID == null) return Route::redirect('/');
			if($_POST) {
				$FacebookSchedules = $this->model('FacebookSchedules');
				$FacebookContents = $this->model('FacebookContents');

				if($FacebookSchedules->select('id')->where(['user_id'=>$_SESSION['user']['id'], 'id'=>$scheduleID])->count() == 0) {
					Session::setFlash('error','Bạn không có quyền sửa tác vụ này');
					return Route::back();
				}

				$FacebookContents->update(['id'=>$contentID], [
					'link'=>$_POST['link'],
					'description'=>$_POST['description']
				]);


				$FacebookSchedules->update(['id'=>$scheduleID],[
					'distance'=>$_POST['distance'],
					'pause'=>$_POST['pause'],
					'pause_time'=>$_POST['pause-time']*60*60,
					'auto_repeat'=>isset($_POST['repeat']) ? 1 : 0
				]);
				Session::setFlash('success','Sửa thành công');
			}
			return Route::back();
		}
	}

?>