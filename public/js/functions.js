function showAlert (type,text) {
	if(type === 'error') {
		type = 'alert-danger';
		icon = '<i class="material-icons font-24">error</i>';	
	} else if (type === 'success') {
    type = 'alert-success';
		icon = '<i class="material-icons font-24">done</i>';
	}
    animateEnter = 'animated-fast fadeInUp';
    animateExit = 'animated-fast fadeOutRight';
    var allowDismiss = true;

    $.notify({
        message: text
    },
        {
            type: type,
            allow_dismiss: allowDismiss,
            newest_on_top: true,
            delay: 5000,
            placement: {
    				from: "top",
    				align: "right"
    			},
            animate: {
                enter: animateEnter,
                exit: animateExit
            },
            template: '<div data-notify="container" class="bootstrap-notify-container alert alert-dismissible {0}" role="alert">' +
            '<button type="button" aria-hidden="true" class="close" data-notify="dismiss">×</button>' +
            '<span data-notify="icon" style="float:left;margin-right: 10px">'+icon+'</span> ' +
            '<span data-notify="message" style="vertical-align: -4px;">{2}</span>' +
            '<div class="progress" data-notify="progressbar">' +
            '<div class="progress-bar progress-bar-{0}" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%;"></div>' +
            '</div>' +
            '<a href="{3}" target="{4}" data-notify="url"></a>' +
            '</div>'
        });
}

function showTable ($className) {
 $("body").find('.' + $className).DataTable({
    "language": {
      "search" : "Tìm kiếm",
      "lengthMenu": "Hiển thị _MENU_ dòng trên một trang",
      "zeroRecords": "Không có dữ liệu nào",
      "info": "Trang _PAGE_ (Có tổng _PAGES_ trang)",
      "infoEmpty": "Không có trang nào",
      "paginate" : {
        "frist": "Đầu",
        "last" : "Cuối",
        "previous" : "Lùi",
        "next": "Tiếp",
      },
    }
  });
}

function getNumberMutualFriend($facebookID,$access_token,callback) {
  $.get(SITE_URL + '/api/getNumberMutualFriend/' + $facebookID + '/' + $access_token,function (res){
    callback(res);
  });
}

function deleteFriend ($facebookID, $access_token,callback) {
  ajax({url:SITE_URL + '/api/deleteFriend/' + $facebookID + '/' + $access_token},function (res){
    callback(res);
  });
}


function searchGroupByKeyword($keyword,$access_token,callback) {
  ajax({url:SITE_URL + '/api/searchGroupByKeyword/' + $keyword + '/' + $access_token},function (res){
    callback(res);
  });
}

function acceptFriendRequest ($facebookID, $access_token, callback) {
  ajax({url:SITE_URL + '/api/acceptFriendRequest/' + $facebookID + '/' + $access_token},function (res){
    callback(res);
  });
}

function getAllFriendRequest($access_token, callback) {
  ajax({url:SITE_URL + '/api/getAllFriendRequest/' + $access_token},function (res){
    callback(res);
  });
}

function getAllMembersOfGroup ($groupID, $access_token, callback) {
  ajax({url:SITE_URL + '/api/getAllMembersOfGroup/' + $groupID + '/' + $access_token},function (res){
    callback(res);
  });
}

function getAllGroupOfUser ($userID, $access_token, callback) {
  ajax({url:SITE_URL + '/api/getAllGroupOfUser/' + $userID + '/' + $access_token},function (res){
        callback(res);
  });
}

function getAllFriendOfUser($userID, $access_token, callback) {
  ajax({url:SITE_URL + '/api/getAllFriendOfUser/' + $userID + '/' + $access_token},function (res){
      callback(res);
  });
}

function getAccessTokenByFacebookID ($accountID, callback) {
  ajax({url:SITE_URL + '/api/getAccessTokenByFacebookID/' + $accountID},function (res){
      callback(res);
  });
}

function getFacebookIdByUrl($url,$type,callback) {
  ajax({url:SITE_URL + "/api/findFacebookId/",method:"POST",data:{'type':$type,'url':$link}}, function (res) {
      callback(res);
  });
}

function ajax($option, callback) {
  $.ajax($option).done(function (res) {
      callback(JSON.parse(res));
  });
}

function Stopwatch(config) {
  // If no config is passed, create an empty set
  config = config || {};
  // Set the options (passed or default)
  this.element = config.element || {};
  this.previousTime = config.previousTime || new Date().getTime();
  this.paused = config.paused && true;
  this.elapsed = config.elapsed || 0;
  this.countingUp = config.countingUp && true;
  this.timeLimit = config.timeLimit || (this.countingUp ? 60 * 10 : 0);
  this.updateRate = config.updateRate || 100;
  this.onTimeUp = config.onTimeUp || function() {
    this.stop();
  };
  this.onTimeUpdate = config.onTimeUpdate || function() {
    console.log(this.elapsed);
  };
  if (!this.paused) {
    this.start();
  }
}


Stopwatch.prototype.start = function() {
  // Unlock the timer
  this.paused = false;
  // Update the current time
  this.previousTime = new Date().getTime();
  // Launch the counter
  this.keepCounting();
};

Stopwatch.prototype.keepCounting = function() {
  // Lock the timer if paused
  if (this.paused) {
    return true;
  }
  // Get the current time
  var now = new Date().getTime();
  // Calculate the time difference from last check and add/substract it to 'elapsed'
  var diff = 1;
  if (!this.countingUp) {
    diff = -diff;
  }
  this.elapsed = this.elapsed + diff;
  // Update the time
  this.previousTime = now;
  // Execute the callback for the update
  this.onTimeUpdate();
  // If we hit the time limit, stop and execute the callback for time up
  if ((this.elapsed >= this.timeLimit && this.countingUp) || (this.elapsed <= this.timeLimit && !this.countingUp)) {
    this.stop();
    this.onTimeUp();
    return true;
  }
  // Execute that again in 'updateRate' milliseconds
  var that = this;
  setTimeout(function() {
    that.keepCounting();
  }, this.updateRate);
};

Stopwatch.prototype.stop = function() {
  // Change the status
  this.paused = true;
};
