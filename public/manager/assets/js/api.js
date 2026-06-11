const Api = {
    Image: {},
    Category: {},
    Brand: {},
    Color: {},
    Size: {},
    Material: {},
    Product: {},
    Warehouse: {},
    Order: {},
    Statistic: {},
    Carousel: {},
    News: {},
    Customer: {},
};
(() => {
    $.ajaxSetup({
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        },
        crossDomain: true,
    });
})();

//Carousel
(() => {
    Api.Carousel.GetAll = () =>
        $.ajax({
            url: `/api/admin/carousel/get`,
            method: "GET",
        });
    Api.Carousel.Store = (data) =>
        $.ajax({
            url: `/api/admin/carousel/store`,
            method: "POST",
            data: data,
            contentType: false,
            processData: false,
        });
})();

//Customer
(() => {
    Api.Customer.GetAll = () =>
        $.ajax({
            url: `/api/admin/customer/get`,
            method: "GET",
        });
    Api.Customer.getOne = (id) =>
        $.ajax({
            url: `/api/admin/customer/get-one/${id}`,
            method: "GET",
        });
})();

//Category
(() => {
    Api.Category.GetAll = () =>
        $.ajax({
            url: `/api/admin/category/get`,
            method: "GET",
        });
    Api.Category.Store = (data) =>
        $.ajax({
            url: `/api/admin/category/store`,
            method: "POST",
            data: data,
            contentType: false,
            processData: false,
        });
    Api.Category.getOne = (id) =>
        $.ajax({
            url: `/api/admin/category/get-one/${id}`,
            method: "GET",
        });
    Api.Category.Update = (data) =>
        $.ajax({
            url: `/api/admin/category/update`,
            method: "POST",
            data: data,
            contentType: false,
            processData: false,
        });
    Api.Category.Delete = (id) =>
        $.ajax({
            url: `/api/admin/category/delete/${id}`,
            method: "GET",
        });
})();

//Size
(() => {
    Api.Size.GetAll = () =>
        $.ajax({
            url: `/api/admin/size/get`,
            method: "GET",
        });
    Api.Size.Store = (data) =>
        $.ajax({
            url: `/api/admin/size/store`,
            method: "POST",
            data: data,
            contentType: false,
            processData: false,
        });
    Api.Size.getOne = (id) =>
        $.ajax({
            url: `/api/admin/size/get-one/${id}`,
            method: "GET",
        });
    Api.Size.Update = (data) =>
        $.ajax({
            url: `/api/admin/size/update`,
            method: "POST",
            data: data,
            contentType: false,
            processData: false,
        });
    Api.Size.Delete = (id) =>
        $.ajax({
            url: `/api/admin/size/delete/${id}`,
            method: "GET",
        });
})();

//Material
(() => {
    Api.Material.GetAll = () =>
        $.ajax({
            url: `/api/admin/material/get`,
            method: "GET",
        });
    Api.Material.Store = (data) =>
        $.ajax({
            url: `/api/admin/material/store`,
            method: "POST",
            data: data,
            contentType: false,
            processData: false,
        });
    Api.Material.getOne = (id) =>
        $.ajax({
            url: `/api/admin/material/get-one/${id}`,
            method: "GET",
        });
    Api.Material.Update = (data) =>
        $.ajax({
            url: `/api/admin/material/update`,
            method: "POST",
            data: data,
            contentType: false,
            processData: false,
        });
    Api.Material.Delete = (id) =>
        $.ajax({
            url: `/api/admin/material/delete/${id}`,
            method: "GET",
        });
})();

//Brand
(() => {
    Api.Brand.GetAll = () =>
        $.ajax({
            url: `/api/admin/brand/get`,
            method: "GET",
        });
    Api.Brand.Store = (data) =>
        $.ajax({
            url: `/api/admin/brand/store`,
            method: "POST",
            data: data,
            contentType: false,
            processData: false,
        });
    Api.Brand.getOne = (id) =>
        $.ajax({
            url: `/api/admin/brand/get-one/${id}`,
            method: "GET",
        });
    Api.Brand.Update = (data) =>
        $.ajax({
            url: `/api/admin/brand/update`,
            method: "POST",
            data: data,
            contentType: false,
            processData: false,
        });
    Api.Brand.Delete = (id) =>
        $.ajax({
            url: `/api/admin/brand/delete/${id}`,
            method: "GET",
        });
})();

