<div id="a-sidebar">
    <div id="side-head" class="text-center">
        <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcR9JehUoF8VzpKeYzIyTXYgka--AlsEC5SNe5tAGzlteQEYTwm-EBLY5yO2yqmYFdxTOXU&usqp=CAU" id="logo">
        <div class="h6 text-white mt-3 fw-bolder mb-0">Letran Manaoag</div>
        <!-- <div class="h6 badge bg-white text-dark  fw-normal">Alumni Information System</div> -->
        <hr>
        <div class="px-3">
            <div class="card border-0 " style="background-color: rgba(0,0,0,0.3); backdrop-filter: blur(3px);">
                <div class="card-body d-flex p-2 align-items-center">
                    <img src="../assets/images/default.webp" alt="" id="admin-profile" class="me-md-2">
                    <div class="text-start text-white">
                        <div class="h6 mb-0 fw-bold"><?= $_SESSION['user_info']['full_name'] ?></div>
                        <div class="smalltxt">Administrator</div>
                    </div>
                    <i class="fas fa-cog ms-auto align-self-start text-white mt-1 cursor"></i>
                </div>
            </div>
        </div>
        <hr>
        <div id="menus" class="px-3">
            <a href="index" class="menu-link <?= (basename($_SERVER["SCRIPT_FILENAME"], '.php') == 'index') ? 'menu-active' : '' ?>"><i class="fas fa-tachometer-alt me-2"></i>Dashboard <i class="fas fa-angle-right float-end mt-1"></i></a>
            <a href="users" class="menu-link <?= (in_array(basename($_SERVER["SCRIPT_FILENAME"], '.php'), ['users', 'alumni-view'])) ? 'menu-active' : '' ?>"><i class="fas fa-user-graduate me-2"></i>Users <i class="fas fa-angle-right float-end mt-1"></i></a>

            <a href="book" class="menu-link <?= (in_array(basename($_SERVER["SCRIPT_FILENAME"], '.php'), ['book', 'alumni-view'])) ? 'menu-active' : '' ?>"><i class="fas fa-book me-2"></i>Books<i class="fas fa-angle-right float-end mt-1"></i></a>

            <a href="requests" class="menu-link <?= (in_array(basename($_SERVER["SCRIPT_FILENAME"], '.php'), ['requests', 'alumni-view'])) ? 'menu-active' : '' ?>"><i class="fas fa-book me-2"></i>Requests<i class="fas fa-angle-right float-end mt-1"></i></a>


        </div>

    </div>
</div>