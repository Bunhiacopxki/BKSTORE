<?php
require_once dirname(__DIR__, 3) . '/config/db.php';
if(!isset($_SESSION["email"])){
    header("Location: /auth/login");
    exit();
}
?>

<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="/public/image/logo.png" type="image/x-icon">
    <link href="/public/css/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="/public/css/client.css">
    <title>Thông báo | BKSTORE</title>
</head>

<body class="bg-gray-100">
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
                            Thông báo
                        </div>
                    </div>
                    <hr>
                    <div class="h-full overflow-y-auto">
                        <div class="pt-4 flex flex-col gap-2">
                            <div class="bg-white h-24 w-full rounded-lg bg-blue-100 p-4 flex gap-4 cursor-pointer">
                                <div
                                    class="flex justify-center items-center rounded-full bg-custom-background h-14 w-14">
                                    <img src="/public/image/notice.png" alt="Bell" class="h-10 w-10">
                                </div>
                                <div class="text-sm text-gray-800 flex-1">
                                    <div class="flex flex-col justify-between h-full">
                                        <div>
                                            Đơn hàng #BKS111 - đã được giao cho đơn vị vận chuyển VNPost
                                        </div>
                                        <div class="text-xs text-gray-500">
                                            22:00:00 01-01-2024
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="bg-white h-24 w-full rounded-lg bg-blue-100 p-4 flex gap-4 cursor-pointer">
                                <div
                                    class="flex justify-center items-center rounded-full bg-custom-background h-14 w-14">
                                    <img src="/public/image/notice.png" alt="Bell" class="h-10 w-10">
                                </div>
                                <div class="text-sm text-gray-800 flex-1">
                                    <div class="flex flex-col justify-between h-full">
                                        <div>
                                            Đơn hàng #BKS111 - đã được giao cho đơn vị vận chuyển VNPost
                                        </div>
                                        <div class="text-xs text-gray-500">
                                            22:00:00 01-01-2024
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="bg-white h-24 w-full rounded-lg bg-blue-100 p-4 flex gap-4 cursor-pointer">
                                <div
                                    class="flex justify-center items-center rounded-full bg-custom-background h-14 w-14">
                                    <img src="/public/image/notice.png" alt="Bell" class="h-10 w-10">
                                </div>
                                <div class="text-sm text-gray-800 flex-1">
                                    <div class="flex flex-col justify-between h-full">
                                        <div>
                                            Đơn hàng #BKS111 - đã được giao cho đơn vị vận chuyển VNPost
                                        </div>
                                        <div class="text-xs text-gray-500">
                                            22:00:00 01-01-2024
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
        </div>
        <?php $page = 2;
        include $_SERVER['DOCUMENT_ROOT'] . '/app/views/client/partials/footer.php'; ?>
    </div>
    <script src="/public/js/client.js"></script>
</body>

</html>