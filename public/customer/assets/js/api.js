const Api = {
    Auth: {},
    Category: {},
    Product: {},
    Order: {},
    VNpay: {},
    Carousel: {},
    Profile: {},
    News: {},
};
(() => {
    $.ajaxSetup({
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        },
        crossDomain: true,
    });
})();
//News
(() => {
    Api.News.GetAll = () =>
        $.ajax({
            url: `/api/customer/news/get`,
            method: "GET",
        });
})();

//Carousel
(() => {
    Api.Carousel.GetAll = () =>
        $.ajax({
            url: `/api/customer/carousel/get`,
            method: "GET",
        });
})();
//Category
(() => {
    Api.Category.GetAll = () =>
        $.ajax({
            url: `/api/customer/category/get`,
            method: "GET",
        });
})();

//Product
(() => {
    Api.Product.GetAll = (filter) =>
        $.ajax({
            url: `/api/customer/product/get-all`,
            method: "GET",
            dataType: "json",
            data: {
                keyword: filter.keyword ?? "",
                tag: filter.tag ?? "",
                page: filter.page ?? "",
                pageSize: filter.pageSize ?? "",
                prices: filter.prices ?? "",
                sort: filter.sort ?? "",
                status: filter.status ?? "",
            },
        });
    Api.Product.GetWithCategory = (id) =>
        $.ajax({
            url: `/api/customer/product/get-with-category/${id}`,
            method: "GET",
        });
    Api.Product.NewArrivals = () =>
        $.ajax({
            url: `/api/customer/product/get-new-arrivals`,
            method: "GET",
        });
    Api.Product.BestSale = () =>
        $.ajax({
            url: `/api/customer/product/get-best-sale`,
            method: "GET",
        });
    Api.Product.Trending = () =>
        $.ajax({
            url: `/api/customer/product/get-trending`,
            method: "GET",
        });
    Api.Product.GetOne = (id) =>
        $.ajax({
            url: `/api/customer/product/get-one/${id}`,
            method: "GET",
        });
    Api.Product.GetRelated = (id) =>
        $.ajax({
            url: `/api/customer/product/get-related/${id}`,
            method: "GET",
        });
    Api.Product.GetOneItem = (id) =>
        $.ajax({
            url: `/api/customer/product/get-one-cart/${id}`,
            method: "GET",
        });
})();

//Order
(() => {
    Api.Order.Create = (data) =>
        $.ajax({
            url: `/api/customer/order/create`,
            method: "POST",
            data: data,
            contentType: false,
            processData: false,
        });
    Api.Order.GetOrder = (id) =>
        $.ajax({
            url: `/api/customer/order/get/${id}`,
            method: "GET",
        });
})();

//Profile
(() => {
    Api.Profile.GetProfile = () =>
        $.ajax({
            url: `/api/customer/profile/get`,
            method: "GET",
        });
    Api.Profile.UpdateProfile = (data) =>
        $.ajax({
            url: `/api/customer/profile/update-profile`,
            method: "POST",
            data: data,
            contentType: false,
            processData: false,
        });
    Api.Profile.GetOrder = () =>
        $.ajax({
            url: `/api/customer/profile/get-order`,
            method: "GET",
        });
    Api.Profile.UpdatePassword = (data) =>
        $.ajax({
            url: `/api/customer/profile/update-password`,
            method: "POST",
            data: data,
            contentType: false,
            processData: false,
        });
})();

//VNpay
(() => {
    Api.VNpay.Create = (data) =>
        $.ajax({
            url: `/payment`,
            method: "POST",
            data: data,
            contentType: false,
            processData: false,
        });
})();
