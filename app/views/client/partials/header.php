<?php
require_once dirname(__DIR__, 4) . '/config/db.php';
?>
<div class="fixed w-full bg-custom-background border-b text-black p-4 top-0 z-50">
    <div class="container max-w-screen-1200 mx-auto flex items-center justify-between">
        <div class="flex items-center justify-between gap-2 text-2xl font-bold text-white cursor-pointer"
            onclick="window.location.href='/'">
            <img src="/public/image/logo.png" alt="logo web" class="w-8 h-8">
            <div class="hidden md:block">BKSTORE</div>
        </div>
        <div class="flex items-center mx-4 ml-6 flex-1 justify-center">
            <input type="text" placeholder="Tìm kiếm sản phẩm..."
                class="border rounded-l-md p-2 h-10 w-full lg:w-4/6 focus:outline-none">
            <button class="bg-white text-white rounded-r-md p-2 hover:bg-gray-200 h-10">
                <img src="/public/image/search.png" alt="Search" class="w-5 h-5">
            </button>
        </div>
        <?php if (isset($_SESSION["email"])) { ?>
            <div id="avatar-container" class="flex items-center gap-1 relative">
                <div class="relative">
                    <div class="relative ml-4 cursor-pointer pr-1 hidden md:block" id="notice">
                        <img src="/public/image/bell.png" alt="Bell" class="w-8 h-8">
                        <span
                            class="absolute top-0 right-0 bg-red-500 text-white text-xs font-bold rounded-full h-5 w-5 flex items-center justify-center">3</span>
                    </div>
                    <div id="dropdown-notice" class="absolute right-0 top-10 bg-white border rounded-lg shadow-lg hidden">
                        <div class="pt-4 pb-4 pr-3 pl-3 w-80 h-80 overflow-y-auto">
                            <div class="font-bold">Thông báo</div>
                            <div class="flex flex-col gap-2">
                                <div class="pt-2 flex gap-2 cursor-pointer">
                                    <div
                                        class="flex justify-center items-center rounded-full bg-custom-background h-12 w-12">
                                        <img src="/public/image/notice.png" alt="Bell" class="h-10 w-10">
                                    </div>
                                    <div class="text-sm flex-1">
                                        Đơn hàng #BKS1234 đã được giao cho đơn vị vận chuyển.
                                    </div>
                                </div>
                                <div class="pt-2 flex gap-2 cursor-pointer">
                                    <div
                                        class="flex justify-center items-center rounded-full bg-custom-background h-12 w-12">
                                        <img src="/public/image/notice.png" alt="Bell" class="h-10 w-10">
                                    </div>
                                    <div class="text-sm flex-1">
                                        Đơn hàng #BKS1235 đã được giao cho đơn vị vận chuyển.
                                    </div>
                                </div>
                                <div class="pt-2 flex gap-2 cursor-pointer">
                                    <div
                                        class="flex justify-center items-center rounded-full bg-custom-background h-12 w-12">
                                        <img src="/public/image/notice.png" alt="Bell" class="h-10 w-10">
                                    </div>
                                    <div class="text-sm flex-1">
                                        Đơn hàng #BKS1236 đã được giao cho đơn vị vận chuyển.
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="relative ml-4 cursor-pointer pr-1" onclick="goCart()">
                    <img src="/public/image/cart.png" alt="Shopping Cart" class="w-8 h-8">
                    <span
                        class="absolute top-0 right-0 bg-red-500 text-white text-xs font-bold rounded-full h-5 w-5 flex items-center justify-center">3</span>
                </div>
                <div class="relative">
                    <img src="https://ui-avatars.com/api/?background=random&name=<?php echo urlencode($_SESSION["Ten"]); ?>"
                        alt="User Avatar" class="w-8 h-8 rounded-full cursor-pointer ml-3" id="avatar">
                    <div id="dropdown" class="absolute right-0 top-10 bg-white border rounded-lg shadow-lg hidden">
                        <div class="pt-4 pb-4 pr-3 pl-3 w-72">
                            <div class="flex gap-2 w-full">
                                <img src="https://ui-avatars.com/api/?background=random&name=<?php echo urlencode($_SESSION["Ten"]); ?>"
                                    alt="User Avatar" class="w-10 h-10 rounded-full cursor-pointer">
                                <div class="flex-1">
                                    <div class="font-bold"><?php echo $_SESSION["Ten"]; ?></div>
                                    <div class="text-sm text-gray-700"><?php echo $_SESSION["email"]; ?></div>
                                </div>
                            </div>
                            <div class="text-xs text-gray-500 pt-2">Quản lý tài khoản</div>
                            <ul class="pt-2 text-sm">
                                <a href="/my/profile" class="px-3 py-2 hover:bg-gray-100 cursor-pointer rounded-lg flex gap-1 items-center">
                                    <img src="/public/image/user.png" alt="user" class="h-6">
                                    <div>Thông tin tài khoản</div>
                                </a>
                                <li class="px-3 py-2 hover:bg-gray-100 cursor-pointer rounded-lg flex gap-1 items-center">
                                    <img src="/public/image/his.png" alt="his" class="h-6">
                                    <div>Lịch sử mua hàng</div>
                                </li>
                                <li class="px-3 py-2 hover:bg-gray-100 cursor-pointer rounded-lg flex gap-1 items-center">
                                    <img src="/public/image/pw.png" alt="warranty" class="h-6">
                                    <div>Thay đổi mật khẩu</div>
                                </li>
                                <li class="px-3 py-2 hover:bg-gray-100 cursor-pointer rounded-lg flex gap-1 items-center"
                                    id="logout-btn">
                                    <img src="/public/image/exit.png" alt="exit" class="h-6">
                                    <div>Đăng xuất</div>
                                </li>
                            </ul>
                        </div>

                    </div>
                </div>
            </div>
        <?php } else { ?>
            <div id="avatar-container" class="flex items-center gap-1">
                <a href="/auth/login" class="block md:hidden text-white hover:text-black transition duration-300">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" class="w-6 h-6" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="12" cy="12" r="11" />
                        <circle cx="12" cy="7" r="4" />
                        <path d="M5 20c0-7 15-7 15 0" />
                    </svg>
                </a>

                <a href="/auth/login"
                    class="text-white border border-white rounded-md px-2 py-1 hover:bg-white hover:text-black transition duration-300 font-bold hidden md:block"><span>ĐĂNG
                        NHẬP</span></a>
            </div>
        <?php } ?>
    </div>
</div>
<script>
    try {
        function goCart() {
            window.location.href = "/cart"
        }

        document.getElementById("avatar").addEventListener("click", function () {
            const dropdown = document.getElementById("dropdown");
            dropdown.classList.toggle("hidden");
        });

        document.getElementById("notice").addEventListener("click", function () {
            const dropdown_notice = document.getElementById("dropdown-notice");
            dropdown_notice.classList.toggle("hidden");
        });

        window.addEventListener("click", function (e) {
            const avatar = document.getElementById("avatar");
            const dropdown = document.getElementById("dropdown");
            if (!avatar.contains(e.target) && !dropdown.contains(e.target)) {
                dropdown.classList.add("hidden");
            }

            const notice = document.getElementById("notice");
            const dropdown_notice = document.getElementById("dropdown-notice");
            if (!notice.contains(e.target) && !dropdown_notice.contains(e.target)) {
                dropdown_notice.classList.add("hidden");
            }
        });
        document.getElementById("logout-btn").addEventListener("click", function () {
            fetch('api/auth/logout', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                }
            })
                .then(() => {
                    window.location.href = '/';
                })
                .catch(error => {
                    console.error('Lỗi:', error);
                });
        });
    } catch { }
</script>