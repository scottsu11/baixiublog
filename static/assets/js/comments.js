require.config({
	paths:{
		"jquery":"/static/assets/vendors/jquery/jquery",
		"bootstrap": "/static/assets/vendors/bootstrap/js/bootstrap",
		"template": "/static/assets/vendors/art-template/template-web",
		"pagination": "/static/assets/vendors/twbs-pagination/jquery.twbsPagination"
	},
	shim: {
		"pagination":{
			deps: ["jquery"]
		},
		"bootstrap": {
			deps: ["jquery"]
		}
	}
})

require(["jquery","template","bootstrap","pagination"],function($,template,bootstrap,pagination){
	$(function(){
		var currentPage =1;
		var pageSize= 10;
		var pageCount;
		function getCommentsData(){
			$.ajax({
				type:"POST",
				url:"api/_getCommentsData.php",
				data:{
					currentPage:currentPage,
					pageSize:pageSize
				},
				success:function(res){
					if(res.code ==1){
						pageCount = res.pageCount;
						var html = template("temp",res.data);
						$("tbody").html(html);
              // 使用分页插件，生成分页结构
              $('.pagination').twbsPagination({
              	totalPages: pageCount,
              	visiblePages: 7,
              	onPageClick: function (event, page) {
              		currentPage = page;
              		getCommentsData();
              	}
              });
            }
          }
        })
		}
		getCommentsData();
	})
})