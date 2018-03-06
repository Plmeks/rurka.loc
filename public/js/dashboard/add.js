angular.module("rurka").controller("addPackController", ["$scope", "$http", function($scope, $http) {
	$scope.createPack = function() {
		var data = new FormData();
		data.append("pack", $scope.pack);
		
		$http({
			url: "/stickerManager/createPack",
			method: 'POST',
			data: data,
			headers: { 'Content-Type': undefined},
			transformRequest: angular.identity
		}).then(function(response){
			$scope.created = response.data.created;
		});
	};

	$scope.uploadStickers = function(element) {
		$scope.filesQueue = [];
		var files = element.files;

		angular.forEach(files, function(file) {
			$scope.filesQueue.push(file);
		});

		$scope.createSticker();
	};

	$scope.createSticker = function() {
		var data = new FormData();
		data.append("file", $scope.filesQueue[0]);
		data.append("pack", $scope.pack);

		$http({
			method: "POST",
			url: "/stickerManager/createSticker",
			data: data,
			headers: { 'Content-Type': undefined},
			transformRequest: angular.identity
		}).then(function(response) {
			console.log(response);
			// if(response.data.created) {
				// $scope.vkUpload(response.data.file);
			// }
		});
	};

	$scope.vkUpload = function(file) {
		var data = new FormData();
		data.append("file", file);
		
		$http({
			method: "POST",
			url: "/vk/uploadSticker",
			data: data,
			headers: { 'Content-Type': undefined},
			transformRequest: angular.identity
		}).then(function(response) {
			console.log(response);
		});
	};
}]);
// $(document).ready(function() {
// 	var token = "0f1b86dcc2daa2cfd6c21e04f692868dab2aa3e89d744f88ef32663209f3d2738e041db7fa4c0cacfd173";
// 	var version = "5.73";
// 	var uploadedFiles;
// 	var captchaUrl = null;
// 	var fileQueue = [];
	
// 	var choosedPack = null;
	
// 	var setCaptchaForm = function(error) {
// 		var file = error.request_params[2].value;
// 		captchaUrl = `https://api.vk.com/method/docs.save?file=${file}&captcha_sid=${error.captcha_sid}&access_token=${token}&v=5.73`;
		
// 		$("#captcha img").attr('src', error.captcha_img);
// 		$("#captcha form")[0].reset();
// 		$("#captcha").show();
//     };
    
//     var shiftSticker = function() {
//     	fileQueue.shift();
// 		if(fileQueue.length) {
//     		loadSticker(fileQueue);
// 		} else {
//     		$(`#loading`).append("<br><span style='color: forestgreen;'>All done<br><(￣︶￣)></span><br>");
//     	}
//     }
    
//     var loadSticker = function() {
//     	var data = new FormData();
// 		data.append("file", fileQueue[0]);
// 		data.append("folder", choosedPack);
    	
//     	var name = fileQueue[0].name;
//     	var id = `${fileQueue[0].size}_${fileQueue[0].lastModified}`;
    	
//     	$("#loading").append(`
//     		<span id="${id}" style="color: gold;">${name}<span>...</span></span><br>
//     	`);
    	
//     	$.ajax({
// 			type: "POST",
// 			url: "stickers.php",
// 			cache: false,
//             contentType: false,
//             processData: false,
// 			data: data,
// 			success: function(response) {
// 				// console.log(response);
// 				if(!response)
// 					return;
// 				response = JSON.parse(response);
				
// 				var params = response.params? response.params: null;
// 				var error = (response.vkFile && response.vkFile.error)? response.vkFile: null;
// 				var message = response.message? response.message: null;
				
// 				console.log(response);
// 				// console.log(error);
				
// 				if(response.result == "success") {
// 					if(error) {
// 						setCaptchaForm(error.error);
// 					} else {
// 						if(!message) {
// 							$(`#loading #${id}`).css({'color': 'forestgreen'});
// 							$(`#loading #${id} span`).remove();
// 						} else {
// 							$(`#loading #${id}`).css({'color': '#8B225E'});
// 							$(`#loading #${id}`).append(`
// 								  <span>${message}</span>
// 							`);
// 							// $('#loading').append(`
// 							// 	  <span style="color: orangered;">&nbsp;&nbsp;${message}</span><br>
// 							// `);
// 						}
						
// 						shiftSticker();
// 					}
// 				}
// 			}
// 		});
//     };
    
//     var saveCaptchedSticker = function(response){
//     	console.log(response);
//     	var data = new FormData();
//     	data.append("ownerId", response.owner_id);
//     	data.append("id", response.id);
//     	data.append("fileName", fileQueue[0].name);
//     	data.append("folder", choosedPack);
    	
//     	$.ajax({
// 			type: "POST",
// 			url: "saveToJson.php",
// 			data: data,
// 			cache: false,
//             contentType: false,
//             processData: false,
// 			success: function(response) {
// 				if(response) {
// 				    try {
// 			        	response = JSON.parse(response);
// 				    } catch(e) {
// 				        console.error(e);
// 				    }
// 				}
				
// 				if(response.result == "success") {
// 					var name = fileQueue[0].name;
//     				var id = `${fileQueue[0].size}_${fileQueue[0].lastModified}`;
					
// 					$("#captcha img").attr('src', '');
// 					$("#captcha form")[0].reset();
// 					$("#captcha").hide();
					
// 					$(`#loading #${id}`).css({'color': 'forestgreen'});
// 					$(`#loading #${id} span`).remove();
					
// 					shiftSticker();
					
// 				}
// 			}
//     	});
//     };
    
	
// 	$("#stickers input[type=file]").change(function() {
//     	$('#stickers').trigger('submit');
//     });
	
// 	$("#stickers").submit(function(e){
//         e.preventDefault();
//         fileQueue = [];
//         $(`#loading`).text("");
        
// 		$.each($('#stickers input[type=file]')[0].files, function(i, file) {
// 			console.log(file);
// 		    fileQueue.push(file);
// 		});
		
// 		loadSticker();
//     });
    
//     $("#pack input[name=pack]").blur(function() {
//     	$('#pack').trigger('submit');
//     });
    
//     $("#pack").submit(function(e){
//         e.preventDefault();
        
// 		var data = new FormData(this);

//         $.ajax({
// 			type: "POST",
// 			url: "/stickerManager/createPack",
// 			data: data,
// 			cache: false,
//             contentType: false,
//             processData: false,
// 			success: function(response) {
//                 console.log(response);
// 				response = JSON.parse(response);
// 				choosedPack = response.folder;
				
// 				var message = response.message.charAt(0).toUpperCase() + response.message.slice(1);
// 				$("#pack .message").text(message + ", now load the stickers! ح(◉ ⌣ ◉)づ");
        		
//         		$("#stickers").show();
//         		$("#stickers input[type=file]").val("");
//         		$(`#loading`).text("");
// 			}
// 		});
//     });
    
//     $("#captcha").submit(function(e) {
// 		e.preventDefault();
		
// 		var captchaKey = $("input[name=captcha]").val();
		
// 		if(captchaUrl) {
// 			captchaUrl += `&captcha_key=${captchaKey}`;
// 			console.log(captchaUrl);
// 			$.ajax({
// 				type: "POST",
// 				url: captchaUrl,
// 				dataType: 'JSONP',
// 				success: function(response) {
// 					console.log(response);
// 					var error = response.error? response.error: null;
// 					if(error) {
// 						setCaptchaForm(error);
// 					} else {
// 						saveCaptchedSticker(response.response[0]);
// 					}
// 				}
// 			});
// 		}
// 	});
// });