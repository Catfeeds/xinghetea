$(function(){
   
	$("*[ectype='ul_cate'] a").click(function(){
        replaceParam('cate_id', this.id);
        return false;
    });
    $("*[ectype='ul_brand'] a").click(function(){
        replaceParam('brand', this.id);
        return false;
    });
	
    $("*[ectype='ul_price'] a").click(function(){
        replaceParam('price', this.id);
        return false;
    });
    $("*[ectype='ul_region'] a").click(function(){
        replaceParam('region_id', this.id);
        return false;
    });
	
	$("*[ectype='ul_prop'] a").click(function(){
        id = $(this).attr('selected_props')+this.id;
		replaceParam('props',id);
		return false;
    });
	
	$(".selected-attr a.each-filter").click(function(){
		dropParam(this.id);
		return false;
	});
	
});

/** 打开/关闭过滤器
 *  参数 filter 过滤器   brand | price | region
 *  参数 status 目标状态 on | off
 */
function switch_filter(filter, status)
{
    $("li[ectype='dropdown_filter_title']").attr('class', 'normal');
    $("li[ectype='dropdown_filter_title'] img").attr('src', downimg);
    $("div[ectype='dropdown_filter_content']").hide();

    if (status == 'on')
    {
        $("li[ectype='dropdown_filter_title'][ecvalue='" + filter + "']").attr('class', 'active');
        $("li[ectype='dropdown_filter_title'][ecvalue='" + filter + "'] img").attr('src', upimg);
        $("div[ectype='dropdown_filter_content'][ecvalue='" + filter + "']").show();
    }
}

/* 替换参数 */
function replaceParam(key, value)
{
    var params = null;
	
	/* 后五位是.html，说明开启了伪静态 */
	//if(location.href.substr(-5) == '.html'){ 不兼容IE8
	if(location.href.substr(location.href.length-5,5)=='.html'){
		params = location.href.replace(SITE_URL,'').replace('index.php?','').substr(1).replace('.html','').split('-');
		params[0] = 'app=search';
		params[1] = 'cate_id='+params[1];
		if(params[2]) {
			params[2] = 'page='+params[2];
		}
	}
	else
	{
		params = location.search.substr(1).split('&');
	}

    var found  = false;
    for (var i = 0; i < params.length; i++)
    {
        param = params[i];
        arr   = param.split('=');
        pKey  = arr[0];
        if (pKey == 'page')
        {
            params[i] = 'page=1';
        }
        if (pKey == key)
        {
            params[i] = key + '=' + value;
            found = true;
        }
    }
    if (!found)
    {
        value = transform_char(value);
        params.push(key + '=' + value);
    }
    location.assign(REAL_SITE_URL + '/index.php?' + params.join('&'));
}

/* 删除参数 */
function dropParam(key)
{
    var params = location.search.substr(1).split('&');
    for (var i = 0; i < params.length; i++)
    {
        param = params[i];
        arr   = param.split('=');
        pKey  = arr[0];
        if (pKey == 'page')
        {
            params[i] = 'page=1';
        }
		<!-- sku psmb -->
		if (pKey == 'props' || pKey == 'brand')
		{
			arr1 = arr[1];
			arr1 = arr1.replace(key,'');
			arr1 = arr1.replace(";;",';');
			if(arr1.substr(0,1)==";") {
				arr1 = arr1.substr(1,arr1.length-1);
			}
			if(arr1.substr(arr1.length-1,1) == ";") {
				arr1 = arr1.substr(0,arr1.length-1);
			}
			params[i]=pKey + "=" + arr1;
		}
        if (pKey == key || params[i]=='props=' || params[i]=='brand=')
        {
            params.splice(i, 1);
        }
		<!-- end sku -->
    }
    location.assign(REAL_SITE_URL + '/index.php?' + params.join('&'));
}
