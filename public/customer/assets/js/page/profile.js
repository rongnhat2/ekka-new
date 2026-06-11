const View = {
	Tab: {
		selected: null,
		setTab(tab){
			$(`.tab-control a`).removeClass("is-selected");
			$(`.tab-control a[data-name=${tab}]`).addClass("is-selected");
		},
		render(name){
			$(".I-profile").find(".tab-body").find(".tab-item").remove()
			if (name == "profile") {
				$(".I-profile").find(".tab-body").append(View.Template.profile.render);
			}else if (name == "order") {
				$(".I-profile").find(".tab-body").append(View.Template.order.render);
			}else if (name == "password") {
				$(".I-profile").find(".tab-body").append(View.Template.password.render);
			}
		},
        onControl(callback){
            $(document).on('click', '.tab-control-item', function() {
                callback($(this).attr('data-name'));
            });
        },
	},
	Data: {
		Render: {
			profile(data){
				$("#user-name").val(data.name);
				$("#user-phone").val(data.phone);
				$("#user-address").val(data.address);
			},
			order(data){

			},
			password(data){

			},
		}
	},
	Template: {
		profile: {
			getVal(){
				var resource = ".I-profile";
                var fd = new FormData();
                var required_data = [];
                var onPushData = true;

                var data_name           = $(`${resource}`).find('#user-name').val();
                var data_phone         	= $(`${resource}`).find('#user-phone').val();
                var data_address       	= $(`${resource}`).find('#user-address').val();

                if (data_name == '') { required_data.push('Nhập Họ tên.'); onPushData = false }
                if (data_phone == '') { required_data.push('Nhập Số điện thoại.'); onPushData = false }
                if (data_address == '') { required_data.push('Nhập Địa chỉ.'); onPushData = false }


                if (onPushData) {
                    fd.append('data_name', data_name);
                    fd.append('data_phone', data_phone);
                    fd.append('data_address', data_address);

                    return fd;
                }else{
                    $(`${resource}`).find('.error-log .js-errors').remove();
                    var required_noti = ``;
                    for (var i = 0; i < required_data.length; i++) { required_noti += `<li class="error">${required_data[i]}</li>`; }
                    $(`${resource}`).find('.error-log').prepend(` <ul class="js-errors">${required_noti}</ul> `)
                    return false;
                }
			},
			render(){
				return `<div class="tab-item">
							<div class="tab-header">
								<h5>Thông tin của tôi</h5>
								<p>Quản lý thông tin hồ sơ để bảo mật tài khoản</p>
							</div>
							<div class="tab-content">
								<div class="error-log"></div>
								<div class="row">
									<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 col-xl-6">
										<div class="form-group">
										  	<label for="user-name">Họ và tên:</label>
										  	<input type="text" class="form-control" id="user-name">
										</div>
									</div>
									<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 col-xl-6">
										<div class="form-group">
										  	<label for="user-phone">Số điện thoại:</label>
										  	<input type="text" class="form-control" id="user-phone">
										</div>
									</div>
									<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 col-xl-6">
										<div class="form-group">
										  	<label for="user-address">Địa chỉ:</label>
										  	<input type="text" class="form-control" id="user-address">
										</div>
									</div>
								</div>
							</div>
							<div class="tab-footer">
								<div class="save-button" atr="Save Data">
									Lưu lại
								</div>
							</div>
						</div>`

			}
		},
		order: {
			getVal(){

			},
			setVal(data){
				$(".data-list").find("tr").remove();
	            var order_status = [
	                "badge-warning badge-pill",
	                "badge-secondary badge-pill",
	                "badge-info badge-pill",
	                "badge-success badge-pill",
	                "badge-danger badge-pill",
	            ];
	            var order_status_title = [
	                "Chờ xử lí",
	                "Chưa hoàn thiện",
	                "Đã hoàn thiện",
	                "Đã giao hàng",
	                "Hoàn trả",
	            ];
				data.map(v => {
					$(".data-list")
						.append(`<tr>
							        <td>${v.id}</td>
							        <td>${v.total}</td>
							        <td>${v.created_at}</td>
							        <td><div class="badge ${order_status[v.order_status]}">${order_status_title[v.order_status]}</div></td>
							        <td><div class="view-item" modal-control="Order" atr="Order" data-id="${v.id}"><i class="fas fa-eye"></i></div></td>
						      	</tr>`)
				})
			},
			onGetData(name, callback){
	            $(document).on('click', '.view-item', function() {
	                var id = $(this).attr('data-id');
	                if($(this).attr('atr').trim() == name) {
	                    callback(id);
	                }
	            });
	        },
	        render_sub_order(data){
	        	$(".data-full-list").find("tr").remove()
	        	var order_status = [
	                "badge-warning badge-pill",
	                "badge-success badge-pill",
	            ];
	            var order_status_title = [
	                "Chờ xử lí",
	                "Có sẵn sản phẩm",
	            ];
	            data.map(v => {
	            	$(".data-full-list")
						.append(`<tr>
							        <td>${v.product_id}</td>
							        <td>${v.name}</td>
							        <td>${v.price}</td>
							        <td>${v.discount} %</td>
							        <td>${v.quantity}</td>
							        <td>${v.total_price}</td>
							        <td><div class="badge ${order_status[v.suborder_status]}">${order_status_title[v.suborder_status]}</div></td>
						      	</tr>`)
	            })
	        	
	        },
			render(){
				return `<div class="tab-item">
							<div class="tab-header">
								<h5>Lịch sử mua hàng</h5>
								<p>Lưu lại lịch sử mua hàng của bạn</p>
							</div>
							<div class="tab-content">
								<table class="table table-bordered">
							    	<thead>
							      		<tr>
									        <th>Mã</th>
									        <th>Tổng giá trị</th>
									        <th>Ngày đặt</th>
									        <th>Trạng thái</th>
									        <th></th>
							      		</tr>
							    	</thead>
								    <tbody class="data-list"> 
								    </tbody>
							  	</table>
							</div>
						</div>`;
			}
		},
		password: {
			getVal(){
				var resource = ".I-profile";
                var fd = new FormData();
                var required_data = [];
                var onPushData = true;

                var data_old          = $(`${resource}`).find('#oldpass').val();
                var data_new         = $(`${resource}`).find('#newpass').val();

                if (data_old.length < 8) { required_data.push('Mật khẩu tối thiểu 8 kí tự.'); onPushData = false }
                if (data_new.length < 8) { required_data.push('Mật khẩu tối thiểu 8 kí tự.'); onPushData = false }


                if (onPushData) {
                    fd.append('data_old', data_old);
                    fd.append('data_new', data_new);
                    return fd;
                }else{
                    $(`${resource}`).find('.error-log .js-errors').remove();
                    var required_noti = ``;
                    for (var i = 0; i < required_data.length; i++) { required_noti += `<li class="error">${required_data[i]}</li>`; }
                    $(`${resource}`).find('.error-log').prepend(` <ul class="js-errors">${required_noti}</ul> `)
                    return false;
                }
			},
			render(){
				return `<div class="tab-item">
							<div class="tab-header">
								<h5>Đổi mật khẩu</h5>
							</div>
							<div class="tab-content">
								<div class="error-log"></div>
								<div class="row">
									<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 col-xl-6">
										<div class="form-group">
										  	<label for="oldpass">Mật khẩu cũ:</label>
										  	<input type="password" class="form-control" id="oldpass">
										</div>
									</div>
									<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 col-xl-6">
										<div class="form-group">
										  	<label for="newpass">Mật khẩu mới:</label>
										  	<input type="password" class="form-control" id="newpass">
										</div>
									</div>
								</div>
							</div>
							<div class="tab-footer">
								<div class="save-button" atr="Update Password">
									Lưu lại
								</div>
							</div>
						</div>`
					}
		},
        onPush(name, callback){
            $(document).on('click', `.save-button`, function() {
                if($(this).attr('atr').trim() == name) {
                   	callback();
                }
            });
        },
	},
    URL: {
        get(id){
            var urlParam    = new URLSearchParams(window.location.search);
            return urlParam.get(id)
        }
    },
    isNumberKey(evt){
        var charCode = (evt.which) ? evt.which : event.keyCode
        if (charCode > 31 && (charCode < 48 || charCode > 57))
            return false;
        return true;
    },
    init(){
        $(document).on('keypress', `#user-phone`, function(event) {
            return View.isNumberKey(event);
        });
    }
};
(() => {
	View.init()
	function init(){
		getTemplate()
	} 
	View.Template.onPush("Save Data", () => {
		var fd = View.Template.profile.getVal();
		if (fd) {
			Api.Profile.UpdateProfile(fd)
	            .done(res => {
	            	$('.error-log').find("ul").remove()
	            	if (res.message == 200) {
	            		$('.error-log').append(`<ul class="js-success"><li class="success">Cập nhật thành công</li></ul> `)
	            	}
	            })
	            .fail(err => {  })
	            .always(() => { });
		}
	})
	View.Template.onPush("Update Password", () => {
		var fd = View.Template.password.getVal();
		if (fd) {
			Api.Profile.UpdatePassword(fd)
	            .done(res => {
	            	$('.error-log').find("ul").remove()
	            	if (res.message == 200) {
	            		$('.error-log').append(`<ul class="js-success"><li class="success">Cập nhật thành công</li></ul> `)
	            	}else{
	            		$('.error-log').append(`<ul class="js-error"><li class="error">Mật khẩu cũ không đúng</li></ul> `)
	            	}
	            })
	            .fail(err => {  })
	            .always(() => { });
		}
	})
	View.Template.order.onGetData("Order", (id) => {
		Api.Order.GetOrder(id)
	            .done(res => {
	            	View.Template.order.render_sub_order(res.data)
	            })
	            .fail(err => {  })
	            .always(() => { });
	})
	function getData(tab){
		if (tab == "profile") {
			Api.Profile.GetProfile()
	            .done(res => {
	            	View.Data.Render.profile(res.data[0])
	            })
	            .fail(err => {  })
	            .always(() => { });
		}else if (tab == "order") {
			Api.Profile.GetOrder()
	            .done(res => {
	            	View.Template.order.setVal(res.data)
	            })
	            .fail(err => {  })
	            .always(() => { });
		}
	}
	View.Tab.onControl((name) => {
		View.Tab.selected = name;
		getTemplate()
	})
	function getTemplate(){
		var tab = View.Tab.selected == null ? "profile" : View.Tab.selected;
		View.Tab.setTab(tab);
		View.Tab.render(tab);
		setTimeout( getData(tab), 500);
	}	
	init()
})();