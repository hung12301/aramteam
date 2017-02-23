<?php
	
	require_once ROOT . '/app/controller/EmailController.php';

	class FacebookController extends Controller
	{	
		public function getFacebookRegisterData () {
			$str = curl('m.facebook.com/reg/?cid=103');
			$data = [];
			preg_match('/name=\"lsd\"\svalue=\"(.*?)\"\sautocomplete=\"off\"\s\/>/', $str, $match);
			$data['lsd'] = $match[1];
			preg_match('/name=\"ccp\"\svalue=\"(.*?)\"\s\/>/', $str, $match);
			$data['ccp'] = $match[1];
			preg_match('/name=\"reg_instance\"\svalue=\"(.*?)\"\s\/>/', $str, $match);
			$data['reg_instance'] = $match[1];
			preg_match('/name=\"submission_request\"\svalue=\"(.*?)\"\s\/>/', $str, $match);
			$data['submission_request'] = $match[1];
			return $data;
		}

		public function registerFacebookAccount () {
			$email = "qmd83245@dsiay.com";
			$data = $this->getFacebookRegisterData();
			$data['i'] = "";
			$data['helper'] = "";
			$data['field_names[]'] = 'firstname';
			$data['field_names[]'] = 'reg_email__';
			$data['field_names[]'] = 'sex';
			$data['field_names[]'] = 'birthday_wrapper';
			$data['field_names[]'] = 'reg_passwd__';
			$data['lastname'] = "Trần Văn";
			$data['firstname'] = "Minh";
			$data['reg_email__'] = $email;
			$data['sex'] = 2;
			$data['birthday_day'] = rand(1,28);
			$data['birthday_month'] = rand(1,12);
			$data['birthday_year'] = rand(1980,2001);
			$data['reg_passwd__'] = 'hungvip12';
			$data['submit'] = 'Đăng ký';
			$data = convertPostInfo($data);
			$result = curl('m.facebook.com/reg/?cid=103', $data);
			echo $result;
		}

		public static function loginWithUsernamePassword ($username, $password) {
			// $url = "https://api.facebook.com/restserver.php?credentials_type=password&password=".$password."&email=".$username."&format=json&generate_session_cookies=1&locale=vi_VN&method=auth.login&access_token=350685531728|62f8ce9f74b12f84c123cc23437a4a32";
			// $url_old = "https://api.facebook.com/method/auth.login?credentials_type=password&password=".$password."&email=".$username."&format=json&generate_session_cookies=1&locale=vi_VN&method=auth.login&access_token=350685531728|62f8ce9f74b12f84c123cc23437a4a32";
			// $json = curl($url,null, ROOT . '/app/cookies.txt');
			// $data = json_decode($json);

			// $json = 

			$url = "http://mtdigital.vn/login.php?username=".$username."&password=" . $password;
			$json = curl($url);
			$data = json_decode($json);

			if(isset($data->access_token)) {
				$result['error'] = 0;
				$result['access_token'] = $data->access_token;
			} else {
				$result['error'] = 1;
				$result['error_message'] = json_decode($data->error_data)->error_message;
			}

			return $result;
		}

		public function updateAccessToken ($id) {
			$FacebookAccounts = $this->model('FacebookAccounts');
			// Get Username Password
			$data = $FacebookAccounts->select('*')->where(['id'=>$id])->first();
			if($data['username'] != null && $data['password'] != null) {
				$res = $this->loginWithUsernamePassword($data['username'], $data['password']);
				if($res['error'] == 0) {
					$FacebookAccounts->update(['id'=>$id],['access_token'=>$res['access_token']]);
				} else {
					echo $res['error_message'];
				}
			} else {
				echo "Tài khoản này đăng nhập bằng Access Token";
			}
		}

		public function findFacebookId () {
			if($_POST) {
				$type = $_POST['type'];
				$url = $_POST['url'];
				$data = [];
				preg_match('/facebook\.com\/(.*)/im', $url, $match);

				if(!isset($match[1])) {$data['error'] = 'Không thể lấy ID từ đường link này';echo json_encode($data);exit;}

				$res = curl('https://m.facebook.com/' . $match[1], null, null, ROOT . '/app/cookies.txt');

				if($type == 'user') {
					preg_match('/\"profile_id\":(.*?),/im', $res, $match);
					if(!isset($match[1])) {$data['error'] = 'Không thể lấy ID từ đường link này';echo json_encode($data);exit;}
					$data['success'] = $match[1];
				}

				if($type == 'group') {
					preg_match('/\?group_id=(.*?)&amp;/im', $res, $match);
					if(!isset($match[1])) {$data['error'] = 'Không thể lấy ID từ đường link này';echo json_encode($data);exit;}
					$data['success'] = $match[1];
				}
				echo json_encode($data);
			}
		}

		public function getAllFeedOfGroup ($id, $access_token) {
 			$url = "https://graph.facebook.com/v2.3/".$id."/feed?limit=500&fields=from&access_token=" . $access_token;
 			$json = curl($url);
 			$data = json_decode($json);
 			$result = [];

 			if(isset($data->error)) {
 				echo '<pre>';
 				print_r($data);
 				echo '</pre>';
 				exit;
 			}

 			foreach($data->data as $from) {
 				if(!isset($result[$from->from->id])) {
 					$result[$from->from->id] = $from->from->name;
 					echo $from->from->id . " | " . $from->from->name . "<br>";
 				}
 			}
		}

		public static function getUserBasicInfo ($access_token) {
			$fields = 'me?fields=id,name';
			$json = curl(FACEBOOK_API . $fields . '&access_token=' . $access_token);
			$data = json_decode($json);
			return $data;
		}

		public static function getUserAvatar ($id,$access_token) {
			$fields = $id . '/picture?width=100&redirect=false&access_token=' . $access_token;
			$json = curl(FACEBOOK_API . $fields);
			$data = json_decode($json);
			if(isset($data->data->url)) {
				return $data->data->url;
			}
			return $data;
		}

		public function getAllGroupOfUser ($id,$access_token) {
			$fields = 'me/groups?limit=950';
			echo $json = curl(FACEBOOK_API . $fields . '&access_token=' . $access_token);
		}

		public function getAllMembersOfGroup ($id,$access_token) {
			
			$fields = $id . '/members?limit=950';
			echo $json = curl(FACEBOOK_API . $fields . '&access_token=' . $access_token);
			// $data = json_decode($json);
			// if(isset($data->error)) echo $json;
			// $result = [];
			// $result[] = $data->data;
			// while (isset($data->paging->next)) {
			// 	$json = curl($data->paging->next);
			// 	$data = json_decode($json);
			// 	$result[] = $data->data;
			// }
			// echo json_encode($result);
		}

		public static function getGroupNameById ($id,$access_token) {
			$fields = $id . '?access_token=' . $access_token;
			$json = curl(FACEBOOK_API . $fields);
			$data = json_decode($json);
			return isset($data->name) ? $data->name : "Chưa xác định" ;
		}

		public static function searchGroupByKeyword($keyword,$access_token) {
			$fields = "search?q=".$keyword."&type=group&limit=950&access_token=" . $access_token;
			$json = curl(FACEBOOK_API . $fields);
			$data = json_decode($json);
			echo json_encode($data->data);
		}

		public function getAllFriendOfUser($facebook_id, $access_token) {
			$fields = $facebook_id . '/friends?limit=950&access_token=' . $access_token;
			echo $json = curl('https://graph.facebook.com/' . $fields);
		}

		public function getAllFriendRequest($access_token) {
			$postData = [
				'access_token'=>$access_token,
				'batch'=>'[{"name": "friendrequests", "method":"GET", "relative_url":"v1.0/me/friendrequests?limit=5000"}, {"method":"GET", "relative_url":"fql?q=SELECT uid, name, mutual_friend_count, sex FROM user WHERE uid IN ({result=friendrequests:$.data[*].from.id})"}]',
				'include_headers'=>false
			];
			$json = curl('https://graph.facebook.com/', $postData);
			$res = json_decode($json);
			if(isset($res->error)) {
				echo $json;
				return;
			}
			echo $res[1]->body;
		}

		public function acceptFriendRequest($facebook_id, $access_token) {
			$postData = [
				'access_token'=>$access_token,
				'method'=>'POST',
			];
			echo $json = curl('https://graph.facebook.com/me/friends/' . $facebook_id, $postData);
		}

		public function getNumberMutualFriend ($facebook_id, $access_token) {
			$json = curl(FACEBOOK_API . $facebook_id.'/?fields=context.fields(mutual_friends)&access_token=' . $access_token);
			$res = json_decode($json);
			echo isset($res->context->mutual_friends->summary->total_count) ? $res->context->mutual_friends->summary->total_count : 0;
		}

		public function postGroup ($group_id, $content, $access_token) {
			$postData = [];
			if($content['type'] == 'link') {
				$postData['access_token'] = $access_token;
				$postData['link'] = $content['link'];
				$postData['message'] = $content['description'];
			}
			$fields = $group_id . '/feed';
			$json = curl('https://graph.facebook.com/' . $fields, $postData);
			return $res = json_decode($json);
		}

		public function postFriend ($friend_id, $content, $access_token) {
			$postData = [];
			if($content['type'] == 'link') {
				$postData['access_token'] = $access_token;
				$postData['link'] = $content['link'];
				$postData['message'] = $content['description'];
			}
			$fields = $friend_id . '/feed';
			$json = curl('https://graph.facebook.com/' . $fields, $postData);
			return $res = json_decode($json);
		}

		public function joinGroup($group_id, $facebook_id, $access_token) {
			$postData = convertPostInfo(['method'=>'post','access_token'=>$access_token]);
			$fields = $group_id . '/members/' . $facebook_id;
			$json = curl('https://graph.facebook.com/' . $fields, $postData);
			return $res = json_decode($json);
		}

		public function addFriendIntoGroup ($group_id, $friend_id, $access_token) {
			$postData = convertPostInfo(['method'=>'post','access_token'=>$access_token]);
			$fields = $group_id . '/members/' . $friend_id;
			echo $json = curl('https://graph.facebook.com/' . $fields, $postData);
			return $res = json_decode($json);
		}

		public function postComment ($post_id, $message, $access_token) {
			$postData = ['method'=>'post','message'=>$message,'access_token'=>$access_token];
			$fields = $post_id . '/comments';
			$json = curl('https://graph.facebook.com/' . $fields, $postData);
			return $res = json_decode($json);
		}

		public function addFriend($user_id, $access_token) {
			$postData = ['method'=>'post','access_token'=>$access_token];
			$fields = 'me/friends/' . $user_id;
			$json = curl('https://graph.facebook.com/' . $fields, $postData);
			return $res = json_decode($json);
		}

		public function deleteFriend ($facebookID, $access_token) {
			$fields = 'me/friends/' . $facebookID . '?access_token=' . $access_token;
			$json = '{
				method: "delete"
			}';
			echo $json = curl_delete('https://graph.facebook.com/' . $fields, $json);
			return $res = json_decode($json);
		}

		public function getAccessTokenByFacebookID ($id) {

			$data = [];

			if(!isset($_SESSION['user'])) {
				$data['error'] = "Bạn không có quyền truy cập trang này !";
				echo json_encode($data);
				return;
			}

			$FacebookAccounts = $this->model('FacebookAccounts');
			$userInfo = $FacebookAccounts->select("*")->where(['facebook_id'=>$id,'user_id'=>$_SESSION['user']['id']])->first();

			if(empty($userInfo)) {
				$data['error'] = "Bạn không có quyền truy cập trang này !";
				echo json_encode($userInfo);
				return;
			}

			$data['access_token'] = $userInfo['access_token'];
			echo json_encode($data);
		}

		public function updateDoneSchedule ($scheduleID) {
			$FacebookSchedules = $this->model('FacebookSchedules');
			$FacebookSchedules->update(['id'=>$scheduleID],['done'=>1]);
		}

		public function updatePauseSchedule ($scheduleID) {
			$FacebookSchedules = $this->model('FacebookSchedules');
			$FacebookSchedules->update(['id'=>$scheduleID],['status'=>0]);
		}

		public function reNewPostGroupSchedule($scheduleID) {
			$FacebookPostGroup = $this->model('FacebookPostGroup');
			$data = $FacebookPostGroup->select('*')->where(['schedule_id'=>$scheduleID])->get();

			foreach ($data as $value) {
				$FacebookPostGroup->insert([
					'schedule_id'=>$scheduleID,
					'facebook_id'=>$value['facebook_id'],
					'group_id'=>$value['group_id'],
					'content_id'=>$value['content_id']
				]);
			}
		}

		public function autoPostGroup ($scheduleID, $repeat) {
			$FacebookPostGroup = $this->model('FacebookPostGroup');
			$FacebookAccounts = $this->model('FacebookAccounts');
			$FacebookContents = $this->model('FacebookContents');
			// Get first group
			$data = $FacebookPostGroup->select('*')->where(['schedule_id'=>$scheduleID,'status'=>0])->first();
			// Update Done when have one post group
			if($FacebookPostGroup->select('*')->where(['schedule_id'=>$scheduleID,'status'=>0])->count() <= 1) {
				if($repeat == 0)
					$this->updateDoneSchedule($scheduleID);
				else
					$this->reNewPostGroupSchedule($scheduleID);
			}
			// Get AccessToken
			$access_token = $FacebookAccounts->select('access_token')->where(['facebook_id'=>$data['facebook_id']])->first()['access_token'];
			// GetContent
			$content = $FacebookContents->select('*')->where(['id'=>$data['content_id']])->first();
			// POST
			$res = $this->postGroup($data['group_id'], $content, $access_token);
			if(!$res->error) {
				// Update if success
				$FacebookPostGroup->update([
					'id'=>$data['id']
				],[
					'status'=>1,
					'posted_id'=>$res->id
				]);
			}else{
				// Update if error
				$FacebookPostGroup->update([
					'id'=>$data['id']
				],[
					'status'=>1,
					'error_message'=>$res->error->message
				]);
				// Disable when account blocked
				$errorBlocked = "The action attempted has been deemed abusive or is otherwise disallowed";
				if($res->error->message == $errorBlocked) {
					$this->updateDoneSchedule($scheduleID);
				}
			}
		}

		public function autoJoinGroup ($scheduleID) {
			// Require
			$FacebookJoinGroup = $this->model('FacebookJoinGroup');
			$FacebookAccounts = $this->model('FacebookAccounts');
			// Get first group
			$data = $FacebookJoinGroup->select('*')->where(['schedule_id'=>$scheduleID,'status'=>0])->first();
			// Update Done when have one post group
			if($FacebookJoinGroup->select('*')->where(['schedule_id'=>$scheduleID,'status'=>0])->count() <= 1) {
				$this->updateDoneSchedule($scheduleID);
			}
			// Get AccessToken
			$access_token = $FacebookAccounts->select('access_token')->where(['facebook_id'=>$data['facebook_id']])->first()['access_token'];
			// Join
			$res = $this->joinGroup($data['group_id'],$data['facebook_id'],$access_token);
			if(!isset($res->error)) {
				// Update if success
				$FacebookJoinGroup->update([
					'id'=>$data['id']
				],[
					'status'=>1
				]);
			} else {
				// Update if error
				$FacebookJoinGroup->update([
					'id'=>$data['id'],
				],[
					'status'=>1,
					'error_message'=> $res->error->message
				]);
				// Disable when account blocked
				$errorBlocked = "The action attempted has been deemed abusive or is otherwise disallowed";
				if($res->error->message == $errorBlocked) {
					$this->updateDoneSchedule($scheduleID);
				}
			}
		}

		public function autoUpTop ($scheduleID) {
			// Require
			$FacebookComments = $this->model('FacebookComments');
			$FacebookAccounts = $this->model('FacebookAccounts');
			$FacebookContents = $this->model('FacebookContents');
			// Get first post
			$data = $FacebookComments->select('*')->where(['schedule_id'=>$scheduleID,'status'=>0])->first();
			// Update Done when have one post group
			if($FacebookComments->select('*')->where(['schedule_id'=>$scheduleID,'status'=>0])->count() <= 1) {
				$this->updateDoneSchedule($scheduleID);
			}
			// Get AccessToken
			$access_token = $FacebookAccounts->select('access_token')->where(['facebook_id'=>$data['facebook_id']])->first()['access_token'];
			// Get Content
			$content = $FacebookContents->select('*')->where(['id'=>$data['content_id']])->first();
			// Random Icon
			// ...
			// Join
			$res = $this->postComment($data['post_id'],$content['message'],$access_token);
			if(!isset($res->error)) {
				// Update if success
				$FacebookComments->update([
					'id'=>$data['id']
				],[
					'status'=>1,
					'commented_id'=>$res->id
				]);
			} else {
				// Update if error
				$FacebookComments->update([
					'id'=>$data['id']
				],[
					'status'=>1,
					'error_message'=> $res->error->message
				]);
				$errorBlocked = "The action attempted has been deemed abusive or is otherwise disallowed";
				if($res->error->message == $errorBlocked) {
					$this->updateDoneSchedule($scheduleID);
				}
			}
		}

		public function autoAddFriend ($scheduleID) {
			// Require
			$FacebookAddFriend = $this->model('FacebookAddFriend');
			$FacebookAccounts = $this->model('FacebookAccounts');
			// Get first task
			$data = $FacebookAddFriend->select('*')->where(['schedule_id'=>$scheduleID,'status'=>0])->first();
			// Update Done when have one task
			if($FacebookAddFriend->select('*')->where(['schedule_id'=>$scheduleID,'status'=>0])->count() <= 1) {
				$this->updateDoneSchedule($scheduleID);
			}
			// Get AccessToken
			$access_token = $FacebookAccounts->select('access_token')->where(['facebook_id'=>$data['facebook_id']])->first()['access_token'];
			// Join
			$res = $this->addFriend($data['user_id'],$access_token);
			if(!isset($res->error)) {
				// Update if success
				$FacebookAddFriend->update([
					'id'=>$data['id']
				],[
					'status'=>1
				]);
			} else {
				// Update if error
				$FacebookAddFriend->update([
					'id'=>$data['id']
				],[
					'status'=>1,
					'error_message'=> $res->error->message
				]);
				$errorBlocked = "The action attempted has been deemed abusive or is otherwise disallowed";
				if($res->error->message == $errorBlocked) {
					$this->updateDoneSchedule($scheduleID);
				}
			}
		}

		public function autoPostFriend ($scheduleID) {
			$FacebookPostFriend = $this->model('FacebookPostFriend');
			$FacebookAccounts = $this->model('FacebookAccounts');
			$FacebookContents = $this->model('FacebookContents');
			// Get first group
			$data = $FacebookPostFriend->select('*')->where(['schedule_id'=>$scheduleID,'status'=>0])->first();
			// Update Done when have one post group
			if($FacebookPostFriend->select('*')->where(['schedule_id'=>$scheduleID,'status'=>0])->count() <= 1) {
				$this->updateDoneSchedule($scheduleID);
			}
			// Get AccessToken
			$access_token = $FacebookAccounts->select('access_token')->where(['facebook_id'=>$data['facebook_id']])->first()['access_token'];
			// GetContent
			$content = $FacebookContents->select('*')->where(['id'=>$data['content_id']])->first();
			// POST
			$res = $this->postGroup($data['friend_id'], $content, $access_token);
			if(!isset($res->error)) {
				// Update if success
				$FacebookPostFriend->update([
					'id'=>$data['id']
				],[
					'status'=>1,
					'posted_id'=>$res->id
				]);
			}else{
				// Update if error
				$FacebookPostFriend->update([
					'id'=>$data['id']
				],[
					'status'=>1,
					'error_message'=>$res->error->message
				]);
				$errorBlocked = "The action attempted has been deemed abusive or is otherwise disallowed";
				if($res->error->message == $errorBlocked) {
					$this->updateDoneSchedule($scheduleID);
				}
			}
		}

		public function isLimitRun ($scheduleID, $type, $updated_at, $distance, $pause, $pauseTime) {
			if($pause == 0) return false;
			// Require
			$FacebookSchedules = $this->model('FacebookSchedules');
			$FacebookPostGroup = $this->model('FacebookPostGroup');
        	$FacebookJoinGroup = $this->model('FacebookJoinGroup');
        	$FacebookAddFriend = $this->model('FacebookAddFriend');
        	$FacebookPostFriend = $this->model('FacebookPostFriend');
    		$FacebookComments = $this->model('FacebookComments');
    		// Get number posted
    		if($type == 'post-group') $runned = $FacebookPostGroup->select('id')->where(['schedule_id'=>$scheduleID,'status'=>1])->count();
    		if($type == 'join-group') $runned = $FacebookJoinGroup->select('id')->where(['schedule_id'=>$scheduleID,'status'=>1])->count();
    		if($type == 'up-top') $runned = $FacebookComments->select('id')->where(['schedule_id'=>$scheduleID,'status'=>1])->count();
    		if($type == 'add-friend') $runned = $FacebookAddFriend->select('id')->where(['schedule_id'=>$scheduleID,'status'=>1])->count();
    		if($type == 'post-friend') $runned = $FacebookPostFriend->select('id')->where(['schedule_id'=>$scheduleID,'status'=>1])->count();
    		// Update
    		if($runned != 0 && $runned % $pause == 0) {
    			if(strtotime($updated_at) + $pauseTime + $distance <= time()) return false;
    			return true;
    		}
    		return false;
		}

		public function run () {

			$FacebookSchedules = $this->model('FacebookSchedules');
			// Get all schedule
			$allSchedules = $FacebookSchedules->select('*')->where(['done'=>0,'status'=>1])->get();
			foreach ($allSchedules as $schedule) {
				if(strtotime($schedule['updated_at']) + $schedule['distance'] <= time()) {
					if(!$this->isLimitRun($schedule['id'],$schedule['type'],$schedule['updated_at'],$schedule['distance'],$schedule['pause'],$schedule['pause_time'])) {
						// Type = post-group
						if($schedule['type'] == 'post-group') {
							$this->autoPostGroup($schedule['id'], $schedule['auto_repeat']);
						}
						// Type = join-group
						if($schedule['type'] == 'join-group') {
							$this->autoJoinGroup($schedule['id']);
						}
						// Type = up-top
						if($schedule['type'] == 'up-top') {
							$this->autoUpTop($schedule['id']);
						}
						// Type = add-friend
						if($schedule['type'] == 'add-friend') {
							$this->autoAddFriend($schedule['id']);
						}
						// Type = post-friend
						if($schedule['type'] == 'post-friend') {
							$this->autoPostFriend($schedule['id']);
						}
						$FacebookSchedules->update(['id'=>$schedule['id']],[]);
					}
				}
			}
		}

		public function test () {
			// Test function
		}
	}
?>