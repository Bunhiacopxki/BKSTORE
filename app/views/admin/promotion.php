<?php
require_once dirname(__DIR__, 3) . '/config/db.php';
if(!($_SESSION["Role"] == 'Admin')){
    header("Location: /404");
    exit();
}
?>
<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="/public/image/logo.png" type="image/x-icon">
    <link rel="stylesheet" href="/public/css/admin.css">
    <link href="/public/css/tailwind.min.css" rel="stylesheet">
    <title>Chương trình khuyến mãi | ADMIN BKSTORE</title>
</head>

<body class="bg-gray-100">
    <div id="editPromotionModal" class="fixed inset-0 bg-black bg-opacity-50 flex justify-center items-center hidden z-40">
        <div class="bg-white p-6 rounded-lg w-11/12 lg:w-2/4 z-50 overflow-y-auto" style="max-height: 700px">
            <div class="flex justify-between items-start">
                <h2 class="text-xl font-bold mb-4">Cập nhật chương trình khuyến mãi</h2>
                <button onclick="closeModalPromotion()">✕</button>
            </div>
            <div class="flex gap-2">
                <div class="w-1/2">
                    <label class="block text-sm font-medium text-gray-700">ID:</label>
                    <input type="text" id="id" class="mt-2 mb-4 w-full p-2 border rounded" disabled>
                </div>
                <div class="w-1/2">
                    <label class="block text-sm font-medium text-gray-700">Mã giảm giá:</label>
                    <input type="text" id="magiamgia" class="mt-2 mb-4 w-full p-2 border rounded">
                </div>
            </div>
        
            <div class="flex gap-2">
                <div class="w-1/2">
                    <label class="block text-sm font-medium text-gray-700">Tiền giảm:</label>
                    <input id="tiengiam" class="mt-2 mb-4 w-full p-2 border rounded">
                </div>
                <div class="w-1/2">
                    <label class="block text-sm font-medium text-gray-700">Điều kiện:</label>
                    <input id="dieukien" class="mt-2 mb-4 w-full p-2 border rounded">
                </div>
            </div>
            <div class="flex gap-2">
                <div class="w-1/2">
                    <label class="block text-sm font-medium text-gray-700">Số lượng:</label>
                    <input id="soluong" class="mt-2 mb-4 w-full p-2 border rounded">
                </div>
                <div class="w-1/2">
                    <label class="block text-sm font-medium text-gray-700">Trạng thái:</label>
                    <select id="trangthai" class="mt-2 mb-4 w-full p-2 border rounded h-10">
                        <option value="Kích hoạt">Kích hoạt</option>
                        <option value="Hết hạn">Hết hạn</option>
                    </select>
                </div>
            </div>
        
            <div class="flex justify-end space-x-4 pt-6">
                <button onclick="updatePromotion()" class="bg-green-500 text-white px-4 py-2 rounded">Cập nhật</button>
                <button class="bg-red-500 text-white px-8 py-2 rounded">Xoá</button>
                <button onclick="closeModalPromotion()" class="bg-gray-500 text-white px-4 py-2 rounded">Thoát</button>
            </div>
        </div>
    </div>

    <div id="addPromotionModal" class="fixed inset-0 bg-black bg-opacity-50 flex justify-center items-center hidden z-40">
        <div class="bg-white p-6 rounded-lg w-11/12 lg:w-2/4 z-50 overflow-y-auto" style="max-height: 700px">
            <div class="flex justify-between items-start">
                <h2 class="text-xl font-bold mb-4">Thêm khuyến mãi</h2>
                <button onclick="closeModalAddPromotion()">✕</button>
            </div>

            <div class="flex gap-2">
                <div class="w-1/2">
                    <label class="block text-sm font-medium text-gray-700">Mã giảm giá:</label>
                    <input type="text" id="themmagiamgia" class="mt-2 mb-4 w-full p-2 border rounded" placeholder="Nhập mã giảm giá">
                </div>
                <div class="w-1/2">
                    <label class="block text-sm font-medium text-gray-700">Số lượng:</label>
                    <input id="themsoluong" class="mt-2 mb-4 w-full p-2 border rounded" placeholder="Nhập số lượng">
                </div>
            </div>

            <div class="flex gap-2">
                <div class="w-1/2">
                    <label class="block text-sm font-medium text-gray-700">Tiền giảm:</label>
                    <input id="themtiengiam" class="mt-2 mb-4 w-full p-2 border rounded" placeholder="Nhập tiền giảm">
                </div>
                <div class="w-1/2">
                <label class="block text-sm font-medium text-gray-700">Điều kiện:</label>
                <input id="themdieukien" class="mt-2 mb-4 w-full p-2 border rounded" placeholder="Nhập điều kiện">
            </div>
            </div>
            
            <div class="flex justify-end space-x-4 pt-6">
                <button class="bg-green-500 text-white px-4 py-2 rounded">Thêm</button>
                <button onclick="closeModalAddPromotion()" class="bg-gray-500 text-white px-4 py-2 rounded">Thoát</button>
            </div>
        </div>
    </div>

    <div class="h-screen block md:flex">
        <?php $page = 8; include './partials/sidebar.php'; ?>
        <div id="overlay" class="fixed inset-0 bg-black bg-opacity-50 z-20 hidden"></div>
        <div class="flex-1 overflow-x-hidden">
            <div class="flex flex-col">
                <div class="bg-white fixed top-0 left-0 right-0 z-10">
                    <?php include $_SERVER['DOCUMENT_ROOT'] . '/app/views/admin/partials/header.php'; ?>
                </div>
                <div class="overflow-y-auto overflow-x-hidden pt-16">
                    <main class="container mx-auto min-h-screen px-4 py-4">
                        <nav class="flex gap-2 text pb-4 text-sm text-gray-700">
                            <a href="/" class="cursor-pointer hover:text-blue-500 focus:outline-none">BkStore.Vn</a>
                            <div>&rsaquo;</div>
                            <a href="/admin" class="cursor-pointer hover:text-blue-500 focus:outline-none">Admin</a>
                            <div>&rsaquo;</div>
                            <div class="text-gray-500">Quản lý khuyến mãi</div>
                        </nav>
                        <div class="pb-4">
                            <button class="flex items-center bg-custom-background-bl text-white px-4 py-2 rounded focus:outline-none" onclick="addPromotionModal()">
                                <span class="mr-2">+</span> Thêm khuyến mãi
                            </button>
                        </div>

                        <script>
                            const columnTitles = {
                                id: 'ID',
                                ma: 'Mã giảm giá',
                                tiengiam: 'Tiền giảm',
                                dieukien: "Điều kiện",
                                soluong: 'Số lượng',
                                trangthai: 'Trạng thái',
                                action: "Hành động"
                            };

                            const data = [
                                {
                                    id: '2210',
                                    ma: 'NAPCARD20K',
                                    tiengiam: '20K',
                                    dieukien: "Nạp lần đầu",
                                    soluong: '100',
                                    trangthai: 'Kích hoạt',
                                    action: [
                                        { label: 'Cập nhật', class: 'bg-green-500 text-white', onclick: 'editPromotion' },
                                    ]
                                }
                            ];

                            function editPromotion(item) {
                                document.getElementById("editPromotionModal").classList.remove("hidden");
                                const parseItem= JSON.parse(decodeURIComponent(item));

                                document.getElementById("id").value = parseItem.id;
                                document.getElementById("magiamgia").value = parseItem.ma;
                                document.getElementById("tiengiam").value = parseItem.tiengiam; 
                                document.getElementById("dieukien").value = parseItem.dieukien; 
                                document.getElementById("soluong").value = parseItem.soluong; 
                                document.getElementById("trangthai").value = parseItem.trangthai; 
                                
                                
                                
                            }

                            // function updatePromotion() {
                            //     const status = document.getElementById("userStatus").value;
                            //     alert(`User status updated to: ${status}`);
                            //     closeModal();
                            // }

                            function closeModalPromotion() {
                                document.getElementById("editPromotionModal").classList.add("hidden");
                            }

                            function addPromotionModal() {
                                document.getElementById("addPromotionModal").classList.remove("hidden");
                            }

                            function closeModalAddPromotion() {
                                document.getElementById("addPromotionModal").classList.add("hidden");
                            }
                        </script>
                        <?php
                            $title = "Quản lý sản phẩm";
                            include $_SERVER['DOCUMENT_ROOT'] . '/app/views/client/partials/table.php';
                        ?>
                    </main>
                </div>
                <?php include $_SERVER['DOCUMENT_ROOT'] . '/app/views/admin/partials/footer.php'; ?>
            </div>
        </div>
    </div>
    <script src="/public/js/sidebar.js"></script>
</body>

</html>