<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="/public/image/logo.png" type="image/x-icon">
    <link href="/public/css/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="/public/css/client.css">
    <link rel="stylesheet" href="/public/css/notyf.min.css">
    <title>Thanh toán đơn hàng | BKSTORE</title>
</head>

<body class="bg-gray-100">
    <!-- <div id="loading" class="fixed inset-0 flex items-center justify-center bg-white z-50">
        <div class="relative w-14 h-14">
            <img src="/public/image/logo.png" alt="Loading" class="absolute inset-0 w-8 h-8 mx-auto my-auto">
            <div class="loader border-8 rounded-full animate-spin"></div>
        </div>
    </div> -->
    <div class="h-screen">
        <header id="header-content" class="sticky top-0 z-50">
            <?php include $_SERVER['DOCUMENT_ROOT'] . '/app/views/client/partials/header.php'; ?>
        </header>
        <div class="overflow-y-auto">
            <main class="container max-w-2xl mx-auto min-h-screen pt-16 pb-24 px-2 lg:px-0">
                <div class="pt-6">
                    <div class="relative flex justify-center items-center">
                        <button class="absolute left-0 material-icons" onclick="goBack()">
                            <img src="/public/image/arrow.png" alt="arrow" class="w-8 h-8">
                        </button>
                        <div class="font-bold text-lg pb-2">
                            Thông tin
                        </div>
                    </div>
                    <hr>
                    <div class="flex gap-5 pt-2 pb-4 font-bold text-gray-400 px-4 lg:px-0">
                        <div class="w-1/2 text-center border-b-4 p-2 cursor-pointer" onclick="goPaymentInfo()">
                            1. THÔNG TIN
                        </div>
                        <div class="w-1/2 text-center border-b-4 border-gray-400 p-2 cursor-pointer" style="border-color: #0887B3;">
                            2. THANH TOÁN
                        </div>
                    </div>
                    <div class="bg-white rounded-lg border w-full p-4 pl-6 pr-6">
                        <div class="flex gap-5 items-center">
                            <input id="magiamgia" type="text" class="border-b-2 border-gray-300 flex-1 p-2 focus:outline-none focus:border-blue-700" placeholder="Nhập mã giảm giá">
                            <button class="bg-gray-200 rounded-lg px-4 py-2" onclick="checkdiscount()">Áp dụng</button>
                        </div>
                        <div class="flex justify-between text-gray-500 pt-6">
                            <div>Số lượng sản phẩm</div>
                            <div class="text-black" id="totalCount"></div>
                        </div>
                        <div class="flex justify-between text-gray-500 pt-4">
                            <div>Tiền hàng (Tạm tính)</div>
                            <div class="text-black" id="tienhang"></div>
                        </div>
                        <div class="flex justify-between text-gray-500 pt-4">
                            <div>Giảm giá</div>
                            <div class="text-black" id="giamgia">0đ</div>
                        </div>
                        <div class="flex justify-between text-gray-500 pt-4 pb-4">
                            <div>Phí vận chuyển</div>
                            <div class="text-black">Miễn phí</div>
                        </div>
                        <hr>
                        <div class="flex justify-between pt-4">
                            <div class="font-bold">Tổng tiền</div>
                            <div class="text-black" id="tongtien"></div>
                        </div>
                    </div>
                    <div class="pb-2 pt-6 font-bold text-custom-blue">PHƯƠNG THỨC THANH TOÁN</div>
                    <div class="bg-white rounded-lg border h-18 w-full p-6 text-gray-600">
                        <div class="flex gap-4 items-center">
                            <input type="radio" id="cod" name="payment" checked>
                            <div class="flex gap-2 items-center">
                                <img class="h-6 w-6 object-contain" src="/public/image/cod.png" alt="1">
                                <div>Thanh toán khi nhận hàng</div>
                            </div>
                        </div>
                        <div class="flex gap-4 pt-4">
                            <input type="radio" id="bank" name="payment">
                            <div class="flex gap-2 items-center">
                                <img class="h-6 w-6 object-contain" src="/public/image/bank.png" alt="1">
                                <div>Chuyển khoản ngân hàng</div>
                            </div>
                        </div>
                    </div>
                    <div class="pb-2 pt-6 font-bold text-custom-blue">ĐỊA CHỈ NHẬN HÀNG</div>
                    <div class="bg-white rounded-lg border h-18 w-full p-4 pl-6 pr-6">
                        <div class="flex gap-4">
                            <div class="w-1/2">
                                <div class="text-gray-600 mb-2">
                                    Tên khách hàng
                                </div>
                                <input id="tenkhachhang" type="text" class="border rounded-lg w-full p-2" disabled>
                            </div>
                            <div class="w-1/2">
                                <div class="text-gray-600 mb-2">
                                    Số điện thoại
                                </div>
                                <input id="sdt" type="text" class="border rounded-lg w-full p-2" disabled>
                            </div>
                        </div>
                        <div class="text-gray-600 mb-2 mt-2">
                            Địa chỉ nhận hàng
                        </div>
                        <input id="diachi" type="text" class="border rounded-lg w-full p-2" disabled>
                    </div>
                </div>
            </main>
        </div>
        <div class="flex items-center justify-center">
            <div class="max-w-2xl w-full fixed bottom-0">
                <div class="flex items-center justify-between bg-white border w-full p-4 rounded-lg shadow-lg h-18">
                    <div>
                        <div class="font-bold text-custom-blue" id="totalAmount">Tạm tính: 0đ</div>
                        <div class="text-xs">Đã bao gồm các khoản phí khác</div>
                    </div>
                    <button class="bg-custom-background text-white font-bold rounded-lg p-2" onclick="BuyOrder()">Thanh toán</button>
                </div>
            </div>
        </div>

    </div>
    <script src="/public/js/payment.js"></script>
    <script src="/public/js/client.js"></script>
    <script src="/public/js/notyf.min.js"></script>
    <script>
        var notyf = new Notyf({
            duration: 3000,
            position: {
            x: 'right',
            y: 'top',
            },
        });

        function checkdiscount() {
            const magiamgia = document.getElementById('magiamgia').value
            const customerInfo = JSON.parse(localStorage.getItem('customerInfo'));
            const method = document.querySelector('input[name="payment"]:checked')?.id || null;


            if(!magiamgia){
                return
            }
            fetch('/api/order/sale', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    MaGiamGia: magiamgia,
                    tienHang: customerInfo.tongtien,
                    phiVanChuyen: 0,
                    PhuongThucThanhToan: method,
                }),
            })
            .then(response => {
                if (!response.ok) {
                    notyf.error('Đã xảy ra lỗi khi kiểm tra mã giảm giá!');
                    return;
                }
                return response.json();
            })
            .then(data => {
                if (data.success === true) {
                    const so_tien_da_giam = data.so_tien_da_giam;
                    const tong_tien_phai_tra = data.tong_tien_phai_tra;

                    const tongtienDiv1 = document.getElementById('tongtien');
                    tongtienDiv1.innerText = `${tong_tien_phai_tra.toLocaleString()}đ`;

                    const totalAmountDiv1 = document.getElementById('totalAmount');
                    totalAmountDiv1.innerText = `Tạm tính: ${tong_tien_phai_tra.toLocaleString()}đ`;

                    const tongtienDiv2 = document.getElementById('giamgia');
                    tongtienDiv2.innerText = `${so_tien_da_giam.toLocaleString()}đ`;


                    notyf.success('Áp dụng mã giảm giá thành công!');
                    return;
                } else {
                    notyf.error(data.message);
                    return;
                }
            })
            .catch(error => {
                console.error('Error:', error);
            });
        }


        function BuyOrder(){
            const method = document.querySelector('input[name="payment"]:checked')?.id || null;
            const magiamgia = document.getElementById('magiamgia').value
            const sdt = document.getElementById('sdt').value
            const diachi = document.getElementById('diachi').value
            const ten = document.getElementById('tenkhachhang').value
            const selectedProducts = JSON.parse(localStorage.getItem('selectedProducts'));
            let list = '';
            selectedProducts.forEach(item => {
                list += item.id + ', '
            });
            list = list.replace(/,\s*$/, "");


            if(method === 'cod'){
                fetch('/api/order/order', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({
                        PhuongThucThanhToan: 'COD',
                        MaGiamGia: magiamgia,
                        SDT: sdt,
                        DiaChi: diachi,
                        TenNguoiNhan: ten,
                        product_list: list
                    }),
                })
                .then(response => {
                    if (!response.ok) {
                        notyf.error('Đã xảy ra lỗi khi đặt hàng!');
                        return;
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.success === true) {
                        localStorage.removeItem('selectedProducts');

                        notyf.success('Đặt hàng thành công!');
                        setTimeout(() => {
                            window.location.href = '/order/success'
                        }, 2000);
                    } else {
                        notyf.error(data.message);
                        return;
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                });

            }
        }


    </script>
</body>

</html>