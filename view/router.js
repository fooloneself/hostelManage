const checkstand = {
	path: '/checkstand',
    meta: {
        title: '客房登记'
    },
	component: (resolve) => require(['./views/Checkstand.vue'], resolve)
};
const store = {
	path: '/',
	component: (resolve) => require(['./views/SidebarStore.vue'], resolve),
	children:[
		{
			path: 'orderIn',
	        meta: {
	            title: '今日新办'
	        },
        	component: (resolve) => require(['./views/StoreOrderIn.vue'], resolve)
		},
		{
			path: 'orderOut',
	        meta: {
	            title: '即将离店'
	        },
        	component: (resolve) => require(['./views/StoreOrderOut.vue'], resolve)
		},
		{
			path: 'orderAll',
	        meta: {
	            title: '全部订单'
	        },
        	component: (resolve) => require(['./views/StoreOrderAll.vue'], resolve)
		},
		{
			path: 'orderError',
	        meta: {
	            title: '异常订单'
	        },
        	component: (resolve) => require(['./views/StoreOrderError.vue'], resolve)
		},
		{
			path: 'memberRank',
	        meta: {
	            title: '会员等级'
	        },
        	component: (resolve) => require(['./views/StoreMemberRank.vue'], resolve)
		},
		{
			path: 'memberRankEdit',
	        meta: {
	            title: '会员等级 - 编辑'
	        },
        	component: (resolve) => require(['./views/StoreMemberRankEdit.vue'], resolve)
		},
		{
			path: 'memberList',
	        meta: {
	            title: '会员列表'
	        },
        	component: (resolve) => require(['./views/StoreMemberList.vue'], resolve)
		},
		{
			path: 'memberListEdit',
	        meta: {
	            title: '会员列表 - 编辑'
	        },
        	component: (resolve) => require(['./views/StoreMemberListEdit.vue'], resolve)
		},
		{
			path: 'memberConsume',
	        meta: {
	            title: '会员列表 - 个人消费情况'
	        },
        	component: (resolve) => require(['./views/StoreMemberConsume.vue'], resolve)
		}
	]
};
const system = {
	path: '/',
	component: (resolve) => require(['./views/SidebarSystem.vue'], resolve),
	children:[
		{
			path: 'configStore',
	        meta: {
	            title: '门店配置'
	        },
        	component: (resolve) => require(['./views/SystemConfigStore.vue'], resolve)
		},
		{
			path: 'configChannel',
	        meta: {
	            title: '渠道配置'
	        },
        	component: (resolve) => require(['./views/SystemConfigChannel.vue'], resolve)
		},
		{
			path: 'configChannelEdit',
	        meta: {
	            title: '渠道配置 - 编辑'
	        },
        	component: (resolve) => require(['./views/SystemConfigChannelEdit.vue'], resolve)
		},
		{
			path: 'roomType',
	        meta: {
	            title: '房间类型'
	        },
        	component: (resolve) => require(['./views/SystemRoomType.vue'], resolve)
		},
		{
			path: 'roomTypeEdit',
	        meta: {
	            title: '房间类型 - 编辑'
	        },
        	component: (resolve) => require(['./views/SystemRoomTypeEdit.vue'], resolve)
		},
		{
			path: 'roomTypeFloat',
	        meta: {
	            title: '房间类型 - 浮动价格'
	        },
        	component: (resolve) => require(['./views/SystemRoomTypeFloat.vue'], resolve)
		},
		{
			path: 'roomList',
	        meta: {
	            title: '房间列表'
	        },
        	component: (resolve) => require(['./views/SystemRoomList.vue'], resolve)
		},
		{
			path: 'roomListEdit',
	        meta: {
	            title: '房间列表 - 编辑'
	        },
        	component: (resolve) => require(['./views/SystemRoomListEdit.vue'], resolve)
		},
		{
			path: 'personInfo',
	        meta: {
	            title: '个人资料'
	        },
        	component: (resolve) => require(['./views/SystemPersonInfo.vue'], resolve)
		},
		{
			path: 'personPassword',
	        meta: {
	            title: '修改密码'
	        },
        	component: (resolve) => require(['./views/SystemPersonPassword.vue'], resolve)
		},
		{
			path: 'personTips',
	        meta: {
	            title: '意见反馈'
	        },
        	component: (resolve) => require(['./views/SystemPersonTips.vue'], resolve)
		},
	]
};
const platform = {
	path: '/',
	component: (resolve) => require(['./views/SidebarPlatform.vue'], resolve),
	children:[
		{
			path: 'managerInfo',
	        meta: {
	            title: '个人资料'
	        },
        	component: (resolve) => require(['./views/PlatformManagerInfo.vue'], resolve)
		},
		{
			path: 'managerPassword',
	        meta: {
	            title: '修改密码'
	        },
        	component: (resolve) => require(['./views/PlatformManagerPassword.vue'], resolve)
		},
		{
			path: 'powerMenu',
	        meta: {
	            title: '系统菜单'
	        },
        	component: (resolve) => require(['./views/PlatformPowerMenu.vue'], resolve)
		},
		{
			path: 'powerMenuEdit',
	        meta: {
	            title: '系统菜单 - 编辑'
	        },
        	component: (resolve) => require(['./views/PlatformPowerMenuEdit.vue'], resolve)
		},
		{
			path: 'powerMenuChild',
	        meta: {
	            title: '系统菜单 - 子菜单'
	        },
        	component: (resolve) => require(['./views/PlatformPowerMenuChild.vue'], resolve)
		},
		{
			path: 'powerMenuChildEdit',
	        meta: {
	            title: '系统菜单 - 子菜单编辑'
	        },
        	component: (resolve) => require(['./views/PlatformPowerMenuEdit.vue'], resolve)
		},
		{
			path: 'powerRole',
	        meta: {
	            title: '角色配置'
	        },
        	component: (resolve) => require(['./views/PlatformPowerRole.vue'], resolve)
		},
		{
			path: 'powerRoleEdit',
	        meta: {
	            title: '角色配置 - 编辑'
	        },
        	component: (resolve) => require(['./views/PlatformPowerRoleEdit.vue'], resolve)
		},
		{
			path: 'powerAccout',
	        meta: {
	            title: '账号管理'
	        },
        	component: (resolve) => require(['./views/PlatformPowerAccout.vue'], resolve)
		},
		{
			path: 'powerAccoutEdit',
	        meta: {
	            title: '账号管理 - 编辑'
	        },
        	component: (resolve) => require(['./views/PlatformPowerAccoutEdit.vue'], resolve)
		},
		{
			path: 'basicDict',
	        meta: {
	            title: '数据字典'
	        },
        	component: (resolve) => require(['./views/PlatformBasicDict.vue'], resolve)
		},
		{
			path: 'basicDictEdit',
	        meta: {
	            title: '数据字典 - 编辑'
	        },
        	component: (resolve) => require(['./views/PlatformBasicDictEdit.vue'], resolve)
		},
		{
			path: 'basicDictInfo',
	        meta: {
	            title: '数据字典 - 数据列表'
	        },
        	component: (resolve) => require(['./views/PlatformBasicDictInfo.vue'], resolve)
		},
		{
			path: 'basicDictInfoEdit',
	        meta: {
	            title: '数据字典 - 编辑数据'
	        },
        	component: (resolve) => require(['./views/PlatformBasicDictInfoEdit.vue'], resolve)
		},
		{
			path: 'basicLinkage',
	        meta: {
	            title: '联动菜单'
	        },
        	component: (resolve) => require(['./views/PlatformBasicLinkage.vue'], resolve)
		},
		{
			path: 'basicLinkageEdit',
	        meta: {
	            title: '联动菜单 - 编辑'
	        },
        	component: (resolve) => require(['./views/PlatformBasicLinkageEdit.vue'], resolve)
		},
		{
			path: 'basicLinkageChild',
	        meta: {
	            title: '联动菜单 - 子菜单'
	        },
        	component: (resolve) => require(['./views/PlatformBasicLinkageChild.vue'], resolve)
		},
		{
			path: 'basicLinkageChildEdit',
	        meta: {
	            title: '联动菜单 - 子菜单编辑'
	        },
        	component: (resolve) => require(['./views/PlatformBasicLinkageChildEdit.vue'], resolve)
		}
	]
};

const routers = [
    {
        path: '/',
        meta: {
            title: '登录'
        },
        component: (resolve) => require(['./views/Login.vue'], resolve)
    },
    {
        path: '',
        component: (resolve) => require(['./views/Layout.vue'], resolve),
        children:[checkstand,store,system,platform]
    }
];
export default routers;