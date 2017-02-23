<?php
	class UserController extends Controller
	{
		public function index () {
			return Route::redirect('/');
		}

		public function dangnhap () {

			if(isset($_SESSION['user'])) {
				Session::setFlash('danger',"Bạn đã đăng nhập rồi");
				return Route::redirect('/');
			}

			$data['body-theme'] = 'login-page';
			$data['title'] = 'Đăng nhập';

			if(!empty($_POST)) {

	            if(!empty($_POST['email']) && !empty($_POST['password'])) {
	                
	                $email = $_POST['email'];
	                $password = md5(Config::get('password_prefix') . $_POST['password']);
	                $remember = isset($_POST['remember']) ? 1 : 0;
	                
	                if(!Validate::isEmail ($email)) {
	                    Session::setFlash('error', "Bạn phải nhập đúng định dạng Email");
	                    return Route::back();
	                }
	                
	                $User = $this->model('User');

	                if($User->select("*")->where(['email'=>$email])->count() == 0) {
	                    Session::setFlash('error', "Email này không tồn tại");
	                    return Route::back();
	                }
	                
	                if($User->login($email,$password)) {

	                	// Save into session
	                    $info = $User->select("*")->where(['email'=>$email])->first();
	                    $_SESSION['user'] = $info;

	                    // Remember Token
	                    if(isset($_POST['remember'])) {
		                	$rememberToken = createRememberToken();
		                	// Set cookie live 1 month
		                	setcookie('id', $info['id'], time() + (60 * 60 * 24 * 30), '/');
		                	setcookie('remember_token', $rememberToken, time() + (60 * 60 * 24 * 30), '/');	                	
		                	// Update remember token on server
		                	$User->update(['id'=>$info['id']], ['remember_token' => $rememberToken]);
		                } else {
		                	// Remove cookie
		                	$User->update(['id'=>$info['id']], ['remember_token' => 'no-remember']);
		                }

	                    Session::setFlash('success', "Đăng nhập thành công");
	                    return Route::redirect('/');
	                } else {
	                    Session::setFlash('error', "Sai mật khẩu");
	                    return Route::back();
	                }
	            } else {
	                Session::setFlash('error', "Bạn phải nhập đầy đủ thông tin");
	                return Route::back();
	            }
	        }

			return $this->view('/user/login', $data);
		}

		public function dangky () {

			if(Validate::checkUser('login')) {
				Session::setFlash('danger',"Bạn đã đăng nhập rồi");
				return Route::redirect('/');
			}

			$data['body-theme'] = 'signup-page';
			$data['title'] = 'Đăng ký';

			if(!empty($_POST)) {
				if(!empty($_POST['name']) && !empty($_POST['email']) && !empty($_POST['password']) && !empty ($_POST['password-confirm'])) {
		        	if (strlen ($_POST['password'])<6) {
		        		Session::setFlash('error',"Mật khẩu quá ngắn");
						return Route::back();
		        	}
		            
					if ($_POST['password-confirm']!= $_POST['password']) {
						Session::setFlash('error',"Mật khẩu nhập lại không khớp");
						return Route::back();
					}

		            $name = $_POST['name'];
		            $email = $_POST['email'];
		            $password = md5(Config::get('password_prefix') . $_POST['password']);
		            
		            if(!Validate::isEmail($email)) {
		                Session::setFlash('error',"Bạn phải nhập đúng định dạng Email");
		                return Route::back();
		            }
		            
		            if(!Validate::isCharacter($name)) {
		                Session::setFlash('error', "Yêu cầu nhập đúng tên dạng \"Trần Văn A\" hoặc \"Văn A\"");
		                return Route::back();
		            }
		            
		            $User = $this->model('User');
		            $count = $User->select("*")->where(['email'=>$email])->count();
		            
		            if($count > 0) {
		                Session::setFlash('error',"Email này đã tồn tại");
		            } else {
		            	
		                $User->insert([
		                	'password' => $password,
		                	'name' => $name,
		                	'email' => $email,
		                	'phone_number'=>$_POST['phone-number'],
		                	'avatar' => 'user.png'
		                ]);

		                $info = $User->select("*")->where(['email'=>$email])->first();
		                $_SESSION['user'] = $info;

		                // Create license
		                $License = $this->model("License");
		                $nextExpried = time() + 2 * 24 * 60 * 60;
						$License->insert([
							'user_id'=>$_SESSION['user']['id'],
							'type'=>'vip1',
							'expried_at'=>date("Y-m-d H:i:s", $nextExpried)
						]);

		                Session::setFlash('success',"Bạn đã đăng ký thành công");
		                return Route::redirect('/');
		            }
		        } else {
		        	$data['info'] = $_POST;
		        	Session::setFlash('error',"Bạn phải điền đầy đủ thông tin");
		        	return $this->view('/user/register', $data);
		        }
			}

			return $this->view('/user/register', $data);
		}

		public function dangxuat () {
			$User = $this->model("User");
			$User->update(['id'=>$_SESSION['user']['id']], ['remember_token'=>'log-out']);
			unset($_SESSION['user']);
	        Session::setFlash('success', "Đăng xuất thành công");
	        return Route::back();
		}

		public function giahantaikhoan ($type = null) {

			if(!Validate::checkUser('login') || $type == null) {
				return Route::redirect('/');
			}

			if($_POST) {

				if(!isset($_POST['price'])) {
					Session::setFlash('error', "Bạn chưa chọn thời hạn");
					return Route::back();
				}
				$arr = explode("|", $_POST['price']);
				// Require
				$Renew = $this->model("Renew");
				$License = $this->model("License");
				$User = $this->model("User");
				// Get current money
				if($_SESSION['user']['money'] < $arr[1]) {
					Session::setFlash('error', "Bạn không đủ tiền để gia hạn");
					return Route::back();
				}
				// ADD renew
				$Renew->insert([
					'user_id'=>$_SESSION['user']['id'],
					'type'=>$type,
					'time'=>$arr[0],
				]);
				// Update money of user
				$User->update(['id'=>$_SESSION['user']['id']], ['money'=>$_SESSION['user']['money']-$arr[1]]);
					// Update Session money
				$_SESSION['user']['money'] = $_SESSION['user']['money']-$arr[1];
				// Update Expried
				if($arr[0] != 0) {
						// Get current expried
					$currentExpried = $License->select('expried_at')->where(['user_id'=>$_SESSION['user']['id']])->first();
					if(empty($currentExpried)) {
						$nextExpried = time() + $arr[0] * 24 * 60 * 60;
						$License->insert([
							'user_id'=>$_SESSION['user']['id'],
							'type'=>'vip1',
							'expried_at'=>date("Y-m-d H:i:s", $nextExpried)
						]);
					} else {
						$currentExpried = $currentExpried['expried_at'];
						// Caclutor
						$nextExpried = strtotime($currentExpried) + $arr[0] * 24 * 60 * 60;
						// Update
						$License->update(['user_id'=>$_SESSION['user']['id']], [
							'expried_at'=>date("Y-m-d H:i:s", $nextExpried)
						]);
					}
				} else {
					$License->update(['user_id'=>$_SESSION['user']['id']], [
						'forever'=>1
					]);
				}
				Session::setFlash('success', "Gia hạn thành công");
			}

			return Route::redirect('/');
		}

		public function naptienchothanhvien () {
			if(!Validate::checkUser('login')) {
				return Route::back();
			}

			if($_SESSION['user']['admin'] == 0) {
				Session::setFlash('error', "Bạn không có quyền vào đây");
				return Route::back();
			}

			// Check email
			$email = $_POST['email'];
			$money = $_POST['money'];

			$User = $this->model('User');
			$SendMoney = $this->model('SendMoney');
			$data = $User->select('*')->where(['email'=>$email])->first();

			if(empty($data)) {
				Session::setFlash('error', "Email này không tồn tại");
				return Route::back();
			}

			$User->update(['id'=>$data['id']], [
				'money'=>$data['money'] + $money,
				'refresh'=>1
			]);

			$SendMoney->insert([
				'user_id'=>$_SESSION['user']['id'],
				'receive_id'=>$data['id'],
				'money'=>$money
			]);

			Session::setFlash('success', "Đã nạp cho <b>" . $data['name'] . "</b> số tiền <b>" . number_format($money,'0',',','.') . "</b> VNĐ");
			return Route::back();
		}
	}
?>