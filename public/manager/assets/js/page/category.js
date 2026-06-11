const View = {
    table: {
        __generateDTRow(data){
            console.log(data);
            return [
                `<div class="id-order">${data.id}</div>`,
                data.name,
                `<div class="view-data modal-control" style="cursor: pointer" atr="View" data-id="${data.id}"><i class=" feather-eye "></i></div>
                <div class="view-data modal-control" style="cursor: pointer" atr="Delete" data-id="${data.id}"><i class=" feather-trash "></i></div>`
            ]
        },
        init(){
            var row_table = [
                    {
                        title: 'ID',
                        name: 'id',
                        orderable: true,
                        width: '10%',
                    },
                    {
                        title: 'Tên',
                        name: 'name',
                        orderable: true,
                    },
                    {
                        title: 'Hành động',
                        name: 'Action',
                        orderable: true,
                        width: '15%',
                    },
                ];
            ViewIndex.table.init("#data-table", row_table);
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
        Create: {
            resource: '#modal-create',
            setDefaul(){ this.init();  },
            setVal(data){ },
            getVal(){
                var resource = this.resource;
                var fd = new FormData();
                var required_data = [];
                var onPushData = true;

                var data_name      = $(`${resource}`).find('.data-name').val();

                if (data_name == "") { required_data.push('Hãy nhập tên danh mục.'); onPushData = false }

                if (onPushData) {
                    fd.append('data_name', data_name);
                    return fd;
                }else{
                    $(`${resource}`).find('.error-log .js-errors').remove();
                    var required_noti = ``;
                    for (var i = 0; i < required_data.length; i++) { required_noti += `<li class="error">${required_data[i]}</li>`; }
                    $(`${resource}`).find('.error-log').prepend(` <ul class="js-errors">${required_noti}</ul> `)
                    return false;
                }
            },
            onPush(name, callback){
                var resource = this.resource;
                $(document).on('click', `${this.resource} .push-modal`, function() {
                    if($(this).attr('atr').trim() == name) {
                        var data = View.modals.Create.getVal();
                        if (data) callback(data);
                    }
                });
            },
            init() {
                var modalTitleHTML  = `Tạo mới`;
                var modalBodyHTML   = Template.Category.Create();
                var modalFooterHTML = ['Đóng', 'Tạo mới'];
                View.modals.launch(this.resource, modalTitleHTML, modalBodyHTML, modalFooterHTML);
                View.modals.onClose("#modal-create");
            }
        },
        Update: {
            resource: '#modal-update',
            setDefaul(){ this.init();  },
            setVal(data){
                $(this.resource).find('.data-id').val(data[0].id);
                $(this.resource).find('.data-name').val(data[0].name);
            },
            getVal(){
                var resource = this.resource;
                var fd = new FormData();
                var onPushData = true;

                var data_id      = $(`${resource}`).find('.data-id').val();
                var data_name      = $(`${resource}`).find('.data-name').val();

                if (onPushData) {
                    fd.append('data_id', data_id);
                    fd.append('data_name', data_name);
                    return fd;
                }else{
                    $(`${resource}`).find('.error-log .js-errors').remove();
                    var required_noti = ``;
                    for (var i = 0; i < required_data.length; i++) { required_noti += `<li class="error">${required_data[i]}</li>`; }
                    $(`${resource}`).find('.error-log').prepend(` <ul class="js-errors">${required_noti}</ul> `)
                    return false;
                }
            },
            onPush(name, callback){
                var resource = this.resource;
                $(document).on('click', `${this.resource} .push-modal`, function() {
                    if($(this).attr('atr').trim() == name) {
                        var data = View.modals.Update.getVal();
                        if (data) callback(data);
                    }
                });
            },
            init() {
                var modalTitleHTML = `Cập nhật`;
                var modalBodyHTML  = Template.Category.Update();
                var modalFooterHTML = ['Đóng', 'Cập nhật'];
                View.modals.onClose("#modal-update");
                View.modals.launch(this.resource, modalTitleHTML, modalBodyHTML, modalFooterHTML);
            }
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
            
            this.Create.init();
            this.Update.init();
            this.Delete.init();
        }
    },
    init(){
        View.table.init();
        View.modals.init();
    }
};
(() => {
    View.init();

    View.modals.onControl("Create", () => {
        var resource = View.modals.Create.resource;
        View.modals.onShow(resource);
        View.modals.Create.onPush("Push", (fd) => {
            ViewIndex.helper.showToastProcessing('Processing', 'Đang tạo!');
            Api.Category.Store(fd)
                .done(res => {
                    ViewIndex.helper.showToastSuccess('Success', 'Tạo thành công !');
                    getData();
                })
                .fail(err => { ViewIndex.helper.showToastError('Error', 'Có lỗi sảy ra'); })
                .always(() => { });
            View.modals.onHide(resource)
            View.modals.Create.setDefaul();
        })
    })

    View.modals.onControl("View", (id) => {
        var resource = View.modals.Update.resource;
        View.modals.onShow(resource);
        Api.Category.getOne(id)
            .done(res => {
                View.modals.Update.setVal(res.data);
                View.modals.Update.onPush("Push", (fd) => {
                    ViewIndex.helper.showToastProcessing('Processing', 'Cập nhật!');
                    Api.Category.Update(fd)
                        .done(res => {
                            ViewIndex.helper.showToastSuccess('Success', 'Cập nhật thành công !');
                            getData();
                        })
                        .fail(err => { ViewIndex.helper.showToastError('Error', 'Có lỗi sảy ra'); })
                        .always(() => { });
                    View.modals.onHide(resource)
                    View.modals.Create.setDefaul();
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
            Api.Category.Delete(id)
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
        Api.Category.GetAll()
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
    init();
})();
