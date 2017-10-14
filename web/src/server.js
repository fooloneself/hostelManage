const interfaces={
    'login': 'admin/login',
    'sex':'site/sex',
    'register': 'admin/register',
    'resetMchPwd': 'merchant/admin/reset-password',
    'adminInfo': 'merchant/admin/info',
    'adminInfoModify': 'merchant/admin/info-modify'
};
const server={
    'host': 'http://www.hotel.com',
    'logoutCode': [100010,100011],
    'loginPath': '/login',
    'interface': interfaces
};
export default server;