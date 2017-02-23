<?php

	require_once ROOT . '/app/controller/FacebookController.php';

	class FacebookAccountController extends Controller {

		public function thembangusernamepassword () {

			if($_POST) {

				$data = json_decode($_POST['response']);
				$result = [];

				if(!isset($data->access_token)) {
					$result['error'] = $data->error_msg;
					echo json_encode($result);
					return ;
				}
				// Get Info
				$access_token = $data->access_token;
				$userInfo = FacebookController::getUserBasicInfo($access_token);
				$facebookID = $userInfo->id;
				$facebookName = $userInfo->name;
				$facebookAvatar = FacebookController::getUserAvatar($facebookID,$access_token);
				// Check Exist on Database
				$FacebookAccounts = $this->model('FacebookAccounts');
				$count = $FacebookAccounts->select("*")->where(['facebook_id'=>$facebookID])->count();

				if($count > 0) {
					$result['error'] = "Tài khoản này đã tồn tại trong hệ thống";
					echo json_encode($result);
					return ;
				}

				$FacebookAccounts->insert([
					'user_id'=>$_SESSION['user']['id'],
					'facebook_id'=>$facebookID,
					'access_token'=>$access_token,
					'name'=>str_replace('\'', '', $facebookName),
					'avatar'=>$facebookAvatar,
					'status'=>1,
					'type'=>'username/passsword',
					'username'=>"",
					'password'=>""
				]);

				$result['success'] = "Thêm tài khoản ".$facebookName." thành công";
				echo json_encode($result);
			}
		}

		public function thembangaccesstoken () {
			if(isset($_POST)) {
				if($_POST['access_token'] && $_POST['access_token'] != '') {
					$access_token = $_POST['access_token'];
					$userInfo = FacebookController::getUserBasicInfo($access_token);
					if(isset($userInfo->error)) {
						Session::setFlash('error', $userInfo->error->type . '|' . $userInfo->error->message);
						return Route::back();
					}
					$facebookID = $userInfo->id;
					$facebookName = $userInfo->name;
					$facebookAvatar = FacebookController::getUserAvatar($facebookID,$access_token);
					// Check Exist on Database
					$FacebookAccounts = $this->model('FacebookAccounts');
					$count = $FacebookAccounts->select("*")->where(['facebook_id'=>$facebookID])->count();
					if($count > 0) {
						Session::setFlash('error', 'Tài khoản này đã tồn tại trong hệ thống');
					} else {
						$FacebookAccounts->insert([
							'user_id'=>$_SESSION['user']['id'],
							'facebook_id'=>$facebookID,
							'access_token'=>$access_token,
							'name'=>str_replace('\'', '', $facebookName),
							'avatar'=>$facebookAvatar,
							'status'=>1,
							'type'=>'access_token'
						]);
						Session::setFlash('success', 'Thêm thành công');
					}
				} else {
					Session::setFlash('error', 'Access Token không được để trống !');
				}
			}
			return Route::redirect('/');
		}

		public function xoataikhoan ($id) {
			$result = [];
			if(!isset($_SESSION['user'])) {
				$result['error'] = "Bạn chưa đăng nhập";
				echo json_encode($result);
				return;
			}

			// Require
			$FacebookAccounts = $this->model('FacebookAccounts');
			$FacebookSchedules = $this->model('FacebookSchedules');

			// Get id of account
			$data = $FacebookAccounts->select('id,facebook_id')->where(['user_id'=>$_SESSION['user']['id'],'id'=>$id])->first();

			if(empty($data)) {
				$result['error'] = "Bạn không có quyền xóa tài khoản này";
				echo json_encode($result);
				return;
			}

			$allSchedules = $FacebookSchedules->select('id')->where(['facebook_id'=>$data['facebook_id']])->get();
			foreach ($allSchedules as $schedule) {
				$FacebookSchedules->delete($schedule['id']);
			}
			$FacebookAccounts->delete($id);
			$result['success'] = "Xóa thành công";
			echo json_encode($result);
		}

	}
?>