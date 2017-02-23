<?php
	class FacebookFriendController extends Controller
	{
		public function index () {
			return Route::redirect('/');
		}

		public function danglentuongbanbe () {

			if(!Validate::checkUser('login') || empty($_POST)) {
				Session::setFlash('error', "Bạn chưa đăng nhập hoặc không có quyền vào trang này");
				return Route::back();
			}

			if(empty($_POST['facebook_id']) || $_POST['facebook_id'] == '') {
				Session::setFlash('error', "Bạn chưa chọn tài khoản Facebook");
				return Route::back();
			}

			if(!isset($_POST['users']) || empty($_POST['users'])) {
				Session::setFlash('error', "Không có bạn bè nào được chọn");
				return Route::back();
			}

			if($_POST['link'] == '') {
				Session::setFlash('error', "Bạn chưa nhập link");
				return Route::back();
			}

			// Require
			$FacebookPostFriend = $this->model('FacebookPostFriend');
			$FacebookSchedules = $this->model('FacebookSchedules');
			$FacebookContents = $this->model('FacebookContents');

			// Check if exist post friend in facebook account
			// if($FacebookSchedules->select('id')->where(['facebook_id'=>$_POST['facebook_id'],'type'=>'post-friend', 'done'=>0])->count() != 0){
			// 	Session::setFlash('error', "Tài khoản này đang thực hiện đăng nhóm rồi");
			// 	return Route::redirect('/tu-dong-dang-nhom');
			// }

			// Create new Schedule
			$FacebookSchedules->insert([
				'user_id'=> $_SESSION['user']['id'],
				'facebook_id'=>$_POST['facebook_id'],
				'type'=> 'post-friend',
				'pause'=>$_POST['pause'],
				'pause_time'=>$_POST['pause-time'] * 60 * 60,
				'status'=>1,
				'distance'=>$_POST['distance']
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
			// Insert selected users
			foreach($_POST['users'] as $user_id) {
				$FacebookPostFriend->insert([
					'schedule_id'=>$lastScheduleID,
					'facebook_id'=>$_POST['facebook_id'],
					'friend_id'=>$user_id,
					'content_id'=>$lastContentID
				]);
			}

			Session::setFlash('success', 'Lên lịch đăng thành công');
			return Route::redirect('/');
		}

		public function tudongketban () {

		  	if(!isset($_SESSION['user']) || empty($_POST)) {
				Session::setFlash('error', "Bạn chưa đăng nhập hoặc không có quyền vào trang này");
				return Route::redirect('/tu-dong-ket-ban');
			}

			if(empty($_POST['facebook_id']) || $_POST['facebook_id'] == '') {
				Session::setFlash('error', "Bạn chưa chọn tài khoản Facebook");
				return Route::redirect('/tu-dong-ket-ban');
			}

			if(!isset($_POST['users']) || empty($_POST['users'])) {
				Session::setFlash('error', "Không có nhóm nào được chọn");
				return Route::redirect('/tu-dong-ket-ban');
			}

	        if($_POST) {
	        	$FacebookSchedules = $this->model('FacebookSchedules');
	        	$FacebookAddFriend = $this->model('FacebookAddFriend');
	        	// Create new Schedule
				$FacebookSchedules->insert([
					'user_id'=> $_SESSION['user']['id'],
					'facebook_id'=>$_POST['facebook_id'],
					'type'=>'add-friend',
					'status'=>1,
					'distance'=>$_POST['distance']
				]);
				// Get last schedule and last content
				$lastScheduleID = $FacebookSchedules->select('id')->where(['user_id'=>$_SESSION['user']['id']])->orderBy('id', 'desc')->first()['id'];
				foreach($_POST['users'] as $user) {
					$user = explode("|", $user);
					$FacebookAddFriend->insert([
						'schedule_id'=>$lastScheduleID,
						'facebook_id'=>$_POST['facebook_id'],
						'user_id'=>$user[0],
						'user_name'=>$user[1]
					]);
				}
				Session::setFlash('success', 'Lên lịch thành công');
	        }

	        return Route::redirect('/');
		}

		public function xemchitietketban ($scheduleID) {
			if(!isset($_SESSION['user'])) {
				return Route::redirect('/');
			}

			$FacebookAddFriend = $this->model('FacebookAddFriend');
			$FacebookAccounts = $this->model('FacebookAccounts');
			$FacebookSchedules = $this->model('FacebookSchedules');

			$data['users'] = $FacebookAddFriend->select('*')->where(['schedule_id'=>$scheduleID])->get();
			$data['added'] = 0;
			foreach ($data['users'] as $value) {
				if($value['status'] == 1) $data['added']++;
			}
			$data['schedule'] = $FacebookSchedules->select('*')->where(['id'=>$scheduleID])->first();
			$data['facebookAccount'] = $FacebookAccounts->select('*')->where(['facebook_id'=>$data['schedule']['facebook_id']])->first();
			return $this->view('/facebook-friend/view-more-add-friend',$data);
		}

		public function suaketban ($scheduleID = null) {
			if(!isset($_SESSION['user']) || $scheduleID == null) return Route::redirect('/');
			if($_POST) {
				$FacebookSchedules = $this->model('FacebookSchedules');
				if($FacebookSchedules->select('id')->where(['user_id'=>$_SESSION['user']['id'], 'id'=>$scheduleID])->count() == 0) {
					Session::setFlash('error','Bạn không có quyền sửa tác vụ này');
					return Route::back();
				}
				$FacebookSchedules->update(['id'=>$scheduleID],[
					'distance'=>$_POST['distance'],
				]);
				Session::setFlash('success','Sửa thành công');
			}
			return Route::back();
		}

		public function xemchitietdanglentuongbanbe ($scheduleID) {
			if(!isset($_SESSION['user'])) {
				return Route::redirect('/');
			}

			$FacebookPostFriend = $this->model('FacebookPostFriend');
			$FacebookAccounts = $this->model('FacebookAccounts');
			$FacebookSchedules = $this->model('FacebookSchedules');

			$data['users'] = $FacebookPostFriend->select('*')->where(['schedule_id'=>$scheduleID])->get();
			$data['added'] = 0;
			foreach ($data['users'] as $value) {
				if($value['status'] == 1) $data['added']++;
			}
			$data['schedule'] = $FacebookSchedules->select('*')->where(['id'=>$scheduleID])->first();
			$data['facebookAccount'] = $FacebookAccounts->select('*')->where(['facebook_id'=>$data['schedule']['facebook_id']])->first();
			return $this->view('/facebook-friend/view-more-post-friend',$data);
		}
	}
?>