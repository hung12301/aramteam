<?php
	class FacebookCommentController extends Controller
	{
		public function uptopbaiviet () {
	        if(!isset($_SESSION['user'])) {
	            return Route::redirect('/');
	        }
	        // Require
	        $FacebookAccounts = $this->model('FacebookAccounts');

	        if($_POST) {
	            $listPost = explode("\r\n", $_POST['list']);
	            foreach ($listPost as $key=>$value) {
	                if($value=='') unset($listPost[$key]);
	                if(filter_var($value, FILTER_VALIDATE_URL)) $listPost[$key] = getPostIdByUrl($value);
	            }
	            if($_POST['facebook_id'] == 0) {Session::setFlash('error', 'Bạn chưa chọn tài khoản Facebook'); return Route::back();} 
	            if(empty($listPost)) {Session::setFlash('error', 'Bạn chưa nhập danh sách bài viết'); return Route::back();}
	            if(empty($_POST['content'])) {Session::setFlash('error', 'Bạn chưa nhập nội dung'); return Route::back();}

	            // Require
	            $FacebookComments = $this->model('FacebookComments');
	            $FacebookSchedules = $this->model('FacebookSchedules');
	            $FacebookContents = $this->model('FacebookContents');

	            // Create new Schedule
	            $FacebookSchedules->insert([
	                'user_id'=> $_SESSION['user']['id'],
	                'facebook_id'=>$_POST['facebook_id'],
	                'type'=> 'up-top',
	                'status'=>1,
	                'distance'=>$_POST['distance']
	            ]);

	            // Create new Content ID
	            $FacebookContents->insert([
	                'user_id'=>$_SESSION['user']['id'],
	                'type'=>'text',
	                'message'=>$_POST['content']
	            ]);
	            
	            // Get last schedule and last content
	            $lastScheduleID = $FacebookSchedules->select('id')->where(['user_id'=>$_SESSION['user']['id']])->orderBy('id', 'desc')->first()['id'];
	            $lastContentID = $FacebookContents->select('id')->where(['user_id'=>$_SESSION['user']['id']])->orderBy('id', 'desc')->first()['id'];
	            // Insert selected post
	            foreach($listPost as $id) {
	                $FacebookComments->insert([
	                    'schedule_id'=>$lastScheduleID,
	                    'facebook_id'=>$_POST['facebook_id'],
	                    'post_id'=>$id,
	                    'content_id'=>$lastContentID
	                ]);
	            }

	            Session::setFlash('success', 'Lên lịch up top bài viết thành công');
	            return Route::redirect('/');
	        }
	    }

	    public function xemchitietuptop ($scheduleID) {
	    	if(!isset($_SESSION['user'])) {
				return Route::redirect('/');
			}

			$FacebookComments = $this->model('FacebookComments');
			$FacebookAccounts = $this->model('FacebookAccounts');
			$FacebookSchedules = $this->model('FacebookSchedules');
			$FacebookContents = $this->model('FacebookContents');
			$data['comments'] = $FacebookComments->select('*')->where(['schedule_id'=>$scheduleID])->get();
			$data['commented'] = 0;
			foreach ($data['comments'] as $key=>$value) {
				if($value['status'] == 1) $data['commented']++;
				if($value['status'] == 1 && $value['error_message'] == '') $data['comments'][$key]['commented_id'] = isset(explode("_", $value['commented_id'])[2]) ? explode("_", $value['commented_id'])[2] : '';
			}
			$data['schedule'] = $FacebookSchedules->select('*')->where(['id'=>$scheduleID])->first();
			$data['content'] = $FacebookContents->select('*')->where(['id'=>$data['comments'][0]['content_id']])->first();
			$data['facebookAccount'] = $FacebookAccounts->select('*')->where(['facebook_id'=>$data['schedule']['facebook_id']])->first();
			return $this->view('/facebook-comment/view-more-up-top',$data);
	    }

	    public function suauptop ($scheduleID = null) {
			if(!isset($_SESSION['user']) || $scheduleID == null) return Route::redirect('/');
			if($_POST) {
				$FacebookSchedules = $this->model('FacebookSchedules');
				$FacebookContents = $this->model('FacebookContents');
				$FacebookComments = $this->model('FacebookComments');

				if($FacebookSchedules->select('id')->where(['user_id'=>$_SESSION['user']['id'], 'id'=>$scheduleID])->count() == 0) {
					Session::setFlash('error','Bạn không có quyền sửa tác vụ này');
					return Route::back();
				}

				// Get one comment
				$contentID = $FacebookComments->select('content_id')->where(['schedule_id'=>$scheduleID])->first()['content_id'];
				// Update content
				$FacebookContents->update(['id'=>$content_id],['message'=>$_POST['content']]);
				// Update Schedule
				$FacebookSchedules->update(['id'=>$scheduleID],[
					'distance'=>$_POST['distance'],
					'pause'=>$_POST['pause'],
					'pause_time'=>$_POST['pause-time']*60*60,
				]);

				Session::setFlash('success','Sửa thành công');
			}
			return Route::back();
		}
	}
?>