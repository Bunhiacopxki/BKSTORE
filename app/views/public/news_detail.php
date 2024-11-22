<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="/public/image/logo.png" type="image/x-icon">
    <link href="/public/css/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="/public/css/swiper-bundle.min.css">
    <link rel="stylesheet" href="/public/css/client.css">
    <link rel="stylesheet" href="/public/css/notyf.min.css">
    <title>Tin tức | BKSTORE</title>
</head>

<body class="bg-gray-100">
    <div class="h-screen">
        <header id="header-content" class="sticky top-0 z-50">
            <?php include $_SERVER['DOCUMENT_ROOT'] . '/app/views/client/partials/header.php'; ?>
        </header>
        <div class="overflow-y-auto">
            <main class="container max-w-screen-1200 mx-auto min-h-screen pt-16 pb-20 lg:pb-10 px-1 lg:px-0">
                <div class="flex gap-2 pt-4">
                    <div class="w-full rounded-lg">              
                        <div class="rounded-lg bg-white ">
                            <div class="flex justify-start items-center">
                                <button class="left-0 material-icons" onclick="goBack()">
                                    <img src="/public/image/arrow.png" alt="arrow" class="w-8 h-8">
                                </button>
                            </div>
                            <hr>
                            <div class="content space-y-4" id="content-new">
                            </div>
                        </div>
                    </div>
                </div>
            </main>
        </div>
        <?php $page = 1;
        include $_SERVER['DOCUMENT_ROOT'] . '/app/views/client/partials/footer.php'; ?>
    </div>
    <script src="/public/js/notyf.min.js"></script>
    <script src="/public/js/heart.js"></script>
    <script src="/public/js/swiper-bundle.min.js"></script>
    <script src="/public/js/client.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const urlParams = new URLSearchParams(window.location.search);
            const ID_New = urlParams.get('id');

            if (ID_New) {
                fetch(`${window.location.origin}/api/system/new`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({ MaTinTuc: ID_New })
                })
                .then(response => response.json())
                .then(data => {
                    console.log(data)
                    console.log(data[0].MaTinTuc)
                    const contentNew = document.getElementById("content-new");
                    contentNew.innerHTML = `
                        <div class="flex flex-col justify-center items-center text-center">
                            <h3 class="text-xl font-semibold text-blue-600">${data[0].TieuDe}</h3>
                        </div>
                        <p class="ml-2 text-gray-400">${data[0].ThoiGianTao}</p>
                        <div class="ml-2 space-y-4">
                            <p class="text-gray-700">
                                ${data[0].NoiDung}
                            </p>
                        </div>
                    `;
                })
                .catch(error => {
                    console.error("Lỗi khi gọi API:", error);
                });
            } else {
                console.error("Không tìm thấy MaDonHang trong URL");
            }
        });

    </script>
</body>

</html> 