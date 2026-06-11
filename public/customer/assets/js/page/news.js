const View = {
    News: {
        Render(data){
            data.map(v => {
                $(".new-list")
                    .append(`
                        <div class="col-lg-4 col-md-6 col-sm-12 mb-6 ec-blog-block">
                            <div class="ec-blog-inner">
                                <div class="ec-blog-image">
                                    <a href="/news-detail/${v.id}-${v.slug}">
                                        <img class="blog-image" src="/${v.image}" alt="Blog">
                                    </a>
                                </div>
                                <div class="ec-blog-content">
                                    <h5 class="ec-blog-title"><a href="/news-detail/${v.id}-${v.slug}"></a></h5> 
                                    <div class="ec-blog-desc">${v.description},</div>
                                    <div class="ec-blog-btn"><a href="/news-detail/${v.id}-${v.slug}" class="btn btn-primary">Xem thêm</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    `)
            })
        }
    },
    isNumberKey(evt){
        var charCode = (evt.which) ? evt.which : event.keyCode
        if (charCode > 31 && (charCode < 48 || charCode > 57))
            return false;
        return true;
    },
    formatNumber(num) {
        return num.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1,')
    },
    URL: {
        get(id){
            var urlParam    = new URLSearchParams(window.location.search);
            return urlParam.get(id)
        }
    }, 
    init(){
        $(document).on('keypress', `.product-quantity`, function(event) {
            return View.isNumberKey(event);
        });
    }
};
(() => {
    View.init()
    function init(){
        getNews();  
    } 
    function getNews(){
        Api.News.GetAll()
            .done(res => {
                View.News.Render(res.data)
            })
            .fail(err => {  })
            .always(() => { });
    }  
    init()
})();
