const interfaces={
    'login': 'admin/login',
    'sex':'site/sex',
    'register': 'admin/register',
    'resetMchPwd': 'merchant/admin/reset-password',
    'adminInfo': 'merchant/admin/info',
    'adminInfoModify': 'merchant/admin/info-modify',
    'tips': 'merchant/merchant/feedback',
    'roomList':'merchant/room/list',
    'roomEditPageInfo':'merchant/room/edit-page-info',
    'roomTypes':'merchant/room-type/list',
    'roomType':'merchant/room-type/view',
    'roomTypeEdit':'merchant/room-type/record',
    'roomTypeDelete':'merchant/room-type/delete',
    'roomWeekPrice':'merchant/room-price/view-week',
    'roomWeekPriceSave':'merchant/room-price/record',
    'dictionaryView':'platform/dictionary/view',
    'dictionaryViewByCode':'platform/dictionary/view-by-code',
    'dictionaries':'platform/dictionary/list',
    'dictionaryDelete':'platform/dictionary/delete',
    'dictionaryRecord':'platform/dictionary/record',
    'dictionaryItemRecord':'platform/dictionary/item-record',
    'dictionaryItemDelete':'platform/dictionary/delete-item',
    'dictionaryItemList':'platform/dictionary/item-list',
    'dictionaryItemView':'platform/dictionary/item-view'
};
const server={
    'host': 'http://www.hotel.com',
    'logoutCode': [100010,100011],
    'loginPath': '/login',
    'interface': interfaces
};
export default server;