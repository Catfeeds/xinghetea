$(function(){
	$('.J_pagemore').click(function(){
		$(this).parent().next('.J_eject_tab').toggle();
	});
	
	/* 移动端下通用异步请求 */
	$('.J_AjaxRequest').click(function(){
		var method = 'GET';
		var url = $(this).attr('action');
		var affirm = $(this).attr('confirm');
		var ret_url = $(this).attr('ret_url');
		
		if(affirm){
			layer.open({
    			content: affirm,
    			btn: [lang.confirm, lang.cancel],
    			shadeClose: false,
    			yes: function(){
					ajaxRequest(method, url, null, ret_url, $(this));
    			}, 
				no: function(){
					// TODO
    			}
			});
		} else {
			ajaxRequest(method, url, null, ret_url, $(this));
		}
	});
  
	/* 移动端下通用表单请求*/
	$('.J_AjaxFormSubmit').click(function(){
		 // 防止重复提交
		$(this).prop('disabled', true);
		var method = $(this).parents('.J_AjaxForm').attr('method').toUpperCase();
		var url =  window.location.href; 
		var ret_url = $(this).parents('.J_AjaxForm').find('.J_AjaxFormSuccessRet').val();
		var params = {};
		
		if($(this).parents('.J_AjaxForm').attr('action')) {
			url = REAL_SITE_URL + "/" + $(this).parents('.J_AjaxForm').attr('action');
		}
		//alert(url);	
		$(this).parents('.J_AjaxForm').find('.J_AjaxFormFields').each(function(){
			params[$(this).attr('name')] = $.trim($(this).val());
			
			if($(this).get(0).tagName == 'INPUT') {
				if($(this).attr('type').toLowerCase() == 'checkbox' && $(this).prop('checked') != true) {
					params[$(this).attr('name')] = '';
				}
				if($(this).attr('type').toLowerCase() == 'radio') {
					params[$(this).attr('name')] = $(this).parents('.J_AjaxForm').find('input[name='+$(this).attr('name')+']:checked').val();
				}
			}
			else if($(this).get(0).tagName == 'TEXTAREA') {
				// TODO
			}
			else if($(this).get(0).tagName == 'SELECT') {
				// TODO
			}
			
			//alert($(this).attr('name') + ":" + params[$(this).attr('name')]);
		});
		
		ajaxRequest(method, url, params, ret_url, $(this));
		
		// Ajax请求不需要提交表单
		return false;
	});
});

/* 移动端下通用请求 */
function ajaxRequest(method, url, params, ret_url, oClick)
{
	$.ajax({
		type: method,
		url: url,
		data: params,
		cache:false, 
        dataType: "json",
		success: function(data){
			if(data.done) {
				layer.open({content: data.msg, className:'layer-popup', time: 3, end: function(data) {
					if(ret_url) {
						go(ret_url);
					} else if(method.toUpperCase() == 'GET'){
						window.location.reload();
					}
					else{
						window.history.go(-1);
					}
				}});
			}
			else
			{
				oClick.prop('disabled', false);
				layer.open({content: data.msg, className:'layer-popup', time: 3});
			}
				 
		},
		error: function(data) {
			oClick.prop('disabled', false);
			layer.open({content: lang.system_busy,time: 5});
		}
	});
}

jQuery.extend({
  getCookie : function(sName) {
    var aCookie = document.cookie.split("; ");
    for (var i=0; i < aCookie.length; i++){
      var aCrumb = aCookie[i].split("=");
      if (sName == aCrumb[0]) return decodeURIComponent(aCrumb[1]);
    }
    return '';
  },
  setCookie : function(sName, sValue, sExpires) {
    var sCookie = sName + "=" + encodeURIComponent(sValue);
    if (sExpires != null) sCookie += "; expires=" + sExpires;
    document.cookie = sCookie;
  },
  removeCookie : function(sName) {
    document.cookie = sName + "=; expires=Fri, 31 Dec 1999 23:59:59 GMT;";
  }
});

function drop_confirm(msg, url){
    if(confirm(msg)){
        window.location = url;
    }
}

/* 显示Ajax表单 */
function ajax_form(id, title, url, width, style, opacity)
{
    if (!width)
    {
        width = 400;
    }
    var d = DialogManager.create(id);
    d.setTitle(title);
    d.setContents('ajax', url);
    d.setWidth(width);
	if(style)
	{
		d.setStyle(style);
	}
	if(opacity)
	{
		ScreenLocker.style.opacity = opacity;
	}
    d.show('center');

    return d;
}
function go(url){
	/*var spm = Math.random() * 5;
	if(url.indexOf("?") != -1) {
		url = url + "&spm=" + spm;
	}
	else url = url + "?spm=" + spm;
    window.location = url;
	*/
	url = decodeURIComponent(url);
	if(url.toLowerCase().indexOf('http') == -1) {
		if(url.substr(0,1) != '/') {
			url = '/' + url;
		}
		url = SITE_URL + url;
	}
    window.location = url;
}

function change_captcha(jqObj){
    jqObj.attr('src', REAL_SITE_URL +'/index.php?app=captcha&' + Math.round(Math.random()*10000));
}

/* 格式化金额 */
function price_format(price){
    if(typeof(PRICE_FORMAT) == 'undefined'){
        PRICE_FORMAT = '&yen;%s';
    }
    price = number_format(price, 2);

    return PRICE_FORMAT.replace('%s', price);
}

