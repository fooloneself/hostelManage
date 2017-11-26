const admin = [
	{
		path: 'orderIn',
        meta: {
            title: '今日到店'
        },
    	component: (resolve) => require(['./views/store/OrderIn.vue'], resolve)
	},
	{
		path: 'orderOut',
        meta: {
            title: '今日离店'
        },
    	component: (resolve) => require(['./views/store/OrderOut.vue'], resolve)
	},
	{
		path: 'orderFuture',
        meta: {
            title: '预订订单'
        },
    	component: (resolve) => require(['./views/store/OrderFuture.vue'], resolve)
	},
    {
        path: 'orderAll',
        meta: {
            title: '全部订单'
        },
        component: (resolve) => require(['./views/store/OrderAll.vue'], resolve)
    },
	{
		path: 'orderError',
        meta: {
            title: '异常订单'
        },
    	component: (resolve) => require(['./views/store/OrderError.vue'], resolve)
	},
	// 营销管理
	{
		path: 'memberRank',
        meta: {
            title: '会员等级'
        },
    	component: (resolve) => require(['./views/market/MemberRank.vue'], resolve)
	},
	{
		path: 'memberRankEdit/:id',
        meta: {
            title: '会员等级 - 编辑'
        },
    	component: (resolve) => require(['./views/market/MemberRankEdit.vue'], resolve)
	},
	{
		path: 'memberRankBirthday',
        meta: {
            title: '会员等级 - 生日福利'
        },
    	component: (resolve) => require(['./views/market/MemberRankBirthday.vue'], resolve)
	},
	{
		path: 'memberList',
        meta: {
            title: '会员列表'
        },
    	component: (resolve) => require(['./views/market/MemberList.vue'], resolve)
	},
	{
		path: 'memberListEdit/:id',
        meta: {
            title: '会员列表 - 编辑'
        },
    	component: (resolve) => require(['./views/market/MemberListEdit.vue'], resolve)
	},
	{
		path: 'memberBlack',
        meta: {
            title: '黑名单'
        },
    	component: (resolve) => require(['./views/market/MemberBlack.vue'], resolve)
	},
	{
		path: 'memberBlackEdit/:id',
        meta: {
            title: '黑名单 - 添加'
        },
    	component: (resolve) => require(['./views/market/MemberBlackEdit.vue'], resolve)
	},
	{
		path: 'discount',
        meta: {
            title: '折扣'
        },
    	component: (resolve) => require(['./views/market/PromotionDiscount.vue'], resolve)
	},
	{
		path: 'discountEdit',
        meta: {
            title: '折扣 - 编辑'
        },
    	component: (resolve) => require(['./views/market/PromotionDiscountEdit.vue'], resolve)
	},
	{
		path: 'cutdown',
        meta: {
            title: '满减'
        },
    	component: (resolve) => require(['./views/market/PromotionCutdown.vue'], resolve)
	},
	{
		path: 'cutdownEdit',
        meta: {
            title: '满减 - 编辑'
        },
    	component: (resolve) => require(['./views/market/PromotionCutdownEdit.vue'], resolve)
	},
	{
		path: 'bargain',
        meta: {
            title: '特价房'
        },
    	component: (resolve) => require(['./views/market/PromotionBargain.vue'], resolve)
	},
	{
		path: 'bargainEdit',
        meta: {
            title: '特价房 - 编辑'
        },
    	component: (resolve) => require(['./views/market/PromotionBargainEdit.vue'], resolve)
	},
	{
		path: 'planEdit',
        meta: {
            title: '活动执行计划'
        },
    	component: (resolve) => require(['./views/market/PromotionPlanEdit.vue'], resolve)
	},
	// 门店管理
	{
		path: 'configStore',
        meta: {
            title: '门店配置'
        },
    	component: (resolve) => require(['./views/system/ConfigStore.vue'], resolve)
	},
	{
		path: 'configChannel',
        meta: {
            title: '渠道配置'
        },
    	component: (resolve) => require(['./views/system/ConfigChannel.vue'], resolve)
	},
	{
		path: 'configChannelEdit/:id',
        meta: {
            title: '渠道配置 - 编辑'
        },
    	component: (resolve) => require(['./views/system/ConfigChannelEdit.vue'], resolve)
	},
	{
		path: 'configAccount',
        meta: {
            title: '账号配置'
        },
    	component: (resolve) => require(['./views/system/ConfigAccount.vue'], resolve)
	},
	{
		path: 'configAccountEdit',
        meta: {
            title: '账号配置 - 编辑'
        },
    	component: (resolve) => require(['./views/system/ConfigAccountEdit.vue'], resolve)
	},
	{
		path: 'roomType',
        meta: {
            title: '房间类型'
        },
    	component: (resolve) => require(['./views/system/RoomType.vue'], resolve)
	},
	{
		path: 'roomTypeEdit/:id',
        meta: {
            title: '房间类型 - 编辑'
        },
    	component: (resolve) => require(['./views/system/RoomTypeEdit.vue'], resolve)
	},
	{
		path: 'roomTypeFloat/:typeId',
        meta: {
            title: '房间类型 - 浮动价格'
        },
    	component: (resolve) => require(['./views/system/RoomTypeFloat.vue'], resolve)
	},
	{
		path: 'roomList',
        meta: {
            title: '房间列表'
        },
    	component: (resolve) => require(['./views/system/RoomList.vue'], resolve)
	},
	{
		path: 'roomListEdit/:id',
        meta: {
            title: '房间列表 - 编辑'
        },
    	component: (resolve) => require(['./views/system/RoomListEdit.vue'], resolve)
	},
    {
        path: 'roomListMulti/:id',
        meta: {
            title: '房间列表 - 批量新增'
        },
        component: (resolve) => require(['./views/system/RoomListMulti.vue'], resolve)
    },
	{
		path: 'personInfo',
        meta: {
            title: '个人资料'
        },
    	component: (resolve) => require(['./views/system/PersonInfo.vue'], resolve)
	},
	{
		path: 'personPassword/:adminId',
        meta: {
            title: '修改密码'
        },
    	component: (resolve) => require(['./views/system/PersonPassword.vue'], resolve)
	},
	{
		path: 'personNotice',
        meta: {
            title: '公告'
        },
    	component: (resolve) => require(['./views/system/PersonNotice.vue'], resolve)
	},
	{
		path: 'personNoticeInfo/:id',
			meta: {
			title: '公告-详情'
		},
		component: (resolve) => require(['./views/system/PersonNoticeInfo.vue'], resolve)
	},
	{
		path: 'personTips',
        meta: {
            title: '意见反馈'
        },
    	component: (resolve) => require(['./views/system/PersonTips.vue'], resolve)
	},
	// 系统管理
	{
		path: 'managerInfo',
        meta: {
            title: '个人资料'
        },
    	component: (resolve) => require(['./views/platform/ManagerInfo.vue'], resolve)
	},
	{
		path: 'managerPassword/:adminId',
        meta: {
            title: '修改密码'
        },
    	component: (resolve) => require(['./views/platform/ManagerPassword.vue'], resolve)
	},
	{
		path: 'powerMenu',
        meta: {
            title: '系统菜单'
        },
    	component: (resolve) => require(['./views/platform/PowerMenu.vue'], resolve)
	},
	{
		path: 'powerMenuEdit',
        meta: {
            title: '系统菜单 - 编辑'
        },
    	component: (resolve) => require(['./views/platform/PowerMenuEdit.vue'], resolve)
	},
	{
		path: 'powerMenuChild',
        meta: {
            title: '系统菜单 - 子菜单'
        },
    	component: (resolve) => require(['./views/platform/PowerMenuChild.vue'], resolve)
	},
	{
		path: 'powerMenuChildEdit',
        meta: {
            title: '系统菜单 - 子菜单编辑'
        },
    	component: (resolve) => require(['./views/platform/PowerMenuEdit.vue'], resolve)
	},
	{
		path: 'powerRole',
        meta: {
            title: '角色配置'
        },
    	component: (resolve) => require(['./views/platform/PowerRole.vue'], resolve)
	},
	{
		path: 'powerRoleEdit/:roleId',
        meta: {
            title: '角色配置 - 编辑'
        },
    	component: (resolve) => require(['./views/platform/PowerRoleEdit.vue'], resolve)
	},
	{
		path: 'powerRoleAccountEdit',
        meta: {
            title: '角色配置 - 分配账号'
        },
    	component: (resolve) => require(['./views/platform/PowerRoleAccountEdit.vue'], resolve)
	},
	{
		path: 'powerAccount',
        meta: {
            title: '账号管理'
        },
    	component: (resolve) => require(['./views/platform/PowerAccount.vue'], resolve)
	},
	{
		path: 'powerAccountEdit/:adminId',
        meta: {
            title: '账号管理 - 编辑'
        },
    	component: (resolve) => require(['./views/platform/PowerAccountEdit.vue'], resolve)
	},
	{
		path: 'powerAccountRoleEdit/:adminId',
        meta: {
            title: '账号管理 - 分配角色'
        },
    	component: (resolve) => require(['./views/platform/PowerAccountRoleEdit.vue'], resolve)
	},
	{
		path: 'basicDict',
        meta: {
            title: '数据字典'
        },
    	component: (resolve) => require(['./views/platform/BasicDict.vue'], resolve)
	},
	{
		path: 'basicDictEdit/:id',
        meta: {
            title: '数据字典 - 编辑'
        },
    	component: (resolve) => require(['./views/platform/BasicDictEdit.vue'], resolve)
	},
	{
		path: 'basicDictInfo/:code',
        meta: {
            title: '数据字典 - 数据列表'
        },
    	component: (resolve) => require(['./views/platform/BasicDictInfo.vue'], resolve)
	},
	{
		path: 'basicDictInfoEdit/:code/:id',
        meta: {
            title: '数据字典 - 编辑数据'
        },
    	component: (resolve) => require(['./views/platform/BasicDictInfoEdit.vue'], resolve)
	},
	{
		path: 'basicLinkage',
        meta: {
            title: '联动菜单'
        },
    	component: (resolve) => require(['./views/platform/BasicLinkage.vue'], resolve)
	},
	{
		path: 'basicLinkageEdit/:id',
        meta: {
            title: '联动菜单 - 编辑'
        },
    	component: (resolve) => require(['./views/platform/BasicLinkageEdit.vue'], resolve)
	},
	{
		path: 'basicLinkageChild/:code/:pid',
        meta: {
            title: '联动菜单 - 子菜单'
        },
    	component: (resolve) => require(['./views/platform/BasicLinkageChild.vue'], resolve)
	},
	{
		path: 'basicLinkageChildEdit/:code/:pid/:id',
        meta: {
            title: '联动菜单 - 子菜单编辑'
        },
    	component: (resolve) => require(['./views/platform/BasicLinkageChildEdit.vue'], resolve)
	},
	{
		path: 'basicNotice',
        meta: {
            title: '联动菜单 - 子菜单'
        },
    	component: (resolve) => require(['./views/platform/BasicNotice.vue'], resolve)
	},
	{
		path: 'basicNoticeEdit/:id',
        meta: {
            title: '联动菜单 - 子菜单编辑'
        },
    	component: (resolve) => require(['./views/platform/BasicNoticeEdit.vue'], resolve)
	},
	{
		path: 'basicTips',
        meta: {
            title: '建议反馈列表'
        },
    	component: (resolve) => require(['./views/platform/BasicTips.vue'], resolve)			
	},
	// 房屋登记
    {
        path: '',
        component: (resolve) => require(['./views/checkstand/Index.vue'], resolve),
        children:[
            {
                path: '',
                meta: {
                    title: '客房登记 - 入住'
                },
                component: (resolve) => require(['./views/checkstand/In.vue'], resolve)
            },
            {
                path: 'checkstandEdit/:id',
                meta: {
                    title: '客房登记 - 入住 - 订单编辑'
                },
                component: (resolve) => require(['./views/checkstand/Edit.vue'], resolve)
            },
            {
                path: 'checkstandView/:id',
                meta: {
                    title: '客房登记 - 入住 - 订单编辑'
                },
                component: (resolve) => require(['./views/checkstand/View.vue'], resolve)
            },
            {
                path: 'checkstandChange',
                meta: {
                    title: '客房登记 - 入住 - 换房'
                },
                component: (resolve) => require(['./views/checkstand/Change.vue'], resolve)
            },
            {
                path: 'checkstandOut',
                meta: {
                    title: '客房登记 - 入住 - 退房'
                },
                component: (resolve) => require(['./views/checkstand/Out.vue'], resolve)
            },
            {
                path: 'checkstandOrder',
                meta: {
                    title: '客房登记 - 预订'
                },
                component: (resolve) => require(['./views/checkstand/Order.vue'], resolve)
            },
            {
                path: 'checkstandOrderEdit',
                meta: {
                    title: '客房登记 - 预订 - 编辑'
                },
                component: (resolve) => require(['./views/checkstand/OrderEdit.vue'], resolve)
            },
            {
                path: 'checkstandOrderView',
                meta: {
                    title: '客房登记 - 预订 - 编辑'
                },
                component: (resolve) => require(['./views/checkstand/OrderView.vue'], resolve)
            }
        ]
    }
];
const tourist = [
	{
    	path: '',
        meta: {
            title: ''
        },
        component: (resolve) => require(['./views/tourist/Index.vue'], resolve)
    },
    {
    	path: 'login',
        meta: {
            title: '登录'
        },
        component: (resolve) => require(['./views/tourist/SignIn.vue'], resolve)
    },
    {
    	path: 'register',
        meta: {
            title: '登录'
        },
        component: (resolve) => require(['./views/tourist/SignUp.vue'], resolve)
    },
];
const routers = [
    {
    	path: '/',
    	component: (resolve) => require(['./views/TouristLayout.vue'], resolve),
    	children:tourist
    },
    {
        path: '/admin',
        component: (resolve) => require(['./views/AdminLayout.vue'], resolve),
        children:admin
    }
];
export default routers;