<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="/public/image/logo.png" type="image/x-icon">
    <link href="/public/css/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="/public/css/client.css">
    <link rel="stylesheet" href="/public/css/notyf.min.css">
    <title>Tài khoản | BKSTORE</title>
</head>
<body class="bg-gray-100">
    <div class="h-screen">
        <header id="header-content" class="sticky top-0 z-50">
            <?php include $_SERVER['DOCUMENT_ROOT'] . '/app/views/client/partials/header.php'; ?>
        </header>
        <div class="overflow-y-auto">
            <main class="container max-w-screen-1200 min-h-screen mx-auto pt-16 pb-20 px-1 lg:px-0">
                <div class="flex gap-4 pt-6">
                    <div class="w-1/4 bg-white hidden lg:block flex flex-col text-gray-800 p-4 shadow-md rounded-lg ">
                        <nav>
                            <ul>
                                <li class="mb-4">
                                    <a href="#" class="block py-2 px-4 bg-blue-300 text-white rounded shadow-lg">
                                        Trang chủ
                                    </a>
                                </li>
                                <li class="mb-4">
                                    <a href="/my/account" class="block py-2 px-4 text-gray-800 rounded hover:bg-gray-300 hover:shadow-lg hover:-translate-y-1 transform transition-all duration-200">
                                        Tài khoản của bạn
                                    </a>
                                </li>
                                <li class="mb-4">
                                    <a href="/my/order" class="block py-2 px-4 text-gray-800 rounded hover:bg-gray-300 hover:shadow-lg hover:-translate-y-1 transform transition-all duration-200">
                                        Lịch sử mua hàng
                                    </a>
                                </li>
                                <li class="mb-4">
                                    <a href="/my/support" class="block py-2 px-4 text-gray-800 rounded hover:bg-gray-300 hover:shadow-lg hover:-translate-y-1 transform transition-all duration-200">
                                        Hỗ trợ
                                    </a>
                                </li>
                                <li>
                                    <a href="#" class="block py-2 px-4 text-gray-800 rounded hover:bg-red-500 hover:shadow-lg hover:-translate-y-1 transform transition-all duration-200">
                                        Thoát tài khoản
                                    </a>
                                </li>
                            </ul>
                        </nav>
                    </div>

                    <div class="w-full lg:w-3/4 rounded-lg space-y-6">
                        <button id="likeButton">Like Product</button>
                        <p id="responseMessage"></p>
                    </div>
                </div>


            </main>
        </div>
        <?php $page = 1; include $_SERVER['DOCUMENT_ROOT'] . '/app/views/client/partials/footer.php'; ?>
    </div>
    <!-- <script src="/public/js/client.js"></script> -->
    <script src="/public/js/notyf.min.js"></script>
    <!-- <script src="/public/js/profile.js"></script> -->
    
    <script>
        // Check if user is logged in
        
        document.getElementById('likeButton').addEventListener('click', () => {
            
            fetch(`${window.location.origin}/api/product/like`, {  // Replace with your actual API path
                method: 'GET',
                headers: {
                    'Content-Type': 'application/json'
                },
                credentials: 'include'  // Include credentials if you're using sessions
            })
            .then(response => response.json())
            .then(data => {
                console.log(data)
                if (data.success) {
                    document.getElementById('responseMessage').innerText = 'Đã thích sản phẩm';
                } else {
                    document.getElementById('responseMessage').innerText = data.message || 'Có lỗi xảy ra';
                }
            })
            .catch(error => {
                console.error('Error:', error);
                document.getElementById('responseMessage').innerText = 'Có lỗi xảy ra khi thực hiện yêu cầu';
            });
        });
    </script>
</body>
</html> 
</body>
</html>

