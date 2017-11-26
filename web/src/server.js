const interfaces={
    'login': 'admin/login',
    'loginOut':'site/login-out',
    'sex':'site/sex',
    'register': 'admin/register',
    'resetMchPwd': 'merchant/admin/reset-password',
    'merchantAdminInfo': 'merchant/admin/info',
    'merchantAdminInfoModify': 'merchant/admin/info-modify',
    'resetPlatformPwd':'platform/admin/reset-password',
    'platformAdminInfo':'platform/admin/info',
    'platformAdminInfoModify':'platform/admin/info-modify',
    'tips': 'merchant/merchant/feedback',
    'roomList':'merchant/room/list',
    'roomRecord':'merchant/room/record',
    'roomDelete':'merchant/room/delete',
    'roomLock':'merchant/room/lock',
    'roomEditPageInfo':'merchant/room/edit-page-info',
    'roomTypes':'merchant/room-type/list',
    'roomType':'merchant/room-type/view','roomTypeEdit':'merchant/room-type/record',
    'roomTypeDelete':'merchant/room-type/delete',
    'roomWeekPrice':'merchant/room-price/view-week',
    'roomWeekPriceSave':'merchant/room-price/record',
    'roomDayPrices':'merchant/room-price/day-prices',
    'roomDayPrice':'merchant/room-price/day-price',
    'roomDayPriceSet':'merchant/room-price/day-price-set',
    'roomDayPriceDel':'merchant/room-price/del-day-price',
    'dictionaryView':'platform/dictionary/view',
    'dictionaryViewByCode':'platform/dictionary/view-by-code',
    'dictionaries':'platform/dictionary/list',
    'dictionaryDelete':'platform/dictionary/delete',
    'dictionaryRecord':'platform/dictionary/record',
    'dictionaryItemRecord':'platform/dictionary/item-record',
    'dictionaryItemDelete':'platform/dictionary/delete-item',
    'dictionaryItemList':'platform/dictionary/item-list',
    'dictionaryItemView':'platform/dictionary/item-view',
    'storeBaseSet':'merchant/merchant/base-info-setting',
    'storeSet':'merchant/merchant/setting',
    'storeConfig':'merchant/merchant/config',
    'channelList':'merchant/channel/list',
    'channelView':'merchant/channel/view',
    'channelRecord':'merchant/channel/record',
    'channelDel':'merchant/channel/delete',
    'channelAll':'merchant/channel/all',
    'mchNoticeList':'merchant/notice/list',
    'mchNoticeRead':'merchant/notice/read',
    'platformNoticeList':'platform/notice/list',
    'platformNoticeView':'platform/notice/view',
    'platformNoticeEdit':'platform/notice/edit',
    'platformNoticePublic':'platform/notice/public',
    'platformNoticeDelete':'platform/notice/delete',
    'platformNoticeRevoke':'platform/notice/revoke',
    'linkageMenuList':'platform/linkage-menu/list',
    'linkageMenuView':'platform/linkage-menu/view',
    'linkageMenuRecord':'platform/linkage-menu/record',
    'linkageMenuDelete':'platform/linkage-menu/delete',
    'linkageMenuItemList':'platform/linkage-menu/item-list',
    'linkageMenuItemView':'platform/linkage-menu/item-view',
    'linkageMenuItemRecord':'platform/linkage-menu/item-record',
    'linkageMenuItemDelete':'platform/linkage-menu/item-delete',
    'merchantMemberList':'merchant/member/list',
    'merchantMemberEdit':'merchant/member/record',
    'merchantMemberDelete':'merchant/member/delete',
    'merchantMemberEditInfo':'merchant/member/edit-page-info',
    'merchantMemberBlack':'merchant/member/black',
    'merchantMemberBlackSet':'merchant/member/put-to-black',
    'merchantMemberBlackList':'merchant/member/black-list',
    'merchantMemberBlackRemove':'merchant/member/remove-from-black',
    'merchantMemberBlackAdd':'merchant/member/add-black',
    'checkstandRoom':'merchant/checkstand/room',
    'checkstandRoomFilter':'merchant/checkstand/room-filter',
    'platformFeedbackList':'platform/feedback/list',
    'platformFeedbackAnswer':'platform/feedback/answer',
    'platformFeedbackCancel':'platform/feedback/cancel-answer',
    'merchantAdminList':'merchant/admin/list',
    'merchantAdminDel':'merchant/admin/delete',
    'merchantAdminChange':'merchant/admin/change',
    'merchantAdminAdd':'merchant/admin/add',
    'merchantAdminRoles':'merchant/admin/roles',
    'platformAdminList':'platform/admin/list',
    'platformAdminDel':'platform/admin/delete',
    'platformAdminChange':'platform/admin/change',
    'platformAdminAdd':'platform/admin/add',
    'platformAdminRoles':'platform/admin/roles',
    'platformRoleList':'platform/role/list',
    'platformRoleChangeStatus':'platform/role/change-status',
    'platformRoleDel':'platform/role/delete',
    'platformRoleDetail':'platform/role/detail',
    'platformRoleEdit':'platform/role/edit',
    'merchantRoom':'merchant/room/place-page',
    'merchantOccupancy':'merchant/order/occupancy',
    'merchantReverse':'merchant/order/reverse',
    'merchantAllRoomType':'merchant/room-type/all-type',
    'merchantAllRoom':'merchant/room/all-room',
    'merchantOrderList':'merchant/order/list',
    'merchantAllOrderAbnormal':'merchant/dictionary/all-order-abnormal',
    'merchantPaymentChannel':'merchant/dictionary/payment-channel',
    'merchantExpanseItem':'merchant/dictionary/expanse-item',
    'merchantMemberRanks':'merchant/member/ranks',
    'merchantMemberRank':'merchant/member/rank',
    'merchantMemberRankDel':'merchant/member/del-rank',
    'merchantMemberRankEdit':'merchant/member/rank-divide',
    'merchantMemberAllRank':'merchant/member/all-rank',
    'merchantNumberTypes':'merchant/dictionary/number-types',
    'merchantDiscountList':'merchant/activity/discount-list',
    'merchantDiscountActiveEdit':'merchant/activity/edit-discount',
    'merchantActivityInfo':'merchant/activity/info',
    'merchantActivityDel':'merchant/activity/del',
    'merchantRoomByTree':'merchant/activity/rooms-by-tree',
    'merchantMemberRankFoActivity':'merchant/member/ranks-for-activity',
    'merchantActivityPlans':'merchant/activity/plans',
    'merchantActivityAddPlan':'merchant/activity/add-plan',
    'merchantActivityDelPlan':'merchant/activity/del-plan'
};
const server={
    'host': 'http://www.hotel.com',
    'logoutCode': [100010,100011],
    'loginPath': '/login',
    'interface': interfaces
};
export default server;