function number_format(num, ext){
    if(ext < 0){
        return num;
    }
    num = Number(num);
    if(isNaN(num)){
        num = 0;
    }
    var _str = num.toString();
    var _arr = _str.split('.');
    var _int = _arr[0];
    var _flt = _arr[1];
    if(_str.indexOf('.') == -1){
        /* 找不到小数点，则添加 */
        if(ext == 0){
            return _str;
        }
        var _tmp = '';
        for(var i = 0; i < ext; i++){
            _tmp += '0';
        }
        _str = _str + '.' + _tmp;
    }else{
        if(_flt.length == ext){
            return _str;
        }
        /* 找得到小数点，则截取 */
        if(_flt.length > ext){
            _str = _str.substr(0, _str.length - (_flt.length - ext));
            if(ext == 0){
                _str = _int;
            }
        }else{
            for(var i = 0; i < ext - _flt.length; i++){
                _str += '0';
            }
        }
    }

    return _str;
}

/* 火狐下取本地全路径 */
function getFullPath(obj)
{
    if(obj)
    {
        //ie
        if (window.navigator.userAgent.indexOf("MSIE")>=1)
        {
            obj.select();
            if(window.navigator.userAgent.indexOf("MSIE") == 25){
            	obj.blur();
            }
            return document.selection.createRange().text;
        }
        //firefox
        else if(window.navigator.userAgent.indexOf("Firefox")>=1)
        {
            if(obj.files)
            {
                //return obj.files.item(0).getAsDataURL();
            	return window.URL.createObjectURL(obj.files.item(0)); 
            }
            return obj.value;
        }
		
        return obj.value;
    }
}


/**
 *    启动邮件队列
 *
 *    @author    Garbin
 *    @param     string req_url
 *    @return    void
 */
function sendmail(req_url)
{
    $(function(){
        var _script = document.createElement('script');
        _script.type = 'text/javascript';
        _script.src  = req_url;
        document.getElementsByTagName('head')[0].appendChild(_script);
    });
}
/* 转化JS跳转中的 ＆ */
function transform_char(str)
{
    if(str.indexOf('&'))
    {
        str = str.replace(/&/g, "%26");
    }
    return str;
}

function is_mobile(str)
{
	if (str.match(/^(((13[0-9]{1})|15[0-9]|18[0-9]|147|145|177)+\d{8})$/)) {
		return true;
	}
	return false; 
}

function is_email(str)
{
	if (str.match(/^\w+((-\w+)|(\.\w+))*\@[A-Za-z0-9]+((\.|-)[A-Za-z0-9]+)*\.[A-Za-z0-9]+$/)) { 
		return true;
	}
	return false;
}

/* 通用倒计时 */
function countdown(theDaysBox, theHoursBox, theMinsBox, theSecsBox)
{
	// 避免重复reload
	if(theDaysBox.text() <=0 && theHoursBox.text() <= 0 && theMinsBox.text() <= 0 && theSecsBox.text() <= 0) {
		return;
	}
	
	var refreshId = setInterval(function() {
		var currentSeconds = theSecsBox.text();
		var currentMins    = theMinsBox.text();
		var currentHours   = theHoursBox.text();
		var currentDays    = theDaysBox.text();
	  
		// hide day
		if(currentDays == 0) {
			theDaysBox.next('em').hide();
			theDaysBox.hide();
		}
	  
		if(currentSeconds == 0 && currentMins == 0 && currentHours == 0 && currentDays == 0) {
			// if everything rusn out our timer is done!!
			// do some exciting code in here when your countdown timer finishes
			clearInterval(refreshId);
			window.location.reload();
	  	
		} else if(currentSeconds == 0 && currentMins == 0 && currentHours == 0) {
			// if the seconds and minutes and hours run out we subtract 1 day
			theDaysBox.html(currentDays-1);
			theHoursBox.html("23");
			theMinsBox.html("59");
			theSecsBox.html("59");
		} else if(currentSeconds == 0 && currentMins == 0) {
			// if the seconds and minutes run out we need to subtract 1 hour
			theHoursBox.html(currentHours-1);
			theMinsBox.html("59");
			theSecsBox.html("59");
		} else if(currentSeconds == 0) {
			// if the seconds run out we need to subtract 1 minute
			theMinsBox.html(currentMins-1);
			theSecsBox.html("59");
		} else {
			theSecsBox.html(currentSeconds-1);
		}
	}, 1000);
}

/* 通用验证码发送控制（手机/EMail）*/
function time(o, wait) {
	if (wait == 0) {
		o.attr("disabled", false);			
		o.val(lang.get_captcha);
		wait = 120;
	} else {
		o.attr("disabled", true);
		o.val(lang.get_captcha_again+"(" + wait + lang.miao_hou+")");
		wait--;
		setTimeout(function() {
			time(o, wait);
		},
		1000)
	}
}
function send_phonecode(o, params, interval){
	$.ajax({
		type:"POST",
        url: REAL_SITE_URL + "/index.php?app=default&act=sendcode",
        data:params,
		dataType:"json",
		success:function(data){
			if(data.done){
				time(o, interval);
            	layer.open({content: data.msg, className:'layer-popup', time: 3});
            }
            else{
				o.attr('disabled', false);
				layer.open({content: data.msg, className:'layer-popup', time: 3});
            }
        },
        error: function(){layer.open({content: lang.captcha_send_failure, className:'layer-popup', time: 3})}
    });
}
function send_emailcode(o, params, interval){
    $.ajax({
        type:"POST",
        url: REAL_SITE_URL + "/index.php?app=default&act=sendemail",
        data:params,
        dataType:"json",
        success:function(data){
           	 if(data.done){
				time(o, interval);
            	layer.open({content: data.msg, className:'layer-popup', time: 3});
            }
            else{
				o.attr('disabled', false);
				layer.open({content: data.msg, className:'layer-popup', time: 3});
            }
        },
        error: function(){layer.open({content: lang.captcha_send_failure, className:'layer-popup', time: 3})}
    });
}
