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
    <title>Quản lý mạng xã hội | ADMIN BKSTORE</title>
</head>

<body class="bg-gray-100">
    <div id="editSocialModal" class="fixed inset-0 bg-black bg-opacity-50 flex justify-center items-center hidden z-40">
        <div class="bg-white p-6 rounded-lg w-11/12 lg:w-2/4 z-50 overflow-y-auto" style="max-height: 700px">
            <div class="flex justify-between items-start">
                <h2 class="text-xl font-bold mb-4">Cập nhật mạng xã hội</h2>
                <button onclick="closeSocialModal()">✕</button>
            </div>
            <div class="flex gap-2">
                <div class="w-1/2">
                    <label class="block text-sm font-medium text-gray-700">Mã mạng xã hội:</label>
                    <input type="text" id="idSocial" class="mt-2 mb-4 w-full p-2 border rounded" disabled>
                </div>
                <div class="w-1/2">
                    <label class="block text-sm font-medium text-gray-700">Trạng thái:</label>
                    <select id="trangthai" class="mt-2 mb-4 w-full p-2 border rounded h-10">
                        <option value="Đang hiện">Đang hiện</option>
                        <option value="Đang ẩn">Đang ẩn</option>
                    </select>
                </div>
            </div>
            <div class="flex gap-2">
                <div class="w-full">
                    <label class="block text-sm font-medium text-gray-700">Liên kết:</label>
                    <input id="lienket" class="mt-2 mb-4 w-full p-2 border rounded h-10">
                </div>
            </div>

            <div class="w-full">
                <label for="hinhanh" class="block text-sm font-medium text-gray-700">Hình ảnh:</label>
                <div class="flex flex-wrap gap-4 mt-2">
                    <div class="relative border border-gray-300 p-1 rounded-md">
                        <img src="/public/image/book1.webp" alt="Hình ảnh 1" class="w-16 h-16 object-cover rounded">
                        <button class="absolute top-0.5 right-0.5 w-6 h-6 bg-red-500 text-white rounded-full flex items-center justify-center hover:bg-red-600 focus:outline-none">
                            ✕
                        </button>
                    </div>
                    <div class="relative border-2 border-dashed border-gray-300 w-20 h-20 rounded-md flex items-center justify-center cursor-pointer hover:bg-gray-100">
                        <input type="file" id="uploadImage" class="opacity-0 absolute inset-0 cursor-pointer" accept="image/*" onchange="handleImageUpload(this)">
                        <span class="text-gray-400">+</span>
                    </div>
                </div>
            </div>
            
            <div class="flex justify-end space-x-4 pt-6">
                <button onclick="updateSocial()" class="bg-green-500 text-white px-4 py-2 rounded">Cập nhật</button>
                <button class="bg-red-500 text-white px-8 py-2 rounded">Xoá</button>
                <button onclick="closeSocialModal()" class="bg-gray-500 text-white px-4 py-2 rounded">Thoát</button>
            </div>
        </div>
    </div>

    <div id="addSocialModal" class="fixed inset-0 bg-black bg-opacity-50 flex justify-center items-center hidden z-40">
        <div class="bg-white p-6 rounded-lg w-11/12 lg:w-2/4 z-50 overflow-y-auto" style="max-height: 700px">
            <div class="flex justify-between items-start">
                <h2 class="text-xl font-bold mb-4">Thêm liên hệ</h2>
                <button onclick="closeModalAddSocial()">✕</button>
            </div>
           
            <div class="flex gap-2">
                <div class="w-full">
                    <label class="block text-sm font-medium text-gray-700">Liên kết:</label>
                    <input id="themlienket" class="mt-2 mb-4 w-full p-2 border rounded h-10" placeholder="Nhập liên kết">
                </div>
            </div>
            
            <div class="w-full">
                <label for="hinhanh" class="block text-sm font-medium text-gray-700">Hình ảnh:</label>
                <div class="flex flex-wrap gap-4 mt-2">
                    <div class="relative border-2 border-dashed border-gray-300 w-20 h-20 rounded-md flex items-center justify-center cursor-pointer hover:bg-gray-100">
                        <input type="file" id="uploadImage" class="opacity-0 absolute inset-0 cursor-pointer" accept="image/*" onchange="handleImageUpload(this)">
                        <span class="text-gray-400">+</span>
                    </div>
                </div>
            </div>
            
            <div class="flex justify-end space-x-4 pt-6">
                <button onclick="updateAddSocial()" class="bg-green-500 text-white px-4 py-2 rounded">Lưu</button>
                <button onclick="closeModalAddSocial()" class="bg-gray-500 text-white px-4 py-2 rounded">Thoát</button>
            </div>
        </div>
    </div>


    <div class="h-screen block md:flex">
        <?php $page = 10; include './partials/sidebar.php'; ?>
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
                            <div class="text-gray-500">Quản lý mạng xã hội</div>
                        </nav>
                        <div class="pb-4">
                            <button class="flex items-center bg-custom-background-bl text-white px-4 py-2 rounded focus:outline-none" onclick="addSocial()">
                                <span class="mr-2">+</span> Thêm mạng xã hội
                            </button>
                        </div>

                        <script>
                            const columnTitles = {
                                id: 'ID',
                                lienket: "Liên kết",
                                trangthai: "Trạng thái",
                                action: 'Hành động'
                            };

                            const data = [
                                {
                                    id: '2210',
                                    lienket: "https://facebook.com/zuck",
                                    trangthai: "Đang hiện",
                                    action: [
                                        { label: 'Cập nhật', class: 'bg-green-500 text-white', onclick: 'editSocial' },
                                    ]
                                },
                                
                            ];

                            function editSocial(item) {
                                document.getElementById("editSocialModal").classList.remove("hidden");
                                const parseItem= JSON.parse(decodeURIComponent(item));
                                
                                document.getElementById("idSocial").value = parseItem.id;
                                document.getElementById("lienket").value = parseItem.lienket;
                                document.getElementById("trangthai").value = parseItem.trangthai;               
                            }

                            function closeSocialModal() {
                                document.getElementById("editSocialModal").classList.add("hidden");
                            }

                            function addSocial() {
                                document.getElementById("addSocialModal").classList.remove("hidden");
                            }

                            function closeModalAddSocial() {
                                document.getElementById("addSocialModal").classList.add("hidden");
                            }


                        </script>
                        <?php
                            $title = "Quản lý mạng xã hội";
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