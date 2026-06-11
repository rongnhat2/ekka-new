const View = {
    table: {
        __generateDTRow(data){ 
            return [
                `<div class="id-order">${data.id}</div>`,
                data.title,
                data.description, 
                `<div class="image-table-preview" style="background-image: url('/${data.image}')"></div>`,
                `<div class="view-data modal-control main-tab-control" style="cursor: pointer" atr="View" data-id="${data.id}"><i class="feather-eye"></i></div>
                <div class="view-data modal-control" style="cursor: pointer" atr="Delete" data-id="${data.id}"><i class="feather-trash"></i></div>`
            ]
        },
        init(){
            var row_table = [
                    {
                        title: 'ID',
                        name: 'id',
                        orderable: true,
                        width: '5%',
                    },
                    {
                        title: 'Tên',
                        name: 'name',
                        orderable: true,
                        width: '10%',
                    }, 
                    {
                        title: 'Mô tả ngắn',
                        name: 'name',
                        orderable: true,
                        width: '20%',
                    },
                    {
                        title: 'Hình ảnh',
                        name: 'name',
                        orderable: true,
                        width: '20%',
                    }, 
                    {
                        title: 'Hành động',
                        name: 'Action',
                        orderable: true,
                        width: '10%',
                    },
                ];
            ViewIndex.table.init("#data-table", row_table);
        }
    }, 
    mainTab: { 
        onControl(name, callback){
            $(document).on('click', '.main-tab-control', function() {
                var id = $(this).attr('data-id');
                if($(this).attr('atr').trim() == name) {
                    callback(id);
                }
            });
        },
        onClose(){
            $(document).on('click', '.main-tab-close', function() {
                $('.main-tab').removeClass('on-show');
                $('.main-tab[tab-name=Main]').addClass('on-show');
            });
        },
        onShow(resource){
            $('.main-tab').removeClass('on-show');
            $(`.main-tab[tab-name=${resource}]`).addClass('on-show');
        },
        setDefaul(){
            $(document).off('click', `.push-data`);
            $(".data-title").val("") 
            $(".data-images").val("") 
            $(".data-description").val("")  
            ViewIndex.summerNote.update(".data-detail", "")
        }, 
        setVal(data){  
            $(".data-id").val(data.id)
            $(".data-title").val(data.title)
            $(".data-description").val(data.description)
            $(".data-image-preview").css({ "background-image": `url(/'${data.image}')`}) 
            ViewIndex.summerNote.update(`.data-detail`, data.detail);
        },
        getVal(){
            var fd = new FormData();
            var required_data = [];
            var onPushData = true;

            var data_id             = $(".data-id").val();
            var data_title          = $(".data-title").val();
            var data_description    = $(".data-description").val(); 
            var data_images         = $(".image-input")[0].files;
            var data_detail         = $(".data-detail").val(); 
  
            // --Required Value 
            if (data_images.length <= 0  ) { required_data.push('Hãy chọn banner.'); onPushData = false }
            if (data_title == '') { required_data.push('Nhập tên.'); onPushData = false }
            if (data_description == '') { required_data.push('Nhập mô tả ngắn.'); onPushData = false }
            if (data_detail == '') { required_data.push('Nhập mô tả đây đủ.'); onPushData = false }

            if (onPushData) {
                fd.append('data_id', data_id);
                fd.append('data_title', data_title);
                fd.append('data_description', data_description);
                fd.append('data_detail', data_detail); 
                fd.append('data_images', data_images[0]); 

                return fd;
            }else{
                $(`#tab-create`).find('.error-log .js-errors').remove();
                var required_noti = ``;
                for (var i = 0; i < required_data.length; i++) { required_noti += `<li class="error">${required_data[i]}</li>`; }
                $(`#tab-create`).find('.error-log').prepend(` <ul class="js-errors">${required_noti}</ul> `)
                return false;
            }
        },
        onPush(name, callback){
            $(document).on('click', ".push-data", function() {
                if($(this).attr('atr').trim() == name) {
                    var data = View.mainTab.getVal();
                    if (data) callback(data);
                }
            });
        },
        init(){
            View.mainTab.onClose();   
            this.setDefaul(); 
            ViewIndex.summerNote.init(".data-detail", "Mô tả đầy đủ", 400);
        }
    },

    modals: {
        onControl(name, callback){
            $(document).on('click', '.modal-control', function() {
                var id = $(this).attr('data-id');
                if($(this).attr('atr').trim() == name) {
                    callback(id);
                }
            });
        },
        launch(resource, modalTitleHTML, modalBodyHTML, modalFooterHTML){
            $(`${resource} .modal-title`).html(modalTitleHTML);
            $(`${resource} .modal-body`).html(modalBodyHTML);
            $(`${resource} .modal-footer .close-modal`).html(modalFooterHTML[0]);
            $(`${resource} .modal-footer .push-modal`).html(modalFooterHTML[1]);
        },
        onShow(resource){
            $(resource).modal('show');
            $(document).off('click', `${resource} .push-modal`);
        },
        onHide(resource){
            $(resource).modal('hide');
        },
        onClose(resource){
            $(document).on('click', '.close-modal', function() {
                $(resource).modal('hide');
            });
        },
        Delete: {
            resource: '#modal-delete',
            setDefaul(){ this.init(); },
            textDefaul(){ },
            setVal(data){ },
            getVal(){
            },
            onPush(name, callback){
                var resource = this.resource;
                $(document).on('click', `${this.resource} .push-modal`, function() {
                    if($(this).attr('atr').trim() == name) {
                        callback();
                    }
                });
            },
            init() {
                var modalTitleHTML = `Xóa`;
                var modalBodyHTML  = Template.Category.Delete();
                var modalFooterHTML = ['Đóng', 'Xóa'];
                View.modals.onClose("#modal-delete");
                View.modals.launch(this.resource, modalTitleHTML, modalBodyHTML, modalFooterHTML);
            }
        },
        init() {
            this.Delete.init();
        }
    },
    isNumberKey(evt){
        var charCode = (evt.which) ? evt.which : event.keyCode
        if (charCode > 31 && (charCode < 48 || charCode > 57))
            return false;
        return true;
    },
    init(){
        View.table.init();
        View.mainTab.init();
        View.modals.init();
    }
};
(() => {
    View.init();

    View.mainTab.onControl("Create", () => { 
        View.mainTab.setDefaul();
        View.mainTab.onShow("Create");
        View.mainTab.onPush("Create", (fd) => {
            Api.News.Store(fd)
                .done(res => {
                    ViewIndex.helper.showToastSuccess('Success', 'Tạo thành công !');
                    getData();
                    View.mainTab.onShow("Main");
                })
                .fail(err => { ViewIndex.helper.showToastError('Error', 'Có lỗi sảy ra'); })
                .always(() => { });
        })
    })

    View.mainTab.onControl("View", (id) => {
        View.mainTab.setDefaul();
        
        Api.News.getOne(id)
            .done(res => {
                View.mainTab.onShow("Create");
                setTimeout(View.mainTab.setVal(res.data[0]), 1000)
                View.mainTab.onPush("Create", (fd) => {
                    Api.News.Update(fd)
                        .done(res => {
                            ViewIndex.helper.showToastSuccess('Success', 'Tạo thành công !');
                            getData();
                            View.mainTab.onShow("Main");
                        })
                        .fail(err => { ViewIndex.helper.showToastError('Error', 'Có lỗi sảy ra'); })
                        .always(() => { });
                })
            })
            .fail(err => { ViewIndex.helper.showToastError('Error', 'Có lỗi sảy ra'); })
            .always(() => { });
    }) 
    View.modals.onControl("Delete", (id) => {
        var resource = View.modals.Delete.resource;
        View.modals.onShow(resource);
        View.modals.Delete.onPush("Push", () => {
            ViewIndex.helper.showToastProcessing('Processing', 'Đang xóa!');
            Api.News.Delete(id)
                .done(res => {
                    ViewIndex.helper.showToastSuccess('Success', 'Xóa thành công !');
                    getData();
                })
                .fail(err => { ViewIndex.helper.showToastError('Error', 'Có lỗi sảy ra'); })
                .always(() => { });
            View.modals.onHide(resource)
            View.modals.Delete.setDefaul();
        })
    }) 

    function init(){
        getData();
    }

    function getData(){
        Api.News.GetAll()
            .done(res => {
                ViewIndex.table.clearRows();
                Object.values(res.data).map(v => {
                    ViewIndex.table.insertRow(View.table.__generateDTRow(v));
                    ViewIndex.table.render();
                })
                ViewIndex.table.render();
            })
            .fail(err => { ViewIndex.helper.showToastError('Error', 'Có lỗi sảy ra'); })
            .always(() => { });
    }
    function debounce(f, timeout) {
        let isLock = false;
        let timeoutID = null;
        return function(item) {
            if(!isLock) {
                f(item);
                isLock = true;
            }
            clearTimeout(timeoutID);
            timeoutID = setTimeout(function() {
                isLock = false;
            }, timeout);
        }
    }
    init();
})();
