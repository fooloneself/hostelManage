const interfaces={
    'login': 'admin/login',
    'register': 'admin/register',
    'resetMchPwd': 'merchant/admin/reset-password'
};
const logoutCode=[100010,100011];
const server={
    'host': 'http://www.hotel.com',
    'logoutCode': logoutCode,
    'loginPath': '/login',
    'interface': interfaces
};
export default server;