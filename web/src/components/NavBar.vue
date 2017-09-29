<template>
    <div class="header">
        <div class="container">
            <div class="logo">
                <span class="login_img"><i class="fa fa-gg" aria-hidden="true"></i></span>
                <router-link to="/"><span class="login_title">考拉客房管理系统</span></router-link>
            </div>
            <div class="menu">
                <router-link to="/" class="current">客房登记</router-link>
                <a href="house_order_into.php">门店管理</a>
                <a href="member_list.php">会员管理</a>
                <a href="system_account_edit.php">系统配置</a>
            </div>
            <div class="user">
                <a class="name" href="system_person.php">Admin Dun</a>
                <a href="../../index.php">
                <i class="fa fa-sign-out" aria-hidden="true"></i></a>
            </div>
            <div class="cls"></div>
        </div>
    </div>
</template>
<script>
export default {
  name: 'nav-bar'
}
</script>