const store = {
	path: '',
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
	path: '',
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
			path: 'configChannelEdit/:id',
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
			path: 'roomTypeEdit/:id',
	        meta: {
	            title: '房间类型 - 编辑'
	        },
        	component: (resolve) => require(['./views/SystemRoomTypeEdit.vue'], resolve)
		},
		{
			path: 'roomTypeFloat/:typeId',
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
			path: 'roomListEdit/:id',
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
			path: 'personNotice',
	        meta: {
	            title: '公告'
	        },
        	component: (resolve) => require(['./views/SystemPersonNotice.vue'], resolve)
		},
		{
			path: 'personNoticeInfo/:id',
				meta: {
				title: '公告-详情'
			},
			component: (resolve) => require(['./views/SystemPersonNoticeInfo.vue'], resolve)
		},
		{
			path: 'personTips',
	        meta: {
	            title: '意见反馈'
	        },
        	component: (resolve) => require(['./views/SystemPersonTips.vue'], resolve)
		}
	]
};
const platform = {
	path: '',
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
			path: 'powerAccount',
	        meta: {
	            title: '账号管理'
	        },
        	component: (resolve) => require(['./views/PlatformPowerAccount.vue'], resolve)
		},
		{
			path: 'powerAccountEdit',
	        meta: {
	            title: '账号管理 - 编辑'
	        },
        	component: (resolve) => require(['./views/PlatformPowerAccountEdit.vue'], resolve)
		},
		{
			path: 'basicDict',
	        meta: {
	            title: '数据字典'
	        },
        	component: (resolve) => require(['./views/PlatformBasicDict.vue'], resolve)
		},
		{
			path: 'basicDictEdit/:id',
	        meta: {
	            title: '数据字典 - 编辑'
	        },
        	component: (resolve) => require(['./views/PlatformBasicDictEdit.vue'], resolve)
		},
		{
			path: 'basicDictInfo/:code',
	        meta: {
	            title: '数据字典 - 数据列表'
	        },
        	component: (resolve) => require(['./views/PlatformBasicDictInfo.vue'], resolve)
		},
		{
			path: 'basicDictInfoEdit/:code/:id',
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
			path: 'basicLinkageEdit/:id',
	        meta: {
	            title: '联动菜单 - 编辑'
	        },
        	component: (resolve) => require(['./views/PlatformBasicLinkageEdit.vue'], resolve)
		},
		{
			path: 'basicLinkageChild/:code/:pid',
	        meta: {
	            title: '联动菜单 - 子菜单'
	        },
        	component: (resolve) => require(['./views/PlatformBasicLinkageChild.vue'], resolve)
		},
		{
			path: 'basicLinkageChildEdit/:code/:pid/:id',
	        meta: {
	            title: '联动菜单 - 子菜单编辑'
	        },
        	component: (resolve) => require(['./views/PlatformBasicLinkageChildEdit.vue'], resolve)
		},
		{
			path: 'basicNotice',
	        meta: {
	            title: '联动菜单 - 子菜单'
	        },
        	component: (resolve) => require(['./views/PlatformBasicNotice.vue'], resolve)
		},
		{
			path: 'basicNoticeEdit/:id',
	        meta: {
	            title: '联动菜单 - 子菜单编辑'
	        },
        	component: (resolve) => require(['./views/PlatformBasicNoticeEdit.vue'], resolve)
		}
	]
};

const routers = [
    {
        path: '/',
        component: (resolve) => require(['./views/Layout.vue'], resolve),
        children:[store,system,platform,
	        {
	        	path: 'checkstand',
			    meta: {
			        title: '客房登记'
			    },
				component: (resolve) => require(['./views/Checkstand.vue'], resolve)
	        },
	        {
	        	path: 'checkstandEdit',
			    meta: {
			        title: '客房登记 - 订单编辑'
			    },
				component: (resolve) => require(['./views/CheckstandEdit.vue'], resolve)
	        },
	        {
	        	path: 'checkstandView',
			    meta: {
			        title: '客房登记 - 订单编辑'
			    },
				component: (resolve) => require(['./views/CheckstandView.vue'], resolve)
	        },
	        {
	        	path: 'checkstandChange',
			    meta: {
			        title: '客房登记 - 换房'
			    },
				component: (resolve) => require(['./views/CheckstandChange.vue'], resolve)
	        },
	        {
	        	path: 'checkstandOut',
			    meta: {
			        title: '客房登记 - 换房'
			    },
				component: (resolve) => require(['./views/CheckstandOut.vue'], resolve)
	        }
        ]
    },
    {
        path: '/login',
        meta: {
            title: '登录'
        },
        component: (resolve) => require(['./views/SignIn.vue'], resolve)
    },
    {
        path: '/register',
        meta: {
            title: '登录'
        },
        component: (resolve) => require(['./views/SignUp.vue'], resolve)
    }
];
export default routers;