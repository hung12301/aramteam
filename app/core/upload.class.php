<?php

class Upload
{
	public static function images ($folder, $file, $isURL = false) {

		if($isURL == false) {
			if($file['type'] != 'image/jpeg' && $file['type'] != 'image/jpg' && $file['type'] != 'image/png') {
				$data['error'] = "Vui lòng chọn file ảnh có dạng trong 3 dạng sau PNG | JPGE | JPG";
				return $data;
			}
		}

		// move file to upload fodel
		$isURL == true ? $name = $file : $name = $file['name'];

		$path = ROOT . '/public/images/' . $folder . '/';
		$ext = substr(pathinfo($name, PATHINFO_EXTENSION),0,3);
		$fileName = time() . rand(0,1000000) . '.' . $ext;
		if($isURL == false)
			move_uploaded_file($file['tmp_name'], $path . $fileName);
		else
			copy($file, $path . $fileName);

		return $fileName;
	}


		public static function video ($folder = '', $file) {
			if($file['type'] != 'video/mp4' && $file['type'] != 'video/mkv' && $file['type'] != 'video/avi' && $file['type'] != 'video/flv') {
				$data['error'] = "Vui lòng chọn file video có dạng trong 4 dạng sau MP4 | MKV | AVI | FLV";
				return $data;
			}

			$size = Config::get("max_size_video");
			if ($file['size']>$size) {
				$data['error'] = "Dung lượng video không được quá 200MB";
				return $data;
			}
		

		// move file to upload fodel
		$name = $file['name'];

		$path = ROOT . '/public/videos/' . $folder . '/';
		$ext = substr(pathinfo($name, PATHINFO_EXTENSION),0,3);
		$fileName = time() . rand(0,1000000) . '.' . $ext;
		
		move_uploaded_file($file['tmp_name'], $path . $fileName);
		

		return $fileName;
	}
}

?>