//Color
(() => {
    Api.Color.GetAll = () =>
        $.ajax({
            url: `/api/admin/color/get`,
            method: "GET",
        });
    Api.Color.Store = (data) =>
        $.ajax({
            url: `/api/admin/color/store`,
            method: "POST",
            data: data,
            contentType: false,
            processData: false,
        });
    Api.Color.getOne = (id) =>
        $.ajax({
            url: `/api/admin/color/get-one/${id}`,
            method: "GET",
        });
    Api.Color.Update = (data) =>
        $.ajax({
            url: `/api/admin/color/update`,
            method: "POST",
            data: data,
            contentType: false,
            processData: false,
        });
    Api.Color.Delete = (id) =>
        $.ajax({
            url: `/api/admin/color/delete/${id}`,
            method: "GET",
        });
})();
//Product
(() => {
    Api.Product.GetAll = () =>
        $.ajax({
            url: `/api/admin/product/get`,
            method: "GET",
        });
    Api.Product.GetFree = () =>
        $.ajax({
            url: `/api/admin/product/getfree`,
            method: "GET",
        });

    Api.Product.GetDiscount = () =>
        $.ajax({
            url: `/api/admin/product/get-discount`,
            method: "GET",
        });

    Api.Product.Store = (data) =>
        $.ajax({
            url: `/api/admin/product/store`,
            method: "POST",
            data: data,
            contentType: false,
            processData: false,
        });

    Api.Product.getOne = (id) =>
        $.ajax({
            url: `/api/admin/product/get-one/${id}`,
            method: "GET",
        });
    Api.Product.Update = (data) =>
        $.ajax({
            url: `/api/admin/product/update`,
            method: "POST",
            data: data,
            contentType: false,
            processData: false,
        });
    Api.Product.UpdateDiscount = (data) =>
        $.ajax({
            url: `/api/admin/product/update-discount`,
            method: "POST",
            data: data,
            contentType: false,
            processData: false,
        });
    Api.Product.DeleteDiscount = (id) =>
        $.ajax({
            url: `/api/admin/product/delete-discount/${id}`,
            method: "GET",
        });
    Api.Product.Delete = (id) =>
        $.ajax({
            url: `/api/admin/product/delete/${id}`,
            method: "GET",
        });
    Api.Product.Trending = (id) =>
        $.ajax({
            url: `/api/admin/product/update-trending`,
            method: "PUT",
            dataType: "json",
            data: {
                id: id ?? "",
            },
        });
    Api.Product.GetVar = (product_id) =>
        $.ajax({
            url: `/api/admin/product/get-var`,
            method: "GET",
            dataType: "json",
            data: {
                product_id: product_id ?? "",
            },
        });
})();

//Order
(() => {
    Api.Order.GetAll = (id) =>
        $.ajax({
            url: `/api/admin/order/get`,
            method: "GET",
            dataType: "json",
            data: {
                id: id ?? "",
            },
        });
    Api.Order.GetOne = (id) =>
        $.ajax({
            url: `/api/admin/order/get-one`,
            method: "GET",
            dataType: "json",
            data: {
                id: id ?? "",
            },
        });
    Api.Order.Update = (data) =>
        $.ajax({
            url: `/api/admin/order/update`,
            method: "POST",
            data: data,
            contentType: false,
            processData: false,
        });
})();

// Image
(() => {
    Api.Image.Create = (data) =>
        $.ajax({
            url: `/api/admin/post-image`,
            method: "POST",
            data: data,
            contentType: false,
            processData: false,
        });
})();

// Warehouse
(() => {
    Api.Warehouse.GetDataItem = () =>
        $.ajax({
            url: `/api/admin/warehouse/get-item`,
            method: "GET",
        });
    Api.Warehouse.GetDataHistory = () =>
        $.ajax({
            url: `/api/admin/warehouse/get-history`,
            method: "GET",
        });
    Api.Warehouse.Store = (data) =>
        $.ajax({
            url: `/api/admin/warehouse/store`,
            method: "POST",
            data: data,
            contentType: false,
            processData: false,
        });
    Api.Warehouse.getOne = (id) =>
        $.ajax({
            url: `/api/admin/warehouse/get-ware-one/${id}`,
            method: "GET",
        });
})();

// Statistic
(() => {
    Api.Statistic.getTotal = () =>
        $.ajax({
            url: `/api/admin/statistic/get-total`,
            method: "GET",
        });
    Api.Statistic.getBestSale = () =>
        $.ajax({
            url: `/api/admin/statistic/get-best-sale`,
            method: "GET",
        });
    Api.Statistic.getCustomerBuy = () =>
        $.ajax({
            url: `/api/admin/statistic/get-customer`,
            method: "GET",
        });
    Api.Statistic.getEmpty = () =>
        $.ajax({
            url: `/api/admin/statistic/get-empty`,
            method: "GET",
        });
})();
