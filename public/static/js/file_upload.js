
/**
 * 上传文件
 * 2017年6月9日 11:53:25 王永杰
 * @param fileid 当前input file类型
 * @param data 传输的数据 file_path属性必传
 * @source admin pc sourcel
 */
function uploadFile(fileid,data,callBack, source){
	var dom = document.getElementById(fileid);
	var file =  dom.files[0];//File对象;
	var only_type = $(dom).attr('only-type');
	var validate_type = new Object();
	if(!only_type){
		validate_type.type_id = 1;
		validate_type.type_content = '';
	}else{
		validate_type.type_id = 2;
		validate_type.type_content = only_type;
	}
	if(validationFile(file, source, validate_type)){
		$.ajaxFileUpload({
			url : __URL(APPMAIN + '/upload/uploadfile'), //用于文件上传的服务器端请求地址
			secureuri : false, //一般设置为false
			fileElementId : fileid, //文件上传空间的id属性  <input type="file" id="file" name="file" />
			dataType : 'json', //返回值类型 一般设置为json
			data : data,
			async : false,
			contentType : "text/json;charset=utf-8",
			success : function(res){ //服务器成功响应处理函数
				callBack.call(this,res);
			}
		});
	}
}

/**
 * 验证文件是否可以上传
 * 2017年6月9日 19:39:19 王永杰
 * @param file JS DOM文件对象
 * @source admin pc sourcel
 * @validate_type 验证类型validate_type.type_id 1：普通图片上传 2：只允许某些类型
 */
function validationFile(file, source, validate_type) {
	if(validate_type.type_id == 1){
		var fileTypeArr = ['application/php','text/html','application/javascript','application/msword','application/x-msdownload'];
		if(null == file) return false;

		var flag = false;
		for(var i=0;i<fileTypeArr.length;i++){
			if(file.type == fileTypeArr[i]){
				flag = true;
				break;
			}
		}
	}else if(validate_type.type_id == 2){
		var flag = false;
		var type = file.name;
		var pos = type.lastIndexOf('.');
		var type_name = type.substring(pos);
		if(type_name != validate_type.type_content){
			flag = true;
		}
	}
	
	if(flag){
		if(source == 1) layer.msg("文件类型不合法");

		else if(source == "pc" ) $.msg("文件类型不合法");
		
		else showTip("文件类型不合法","warning");
		
		return false;
	}

	return true;
}

/**
 * 商品规格图片上传
 * @param fileid
 * @param callBack
 */
function specImgUpload(fileid, data, callBack){
	var dom = document.getElementById(fileid);
	var file =  dom.files[0];//File对象;
	if(validationFile(file)){
		$.ajaxFileUpload({
			url : __URL(APPMAIN + '/upload/specimgupload'), //用于文件上传的服务器端请求地址
			secureuri : false, //一般设置为false
			fileElementId : fileid, //文件上传空间的id属性  <input type="file" id="file" name="file" />
			dataType : 'json', //返回值类型 一般设置为json
			data : data,
			async : false,
			contentType : "text/json;charset=utf-8",
			success : function(res){ //服务器成功响应处理函数
				callBack.call(this,res);
			}
		});
	}
}