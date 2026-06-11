const View = { 
    mainTab: { 
        setVal(data){
            $(".prev-upload").remove()
            $(".product-images").val("")
            data == "" ? null : ViewIndex.multiImage.setVal(data);
        },
        getVal(){
            var fd = new FormData();
            var required_data = [];
            var onPushData = true;
            var data_images         = $(".product-images")[0].files;
            var data_images_preview = [];
            $(`.carousel-data`).find('.image-preview-item.image-load-data').each(function(index, el) {
                data_images_preview.push($(this).attr("data-url"));
            });

            // --Required Value
            if (data_images.length <= 0 && data_images_preview.length == 0) { required_data.push('Hãy chọn ảnh.'); onPushData = false } 

            if (onPushData) { 
                fd.append('data_images_preview', data_images_preview.toString());
                fd.append('image_list_length', data_images.length);
                for (var i = 0; i < data_images.length; i++) {
                    fd.append('image_list_item_'+i, data_images[i]);
                } 
                return fd;
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

        }
    },
    isNumberKey(evt){
        var charCode = (evt.which) ? evt.which : event.keyCode
        if (charCode > 31 && (charCode < 48 || charCode > 57))
            return false;
        return true;
    },
    init(){ 
    }
};
(() => {
    View.init();
 
 

    function init(){
        getData();
    }
    View.mainTab.onPush("Create", (fd) => {
        Api.Carousel.Store(fd)
            .done(res => {
                ViewIndex.helper.showToastSuccess('Success', 'Cập nhật thành công !');
                getData(); 
            })
            .fail(err => { ViewIndex.helper.showToastError('Error', 'Có lỗi sảy ra'); })
            .always(() => { });
    })
    function getData(){
        Api.Carousel.GetAll()
            .done(res => {
                View.mainTab.setVal(res.data[0].url)
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
